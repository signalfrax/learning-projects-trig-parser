<?php


namespace RDFPhp\Parser;

use Generator;
use RDFPhp\Parser\UnicodeRanges as UR;
use Parle\Lexer;
use Parle\LexerException;
use Parle\Parser as ParleParser;
use Parle\Token;
use RDFPhp\Namespaces;

/**
 * Class TrigParser
 * @package RDF\Parser
 *
 * The parser is implemented using a Lexer and Scanner.
 * For both Parle is used.
 */
class TrigParser implements Parser
{
    const IRI_NODE = 0;
    const BLANK_NODE = 1;

    /** @var string */
    protected $message;

    /** @var Lexer */
    protected $lexer;

    /** @var Parser */
    protected $parser;

    protected $actions = [];

    protected $resolvedPrefixedIri = [];
    protected $prefixes = [];
    protected $base = [];
    protected $string = null;
    protected $previousAction = null;

    // Nodes is a stack that holds iri references or blanks for the next RDF terms to be processed.
    protected $nodes = [];

    // Instance variables for processing blank- nodes and property lists.
    protected $blankNodeCounter = 0;
    protected $blankNodeMap = [];
    protected $blankNodePropListStartActions = [];
    protected $openBrackets = [];

    // Instance variables for processing collections
    protected $openParenthesis = [];

    const WS = "\x20|\x9|\xD|\xA";
    const HEX = "[0-9]|[a-f]|[A-F]";
    const PN_LOCAL_ESC = '\\\\(_|~|\.|-|!|\$|&|\\\'|\(|\)|\*|\+|,|;|=|\/|\?|#|@|%)';
    const PERCENT = '%(' . self::HEX . '){2,2}';
    const UCHAR = "\\\\u(" .self::HEX. "){4,4}|\\\\U(" . self::HEX . "){8,8}";
    const ECHAR = "\\\\[tbnrf\"'\\\\]";
    const NIL = "\((" . self::WS. ")*\)";
    const ANON = "\[(" . self::WS . ")*\]";
    const IRIREF = '<([^<>\x00-\x20\{\}"\|\^`\\\\]|'. self::UCHAR .')*>';
    const LANGTAG = "@[a-zA-Z]+(-[a-zA-Z0-9]+)*";
    const INTEGER = "[+-]?[0-9]+";
    const DECIMAL = '[+-]?([0-9]*\.[0-9]+)';
    const EXPONENT = '[eE][+-]?[0-9]+';
    const DOUBLE = '[+-]?([0-9]+\.[0-9]*' . self::EXPONENT. '|\.[0-9]+' . self::EXPONENT . '|[0-9]+' . self::EXPONENT . ')';
    const STRING_LITERAL_QUOTE = '\"([^\x22\x5C\xA\xD]|' . self::ECHAR. '|' . self::UCHAR . ')*\"';
    const STRING_LITERAL_SINGLE_QUOTE = '\\\'([^\x27\x5C\xA\xD]|' . self::ECHAR . '|' . self::UCHAR . ')*\\\'';
    const STRING_LITERAL_LONG_SINGLE_QUOTE = "'''(('|'')?([^'\\\\]|" . self::ECHAR . "|" . self::UCHAR . "))*'''";
    const STRING_LITERAL_LONG_QUOTE = '\"\"\"((\"|\"\")?([^\"\\\\]|'. self::ECHAR . "|" . self::UCHAR . '))*\"\"\"';
    const PN_CHARS_BASE = '[A-Z]|[a-z]' . '|' . UR::RANGE_00C0_00D6 . '|' . UR::RANGE_00D8_00F6 . '|' . UR::RANGE_00F8_02FF
    .'|' . UR::RANGE_0370_037D . '|' . UR::RANGE_200C_200D . '|' . UR::RANGE_2070_218F . '|' . UR::RANGE_2C00_2FEF
    .'|' . UR::RANGE_F900_FDCF . '|' . UR::RANGE_FDF0_FFFD . '|' . UR::RANGE_037F_1FFF . '|' . UR::RANGE_3001_D7FF
    .'|' . UR::RANGE_10000_1007F . '|' . UR::RANGE_10080_100FF . '|' . UR::RANGE_10100_1013F . '|' . UR::RANGE_10300_1032F
    .'|' . UR::RANGE_10380_1039F . '|' . UR::RANGE_10400_1044F . '|' . UR::RANGE_10450_1047F . '|' . UR::RANGE_10480_104AF
    .'|' . UR::RANGE_10800_1083F . '|' . UR::RANGE_1D000_1D0FF . '|' . UR::RANGE_1D100_1D1FF . '|' . UR::RANGE_1D300_1D35F
    .'|' . UR::RANGE_1D400_1D7FF . '|' . UR::RANGE_20000_2A6DF . '|' . UR::RANGE_2F800_2FA1F;
    const PN_CHARS_U = self::PN_CHARS_BASE . '|_';
    const PN_CHARS = self::PN_CHARS_U . '|' . '-' .'|'. '[0-9]' . '|' . UR::RANGE_00B7 . '|' . UR::RANGE_0300_036F . '|' . UR::RANGE_203F_2040;
    const PN_PREFIX = self::PN_CHARS_BASE . '|' . '((' . self::PN_CHARS . '|\.)*(' . self::PN_CHARS . '))?';
    const PLX = self::PERCENT . '|' . self::PN_LOCAL_ESC;
    const PN_LOCAL = '(' . self:: PN_CHARS_U . '|:|[0-9]|' . self::PLX. ')((' . self::PN_CHARS . '|\.|:|' . self::PLX . ')*(' . self::PN_CHARS . '|:|' . self::PLX . '))?';
    const BLANK_NODE_LABEL = '_:(' . self::PN_CHARS_U . '|[0-9])((' . self::PN_CHARS .'|\.)*(' . self::PN_CHARS . '))?';
    const PNAME_NS = '(' . self::PN_PREFIX . ')*:';
    const PNAME_LN = self::PNAME_NS . self::PN_LOCAL;

