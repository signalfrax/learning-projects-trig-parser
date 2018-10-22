<?php


namespace RDF\Parser;

use Generator;
use RDF\Parser\UnicodeRanges as UR;
use Parle\Lexer;
use Parle\LexerException;
use Parle\Parser as ParleParser;
use Parle\Token;
use RDF\Namespaces;

/**
 * Class TrigParser
 * @package RDF\Parser
 *
 */
class TrigParser implements Parser
{
    /** @var string */
    private $message;

    /** @var Lexer */
    private $lexer;

    /** @var Parser */
    private $parser;

    private $rules = [];

    private $resolvedPrefixedIri = [];
    private $prefixes = [];
    private $base = null;

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
        $this->parser = new ParleParser();
        $this->buildTokens();
        $this->buildRules();
        $this->parser->build();
        $this->buildLexer();
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
                        case $this->rules['sparqlPrefix']:
                        case $this->rules['prefixID']:
                            $prefix = trim($this->parser->sigil(1), ':');
                            $namespace = trim($this->parser->sigil(2), '<>');
                            $this->prefixes[$prefix] = $namespace;
                            yield Parser::PREFIX => [
                                $prefix,
                                $namespace
                            ];
                            break;
                        case $this->rules['prefixedIri']:
                            $iri = $this->parser->sigil(0);
                            // TODO: Refactor once we implement proper support for blank nodes.
                            if ( ( FALSE === strpos($iri, '_:') || 0 != strpos($iri, '_:') ) && !isset($this->resolvedPrefixedIri[$iri])) {
                                $prefix = substr($iri, 0, strpos($iri, ':'));
                                $localName = substr($iri, strpos($iri, ':') + 1);
                                if (!isset($this->prefixes[$prefix])) {
                                    throw new ParserException("Invalid RDF document. Cannot find prefix [{$prefix}:]");
                                }
                                $this->resolvedPrefixedIri[$iri] = "{$this->prefixes[$prefix]}$localName";
                            }
                            break;
                        case $this->rules['iriRef']:
                            //TODO:Implement logic to resolve relative IRI.
                            break;
                        case $this->rules['graph']:
                            $graph = trim($this->parser->sigil(1), '<>');
                            yield Parser::GRAPH => [
                                (isset($this->resolvedPrefixedIri[$graph])) ? $this->resolvedPrefixedIri[$graph] : $graph,
                            ];
                            break;
                        case $this->rules['wrappedGraph']:
                            $graph = trim($this->parser->sigil(0), '<>');
                            yield Parser::GRAPH => [
                                (isset($this->resolvedPrefixedIri[$graph])) ? $this->resolvedPrefixedIri[$graph] : $graph,
                            ];
                            break;
                        case $this->rules['subjectWithoutGraph']:
                        case $this->rules['subjectIri']:
                            $subject = trim($this->parser->sigil(0), '<>');
                            yield Parser::SUBJECT => [
                                (isset($this->resolvedPrefixedIri[$subject])) ? $this->resolvedPrefixedIri[$subject] : $subject,
                            ];
                            break;
                        case $this->rules['subjectBlank']:
                            yield Parser::SUBJECT => [
                                $this->parser->sigil(0),
                            ];
                            break;
                        case $this->rules['predicateIri']:
                            $predicate = trim($this->parser->sigil(0), '<>');
                            yield Parser::PREDICATE => [
                                (isset($this->resolvedPrefixedIri[$predicate])) ? $this->resolvedPrefixedIri[$predicate] : $predicate,
                            ];
                            break;
                        case $this->rules['predicateA']:
                            yield Parser::PREDICATE => [
                                    Namespaces::RDF_TYPE,
                            ];
                            break;
                        case $this->rules['objectBlank']:
                        case $this->rules['objectBlankNode']:
                        case $this->rules['objectBooleanFalse']:
                        case $this->rules['objectBooleanTrue']:
                        case $this->rules['objectInteger']:
                        case $this->rules['objectDecimal']:
                        case $this->rules['objectDouble']:
                        case $this->rules['objectString']:
                            yield Parser::OBJECT => [
                                trim($this->parser->sigil(0), '"\''),
                            ];
                            break;
                        case $this->rules['objectIri']:
                            $object = trim($this->parser->sigil(0), '<>');
                            yield Parser::OBJECT_IRI => [
                                (isset($this->resolvedPrefixedIri[$object])) ? $this->resolvedPrefixedIri[$object] : $object,
                            ];
                            break;
                        case $this->rules['objectStringIri']:
                            yield Parser::OBJECT_WITH_DATATYPE => [
                                trim($this->parser->sigil(0), '"\''),
                                (isset($this->resolvedPrefixedIri[$this->parser->sigil(2)])) ? $this->resolvedPrefixedIri[$this->parser->sigil(2)] : trim($this->parser->sigil(2), '<>'),
                            ];
                            break;
                        case $this->rules['objectStringLang']:
                            yield Parser::OBJECT_WITH_LANG_TAG => [
                                trim($this->parser->sigil(0), '"\''),
                                $this->parser->sigil(1),
                            ];
                            break;
                    }
                    break;
            }
            $this->parser->advance();
        } while (ParleParser::ACTION_ACCEPT != $this->parser->action);
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
    }

    protected function buildRules()
    {
        $this->parser->push('START', 'trigDoc');

        $this->parser->push('trigDoc', 'trigDoc directive');
        $this->parser->push('trigDoc', 'trigDoc block');
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
        $this->rules['graph'] = $this->parser->push('block', "'GRAPH' labelOrSubject wrappedGraph");
        $this->rules['subjectWithoutGraph'] = $this->parser->push('triplesOrGraph', "labelOrSubject predicateObjectList '.'");
        $this->rules['wrappedGraph'] = $this->parser->push('triplesOrGraph', 'labelOrSubject wrappedGraph');
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
        $this->rules['subjectIri'] = $this->parser->push('subject', 'iri');
        $this->rules['subjectBlank'] = $this->parser->push('subject', 'blank');
        $this->parser->push('labelOrSubject', 'iri');
        $this->parser->push('labelOrSubject', 'BlankNode');
        $this->parser->push('predicateObjectList', "predicateObjectList ';' predicateObjectList");
        $this->parser->push('predicateObjectList', "predicateObjectList ';'");
        $this->parser->push('predicateObjectList', 'verb objectList');
        $this->parser->push('objectList', "objectList ',' objectList");
        $this->parser->push('objectList', 'object');
        $this->rules['objectIri'] = $this->parser->push('object', 'iri');
        $this->parser->push('object', 'literal');
        $this->rules['objectBlank'] = $this->parser->push('object', 'blank');
        $this->rules['objectBlankNode'] = $this->parser->push('object', 'blankNodePropertyList');
        $this->parser->push('blankNodePropertyList', " '[' predicateObjectList ']' ");
        $this->parser->push('verb', 'predicate');
        $this->rules['predicateA'] = $this->parser->push('verb', "'a'");
        $this->rules['predicateIri'] = $this->parser->push('predicate', 'iri');
        $this->parser->push('literal', 'BooleanLiteral');
        $this->parser->push('literal', 'NumericalLiteral');
        $this->parser->push('literal', 'RDFLiteral');
        $this->parser->push('blank', 'BlankNode');
        $this->parser->push('blank', 'collection');
        $this->parser->push('collection', " '()' ");
        $this->parser->push('collection', "'(' objects ')'");
        $this->parser->push('objects', 'objects object');
        $this->parser->push('objects', 'object');
        $this->parser->push('BlankNode', 'BLANK_NODE_LABEL');
        $this->parser->push('BlankNode', 'ANON');
        $this->rules['objectBooleanTrue'] = $this->parser->push('BooleanLiteral', "'true'");
        $this->rules['objectBooleanFalse'] = $this->parser->push('BooleanLiteral', "'false'");
        $this->rules['objectInteger'] = $this->parser->push('NumericalLiteral', 'INTEGER');
        $this->rules['objectDecimal'] = $this->parser->push('NumericalLiteral', 'DECIMAL');
        $this->rules['objectDouble'] = $this->parser->push('NumericalLiteral', 'DOUBLE');
        $this->rules['objectString'] = $this->parser->push('RDFLiteral', 'String');
        $this->rules['objectStringLang'] = $this->parser->push('RDFLiteral', 'String LANGTAG');
        $this->rules['objectStringIri'] = $this->parser->push('RDFLiteral', "String '^^' iri");
        $this->parser->push('String', 'STRING_LITERAL_QUOTE');
        $this->parser->push('String', 'STRING_LITERAL_SINGLE_QUOTE');
        $this->parser->push('String', 'STRING_LITERAL_LONG_QUOTE');
        $this->parser->push('String', 'STRING_LITERAL_LONG_SINGLE_QUOTE');
        $this->rules['iriRef'] = $this->parser->push('iri', 'IRIREF');
        $this->rules['prefixedIri'] = $this->parser->push('iri', 'PrefixedName');
        $this->parser->push('PrefixedName', 'PNAME_LN');
        $this->parser->push('PrefixedName', 'PNAME_NS');
        $this->rules['prefixID'] = $this->parser->push('prefixID', "'@prefix' PNAME_NS IRIREF '.'");
        $this->rules['base'] = $this->parser->push('base', "'@base' IRIREF '.'");
        $this->rules['sparqlPrefix'] = $this->parser->push('sparqlPrefix', "'PREFIX' PNAME_NS IRIREF");
        $this->parser->push('sparqlBase', "'BASE' IRIREF");
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
        $this->lexer->push('true', $this->parser->tokenId("'true'"));
        $this->lexer->push('false', $this->parser->tokenId("'false'"));
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
        $this->lexer->push(self::IRIREF, $this->parser->tokenId('IRIREF'));
        $this->lexer->push(self::PNAME_LN, $this->parser->tokenId('PNAME_LN'));
        $this->lexer->push(self::PNAME_NS, $this->parser->tokenId('PNAME_NS'));
        $this->lexer->push(self::STRING_LITERAL_LONG_QUOTE, $this->parser->tokenId('STRING_LITERAL_LONG_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_LONG_SINGLE_QUOTE, $this->parser->tokenId('STRING_LITERAL_LONG_SINGLE_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_QUOTE, $this->parser->tokenId('STRING_LITERAL_QUOTE'));
        $this->lexer->push(self::STRING_LITERAL_SINGLE_QUOTE, $this->parser->tokenId('STRING_LITERAL_SINGLE_QUOTE'));
        $this->lexer->push(self::BLANK_NODE_LABEL, $this->parser->tokenId('BLANK_NODE_LABEL'));
        $this->lexer->push(self::LANGTAG, $this->parser->tokenId('LANGTAG'));
        $this->lexer->push(self::HEX, $this->parser->tokenId('HEX'));
        $this->lexer->push(self::PERCENT, $this->parser->tokenId('PERCENT'));
        $this->lexer->push(self::UCHAR, $this->parser->tokenId('UCHAR'));
        $this->lexer->push(self::ECHAR, $this->parser->tokenId('ECHAR'));
        $this->lexer->push(self::NIL, $this->parser->tokenId('NIL'));
        $this->lexer->push(self::ANON, $this->parser->tokenId('ANON'));
        $this->lexer->push('[\s\n\t]+', Token::SKIP);
        $this->lexer->build();
    }
}