    public function __construct()
    {
        $this->base = parse_url('http://rdf-php');
        $this->parser = new ParleParser();
        $this->buildTokens();
        $this->buildRules();
        $this->parser->build();
        $this->buildLexer();

        $this->blankNodePropListStartActions = [
            $this->actions['predicateA'],
            $this->actions['predicateIri'],
        ];

    }

    /**
     * Parse the RDF TriG.
     *
     * @param string $message
     * @return Generator
     * @throws ParserException
     */
    public function parse(string $message): Generator
    {
        $this->parser->consume($message, $this->lexer);

        do {
            switch ($this->parser->action) {
                case ParleParser::ACTION_ERROR:

                    $error = $this->parser->errorInfo();
                    $token = !is_null($error->token) ? json_encode($error->token) : null;
                    throw new ParserException("Invalid RDF Trig format. [{$error->id}] [{$error->position}]\n$token\n. \n>>>>\n$message\n<<<<<\n");
                    break;

                case ParleParser::ACTION_SHIFT:
                case ParleParser::ACTION_GOTO:
                case ParleParser::ACTION_ACCEPT:
                    break;
                case ParleParser::ACTION_REDUCE:

                    switch ($this->parser->reduceId) {

                        case $this->actions['sparqlPrefix']:
                        case $this->actions['prefixID']:

                            // Prefixes are added to a prefix map.
                            // All unicode codes are unescaped before adding adding the prefix to the map.
                            $prefix = trim($this->parser->sigil(1), ':');
                            $iri = $this->unescapeNumeric(trim($this->parser->sigil(2), '<>'));
                            $this->prefixes[$prefix] = $iri;
                            yield Parser::PREFIX => [ $prefix, $iri ];
                            break;

                        case $this->actions['base']:
                        case $this->actions['sparqlBase']:

                            // The base is used to resolve relative iri references.
                            // Base directives are cascading meaning that a base itself, if relative, will be resolved
                            // with a previous base.
                            // If no bases are provided then the default base in this parser is used.
                            $trimmed = trim($this->parser->sigil(1), '<>');
                            $result = $this->unescapeNumeric($trimmed);
                            $this->base = parse_url(resolve_relative_iri($this->base, $result));
                            yield Parser::BASE => [ $trimmed ];
                            break;

                        case $this->actions['iriPrefixedName']:

                            // Prefixed iris need to be resolved according to the prefix directives.
                            // All reserved characters need to be unescaped and the namespace of the prefix
                            // prepended to the localname. e.g. xsd:integer -> http://www.w3.org/2001/XMLSchema#integer
                            $iri = $this->parser->sigil(0);
                            if (!isset($this->resolvedPrefixedIri[$iri])) {
                                $prefix = substr($iri, 0, strpos($iri, ':'));
                                $localName = $this->unescapeReservedCharacters(substr($iri, strpos($iri, ':') + 1));
                                if (!isset($this->prefixes[$prefix])) {
                                    throw new ParserException("Invalid RDF document. Cannot find prefix [{$prefix}:]");
                                }
                                $this->resolvedPrefixedIri[$iri] = "{$this->prefixes[$prefix]}$localName";
                            }
                            $this->nodes[] = [ self::IRI_NODE => $this->resolvedPrefixedIri[$iri] ];
                            break;

                        case $this->actions['iriRef']:

                            // All iri references need to be unescaped, resolved and validated.
                            $escaped = $this->unescapeNumeric(trim($this->parser->sigil(0), '<>'));
                            if (preg_match('/[<>\x00-\x20\{\}"\|\^`\\\\]+/', $escaped)) {
                                throw new ParserException("Invalid IRIREF [{$this->parser->sigil(0)}]");
                            }
                            $this->nodes[] = [ self::IRI_NODE => resolve_relative_iri($this->base, $escaped) ];

                            break;

                        case $this->actions['BlankNodeAnon']:

                            // For each anonymous blank node a new blank node is generated.
                            $this->nodes[] = [ self::BLANK_NODE => $this->generateBlankNode($this->blankNodeCounter++) ];
                            break;

                        case $this->actions['BlankNodeLabel']:

                            // For each blank node label a new blank node is generated added to a blank node map.
                            // All occurrences of this labeled blank node are replaced by the generated blank node.
                            $blankLabel = $this->parser->sigil(0);
                            if (!isset($this->blankNodeMap[$blankLabel])) {
                                $this->blankNodeMap[$blankLabel] = $this->generateBlankNode($this->blankNodeCounter++);
                            }
                            $this->nodes[] = [ self::BLANK_NODE => $this->blankNodeMap[$blankLabel] ];
                            break;

                        case $this->actions['graph']:
                        case $this->actions['wrappedGraph']:

                            $node = array_pop($this->nodes);
                            (self::IRI_NODE == key($node)) ? yield Parser::GRAPH => [  current($node) ] : yield Parser::GRAPH_BLANK_NODE => [ current($node) ];
                            break;

                        case $this->actions['blankNodePropertyList']:

                            // Let the RDF decoder know that we are done with processing the current BlankNodePropertyList.
                            yield Parser::SUBJECT_BLANK_NODE => [ $this->generateBlankNode($this->blankNodeCounter++) ];
                            yield Parser::BLANK_NODE_PROPERTY_LIST_CLOSE => null;
                            break;

                        case $this->actions['collectionEmpty']:

                            $this->nodes[] = [ self::IRI_NODE => Namespaces::RDF_NIL ];
                            break;

                        case $this->actions['collectionObjects']:

                        case $this->actions['subjectWithoutGraph']:
                        case $this->actions['subjectBlank']:
                        case $this->actions['subjectIri']:

                            $node = array_pop($this->nodes);
                            (self::IRI_NODE == key($node)) ? yield Parser::SUBJECT => [ current($node) ] : yield Parser::SUBJECT_BLANK_NODE => [ current($node) ];
                            $this->previousAction = $this->parser->reduceId;
                            break;

                        case $this->actions['predicateIri']:
                        case $this->actions['predicateA']:

                            // BlankNodePropertyList can be in the subject or object position.
                            // We trigger the processing of blank node property list (BNPL) by looking at the openBrackets and the previous action.
                            // When the previous action is a predicate and we have an open bracket then the BNPL is in the object position.
                            // When the previous action is none then the BNPL is in the subject position.
                            // For both of these actions we yield a BLANK_NODE_PROPERTY_LIST_OPEN so that the RDF encoder knows which predicates and objects
                            // to attach to the BNPL blank node.
                            if (!empty($this->openBrackets) && (in_array($this->previousAction, $this->blankNodePropListStartActions) || is_null($this->previousAction)) ) {
                                array_pop($this->openBrackets);
                                yield Parser::BLANK_NODE_PROPERTY_LIST_OPEN => null;
                            }

                            $this->previousAction = $this->parser->reduceId;
                            yield Parser::PREDICATE => [ ($this->parser->reduceId == $this->actions['predicateIri']) ? current(array_pop($this->nodes)) : Namespaces::RDF_TYPE ];

                            break;

                        case $this->actions['objectBlank']:
                        case $this->actions['objectIri']:

                            $node = array_pop($this->nodes);
                            (self::IRI_NODE == key($node)) ? yield Parser::OBJECT_IRI => [ current($node) ] : yield Parser::OBJECT_BLANK_NODE => [ current($node) ];
                            break;

                        case $this->actions['objectBoolean']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                trim($this->parser->sigil(0), '"\''),
                                Namespaces::XSD_BOOLEAN,
                            ];

                            break;
                        case $this->actions['objectInteger']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                trim($this->parser->sigil(0), '"\''),
                                Namespaces::XSD_INTEGER,
                            ];
                            break;

                        case $this->actions['objectDecimal']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                trim($this->parser->sigil(0), '"\''),
                                Namespaces::XSD_DECIMAL,
                            ];
                            break;

                        case $this->actions['objectDouble']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                trim($this->parser->sigil(0), '"\''),
                                Namespaces::XSD_DOUBLE,
                            ];
                            break;

                        case $this->actions['objectString']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                $this->string,
                                Namespaces::XSD_STRING,
                            ];
                            break;

                        case $this->actions['objectStringIri']:

                            yield Parser::OBJECT_WITH_DATATYPE => [
                                $this->string,
                                current(array_pop($this->nodes)),
                            ];
                            break;

                        case $this->actions['objectStringLang']:

                            yield Parser::OBJECT_WITH_LANG_TAG => [
                                $this->string,
                                Namespaces::RDF_LANG_STRING,
                                $this->parser->sigil(1),
                            ];
                            break;

                        case $this->actions['stringLiteralQuote']:
                        case $this->actions['stringLiteralSingleQuote']:

                            // String literals are trimmed of single/double quotes, unicodes and strings (\t\n\r\b) are escaped.
                            $result = $this->parser->sigil(0);
                            $this->string = $this->unescapeString($this->unescapeNumeric(substr($result, 1, strlen($result) - 2)));
                            break;

                        case $this->actions['stringLiteralLongQuote']:
                        case $this->actions['stringLiteralLongSingleQuote']:

                            // String literals are trimmed of single/double quotes, unicodes and strings (\t\n\r\b) are escaped.
                            $result = $this->parser->sigil(0);
                            $this->string = $this->unescapeString($this->unescapeNumeric(substr($result, 3, strlen($result) - 6)));
                            break;

                        case $this->actions['trigDocDirective']:
                        case $this->actions['trigDocBlock']:

                            // Clean up before we process the next directive or block.
                            $this->previousAction = null;
                            $this->openBrackets = [];
                            $this->openParenthesis = [];

                    }
                    break;
            }

            $this->parser->advance();

        } while (ParleParser::ACTION_ACCEPT != $this->parser->action);
    }

    /**
     * Unescape unicode characters.
     *
     * It's called numeric to keep it in line with the official name used by the W3C Trig Spec.
     *
     * @param string $token
     * @return string
     */
    protected function unescapeNumeric(string $token): string
    {
        return preg_replace_callback([
            '/\\\\u([0-9A-Fa-f]{4,4})/',
            '/\\\\U([0-9A-Fa-f]{8,8})/',
        ], function($matches) {

            // This is the only way to programmatically generate a unicode string.
            $code = <<<HEREDOC
                return sprintf("%s", "\\u{{$matches[1]}}");
HEREDOC;
            return eval($code);
        } , $token);
    }

    /**
     * Unescape string escapes. :)
     *
     * @param string $token
     * @return string
     */
    protected function unescapeString(string $token): string
    {
        return preg_replace_callback('/\\\\([tbnrf"\'\\\\])/', function($matches) {
            if (in_array($matches[1], ['"', "'"])) {
                return $matches[1];
            } else if ('\\\\' == $matches[1]) {
                return '\\';
            } else if ('b' == $matches[1]) {
                return "\x8";
            } else {

                // The only way to programmatically generate unescaped strings.
                $code = <<<HEREDOC
                return sprintf("%s", "\\{$matches[1]}");
HEREDOC;
                return eval($code);
            }
        } , $token);
    }

    /**
     * Unescape reserved characters for the local name of a prefixed name.
     *
     * @param string $token
     * @return string
     */
    protected function unescapeReservedCharacters(string $token): string
    {
        return preg_replace("/\\\\([~\.\-\!\$&'\(\)\*\+,;\=\/\?#@%_])/", '$1', $token);
    }

    protected function generateBlankNode(int $counter): string
    {
        return "_:b$counter";
    }

    protected function buildTokens()
    {
        $this->parser->token("'{'");
        $this->parser->token("'}'");
        $this->parser->token("'['");
        $this->parser->token("']'");
        $this->parser->token("'('");
        $this->parser->token("')'");
        $this->parser->token("'()'");
        $this->parser->token("'true'");
        $this->parser->token("'false'");
        $this->parser->token("','");
        $this->parser->token("';'");
        $this->parser->token("'a'");
        $this->parser->token("'.'");
        $this->parser->token("'@prefix'");
        $this->parser->token("'PREFIX'");
        $this->parser->token("'BASE'");
        $this->parser->token("'GRAPH'");
        $this->parser->token("'^^'");
        $this->parser->token('PNAME_NS');
        $this->parser->token('PNAME_LN');
        $this->parser->token('STRING_LITERAL_LONG_QUOTE');
        $this->parser->token('STRING_LITERAL_LONG_SINGLE_QUOTE');
        $this->parser->token('STRING_LITERAL_QUOTE');
        $this->parser->token('STRING_LITERAL_SINGLE_QUOTE');
        $this->parser->token('BLANK_NODE_LABEL');
        $this->parser->token('IRIREF');
        $this->parser->token('LANGTAG');
        $this->parser->token('HEX');
        $this->parser->token('PERCENT');
        $this->parser->token('UCHAR');
        $this->parser->token('ECHAR');
        $this->parser->token('NIL');
        $this->parser->token('WS');
        $this->parser->token('ANON');
        $this->parser->token('INTEGER');
        $this->parser->token('DECIMAL');
        $this->parser->token('DOUBLE');
        $this->parser->token('BOOLEAN');
    }

    protected function buildRules()
    {
        $this->parser->push('START', 'trigDoc');
        $this->actions['trigDocDirective'] = $this->parser->push('trigDoc', 'trigDoc directive');
        $this->actions['trigDocBlock'] = $this->parser->push('trigDoc', 'trigDoc block');
        $this->parser->push('trigDoc', 'directive');
        $this->parser->push('trigDoc', 'block');
        $this->parser->push('trigDoc', '');
        $this->parser->push('directive', 'prefixID');
        $this->parser->push('directive', 'base');
        $this->parser->push('directive', 'sparqlPrefix');
        $this->parser->push('directive', 'sparqlBase');
        $this->parser->push('block', 'triplesOrGraph');
        $this->parser->push('block', 'wrappedGraph');
        $this->parser->push('block', 'triples2');
        $this->actions['graph'] = $this->parser->push('block', "'GRAPH' labelOrSubject wrappedGraph");
        $this->actions['subjectWithoutGraph'] = $this->parser->push('triplesOrGraph', "labelOrSubject predicateObjectList '.'");
        $this->actions['wrappedGraph'] = $this->parser->push('triplesOrGraph', 'labelOrSubject wrappedGraph');
        $this->parser->push('wrappedGraph', " '{' '}' ");
        $this->parser->push('wrappedGraph', " '{' triplesBlock '}' ");
        $this->parser->push('triplesBlock', "triples");
        $this->parser->push('triplesBlock', "triples '.'");
        $this->parser->push('triplesBlock', "triples '.' triplesBlock");
        $this->parser->push('triples', 'subject predicateObjectList');
        $this->parser->push('triples', 'blankNodePropertyList');
        $this->parser->push('triples', 'blankNodePropertyList predicateObjectList');
        $this->parser->push('triples2', "blankNodePropertyList '.'");
        $this->parser->push('triples2', "blankNodePropertyList predicateObjectList '.'");
        $this->parser->push('triples2', "collection predicateObjectList '.'");
        $this->actions['subjectIri'] = $this->parser->push('subject', 'iri');
        $this->actions['subjectBlank'] = $this->parser->push('subject', 'blank');
        $this->parser->push('labelOrSubject', 'iri');
        $this->parser->push('labelOrSubject', 'BlankNode');
        $this->parser->push('predicateObjectList', "predicateObjectList ';' predicateObjectList");
        $this->parser->push('predicateObjectList', "predicateObjectList ';'");
        $this->parser->push('predicateObjectList', 'verb objectList');
        $this->parser->push('objectList', "objectList ',' objectList");
        $this->parser->push('objectList', 'object');
        $this->actions['objectIri'] = $this->parser->push('object', 'iri');
        $this->parser->push('object', 'literal');
        $this->actions['objectBlank'] = $this->parser->push('object', 'blank');
        $this->parser->push('object', 'blankNodePropertyList');
        $this->actions['blankNodePropertyList'] = $this->parser->push('blankNodePropertyList', " '[' predicateObjectList ']' ");
        $this->parser->push('verb', 'predicate');
        $this->actions['predicateA'] = $this->parser->push('verb', "'a'");
        $this->actions['predicateIri'] = $this->parser->push('predicate', 'iri');
        $this->parser->push('literal', 'BooleanLiteral');
        $this->parser->push('literal', 'NumericalLiteral');
        $this->parser->push('literal', 'RDFLiteral');
        $this->parser->push('blank', 'BlankNode');
        $this->parser->push('blank', 'collection');
        $this->actions['collectionEmpty'] = $this->parser->push('collection', " '()' ");
        $this->actions['collectionObjects'] = $this->parser->push('collection', "'(' objects ')'");
        $this->parser->push('objects', 'objects object');
        $this->parser->push('objects', 'object');
        $this->actions['BlankNodeLabel'] = $this->parser->push('BlankNode', 'BLANK_NODE_LABEL');
        $this->actions['BlankNodeAnon'] = $this->parser->push('BlankNode', 'ANON');
        $this->actions['objectBoolean'] = $this->parser->push('BooleanLiteral', 'BOOLEAN');
        $this->actions['objectInteger'] = $this->parser->push('NumericalLiteral', 'INTEGER');
        $this->actions['objectDecimal'] = $this->parser->push('NumericalLiteral', 'DECIMAL');
        $this->actions['objectDouble'] = $this->parser->push('NumericalLiteral', 'DOUBLE');
        $this->actions['objectString'] = $this->parser->push('RDFLiteral', 'String');
        $this->actions['objectStringLang'] = $this->parser->push('RDFLiteral', 'String LANGTAG');
        $this->actions['objectStringIri'] = $this->parser->push('RDFLiteral', "String '^^' iri");
        $this->actions['stringLiteralQuote'] = $this->parser->push('String', 'STRING_LITERAL_QUOTE');
        $this->actions['stringLiteralSingleQuote'] = $this->parser->push('String', 'STRING_LITERAL_SINGLE_QUOTE');
        $this->actions['stringLiteralLongQuote'] = $this->parser->push('String', 'STRING_LITERAL_LONG_QUOTE');
        $this->actions['stringLiteralLongSingleQuote'] = $this->parser->push('String', 'STRING_LITERAL_LONG_SINGLE_QUOTE');
        $this->actions['iriRef'] = $this->parser->push('iri', 'IRIREF');
        $this->actions['iriPrefixedName'] = $this->parser->push('iri', 'PrefixedName');
        $this->parser->push('PrefixedName', 'PNAME_LN');
        $this->parser->push('PrefixedName', 'PNAME_NS');
        $this->actions['prefixID'] = $this->parser->push('prefixID', "'@prefix' PNAME_NS IRIREF '.'");
        $this->actions['sparqlPrefix'] = $this->parser->push('sparqlPrefix', "'PREFIX' PNAME_NS IRIREF");
        $this->actions['base'] = $this->parser->push('base', "'@base' IRIREF '.'");
        $this->actions['sparqlBase'] = $this->parser->push('sparqlBase', "'BASE' IRIREF");
    }

    protected function buildLexer()
    {
        // DO NOT REORDER!!! THIS WILL BREAK THE LEXER!!!
        $this->lexer = new Lexer();
        $this->lexer->push("#.*(\n|\r\n)", Token::SKIP);
        $this->lexer->push('\{', $this->parser->tokenId("'{'"));
        $this->lexer->push('\}', $this->parser->tokenId("'}'"));
        $this->lexer->push('\^\^', $this->parser->tokenId("'^^'"));
        $this->lexer->push('\[', $this->parser->tokenId("'['"));
        $this->lexer->push(']', $this->parser->tokenId("']'"));
        $this->lexer->push('\(\s*\)', $this->parser->tokenId("'()'"));
        $this->lexer->push('\(', $this->parser->tokenId("'('"));
        $this->lexer->push('\)', $this->parser->tokenId("')'"));
        $this->lexer->push('(true|false)', $this->parser->tokenId('BOOLEAN'));
        $this->lexer->push(',', $this->parser->tokenId("','"));
        $this->lexer->push(';', $this->parser->tokenId("';'"));
        $this->lexer->push('a', $this->parser->tokenId("'a'"));
        $this->lexer->push('\.', $this->parser->tokenId("'.'"));
        $this->lexer->push('@prefix', $this->parser->tokenId("'@prefix'"));
        $this->lexer->push('@base', $this->parser->tokenId("'@base'"));
        $this->lexer->push('(?i:PREFIX)', $this->parser->tokenId("'PREFIX'"));
        $this->lexer->push('(?i:BASE)', $this->parser->tokenId("'BASE'"));
        $this->lexer->push('(?i:GRAPH)', $this->parser->tokenId("'GRAPH'"));
        $this->lexer->push(self::INTEGER, $this->parser->tokenId('INTEGER'));
        $this->lexer->push(self::DECIMAL, $this->parser->tokenId('DECIMAL'));
        $this->lexer->push(self::DOUBLE, $this->parser->tokenId('DOUBLE'));
        $this->lexer->push(self::BLANK_NODE_LABEL, $this->parser->tokenId('BLANK_NODE_LABEL'));
        $this->lexer->push(self::IRIREF, $this->parser->tokenId('IRIREF'));
        $this->lexer->push(self::PNAME_LN, $this->parser->tokenId('PNAME_LN'));
        $this->lexer->push(self::PNAME_NS, $this->parser->tokenId('PNAME_NS'));
        $this->lexer->push(self::STRING_LITERAL_LONG_QUOTE, $this->parser->tokenId('STRING_LITERAL_LONG_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_LONG_SINGLE_QUOTE, $this->parser->tokenId('STRING_LITERAL_LONG_SINGLE_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_QUOTE, $this->parser->tokenId('STRING_LITERAL_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_SINGLE_QUOTE, $this->parser->tokenId('STRING_LITERAL_SINGLE_QUOTE'));
        $this->lexer->push(self::LANGTAG, $this->parser->tokenId('LANGTAG'));
        $this->lexer->push(self::HEX, $this->parser->tokenId('HEX'));
        $this->lexer->push(self::PERCENT, $this->parser->tokenId('PERCENT'));
        $this->lexer->push(self::UCHAR, $this->parser->tokenId('UCHAR'));
        $this->lexer->push(self::ECHAR, $this->parser->tokenId('ECHAR'));
        $this->lexer->push(self::NIL, $this->parser->tokenId('NIL'));
        $this->lexer->push(self::ANON, $this->parser->tokenId('ANON'));
        $this->lexer->push('[\s\n\t]+', Token::SKIP);

        // Signal blank node property list
        $this->lexer->callout($this->parser->tokenId("'['"), function(){
            $this->openBrackets[] = 1;
        });

        // Signal collection list
        $this->lexer->callout($this->parser->tokenId("'('"), function(){
            $this->openParenthesis[] = 1;
        });

        $this->lexer->build();
    }
}
