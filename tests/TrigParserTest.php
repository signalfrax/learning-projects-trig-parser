<?php


use PHPUnit\Framework\TestCase;
use RDF\Namespaces;
use RDF\Parser\Parser;
use RDF\Parser\TrigParser;
use Tests\LoadFixture;

/**
 * Class TrigParserTest
 */
class TrigParserTest extends TestCase
{
    use LoadFixture;

    public function test_alternating_bnode_graphs()
    {
        $content = $this->loadFixture("w3c-test-suite/alternating_bnode_graphs.trig");
        $p = new TrigParser();

        $actual = [];
        foreach($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/c']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/d']],
            [Parser::GRAPH => ['_:G']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/e']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/f']],
            [Parser::GRAPH => ['_:G']],
        ], $actual);

    }

    public function test_alternating_iri_graphs()
    {
        $content = $this->loadFixture("w3c-test-suite/alternating_iri_graphs.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/c']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/d']],
            [Parser::GRAPH => ['http://example/G']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/e']],
            [Parser::SUBJECT => ['http://example/a']],
            [Parser::PREDICATE => ['http://example/b']],
            [Parser::OBJECT_IRI => ['http://example/f']],
            [Parser::GRAPH => ['http://example/G']],
        ], $actual);
    }

    public function test_anonymous_blank_node_graph()
    {
        $content = $this->loadFixture("w3c-test-suite/anonymous_blank_node_graph.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['[]']],
        ], $actual);

    }

    public function test_anonymous_blank_node_object()
    {
        $content = $this->loadFixture("w3c-test-suite/anonymous_blank_node_object.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['[]']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['[]']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_anonymous_blank_node_subject()
    {
        $content = $this->loadFixture("w3c-test-suite/anonymous_blank_node_subject.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_bareword_a_predicate()
    {
        $content = $this->loadFixture("w3c-test-suite/bareword_a_predicate.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => [Namespaces::RDF_TYPE]],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => [Namespaces::RDF_TYPE]],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_bareword_decimal()
    {
        $content = $this->loadFixture("w3c-test-suite/bareword_decimal.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1.0']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1.0']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_bareword_double()
    {
        $content = $this->loadFixture("w3c-test-suite/bareword_double.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1E0']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1E0']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_bareword_integer()
    {
        $content = $this->loadFixture("w3c-test-suite/bareword_integer.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_blankNodePropertyList_as_object()
    {

    }

    public function test_blankNodePropertyList_as_subject()
    {

    }

    public function test_blankNodePropertyList_containing_collection()
    {

    }

    public function test_blankNodePropertyList_with_multiple_triples()
    {

    }

    public function test_collection_object()
    {

    }

    public function test_collection_subject()
    {

    }

    public function test_comment_following_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/comment_following_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_comment_following_PNAME_NS()
    {
        $content = $this->loadFixture("w3c-test-suite/comment_following_PNAME_NS.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);

    }

    public function test_default_namespace_IRI()
    {
        $content = $this->loadFixture("w3c-test-suite/default_namespace_IRI.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_double_lower_case_e()
    {
        $content = $this->loadFixture("w3c-test-suite/double_lower_case_e.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1e0']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['1e0']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_empty_collection()
    {

    }

    public function test_first()
    {

    }

    public function test_HYPHEN_MINUS_in_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/HYPHEN_MINUS_in_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s-']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s-']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_IRI_subject()
    {
        $content = $this->loadFixture("w3c-test-suite/IRI_subject.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_IRI_with_all_punctuation()
    {
        $content = $this->loadFixture("w3c-test-suite/IRI_with_all_punctuation.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['scheme:!$%25&amp;\'()*+,-./0123456789:/@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz~?#']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['scheme:!$%25&amp;\'()*+,-./0123456789:/@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz~?#']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_IRI_with_eight_digit_numeric_escape()
    {
        $content = $this->loadFixture("w3c-test-suite/IRI_with_eight_digit_numeric_escape.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/\U00000073']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/\U00000073']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_IRI_with_four_digit_numeric_escape()
    {
        $content = $this->loadFixture("w3c-test-suite/IRI_with_four_digit_numeric_escape.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/\u0073']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/\u0073']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_IRIREF_datatype()
    {
        $content = $this->loadFixture("w3c-test-suite/IRIREF_datatype.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', 'http://www.w3.org/2001/XMLSchema#integer']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', 'http://www.w3.org/2001/XMLSchema#integer']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_labeled_blank_node_graph()
    {

    }

    public function test_labeled_blank_node_object()
    {

    }

    public function test_labeled_blank_node_subject()
    {

    }

    public function test_labeled_blank_node_with_leading_digit()
    {

    }

    public function test_labeled_blank_node_with_leading_underscore()
    {

    }

    public function test_labeled_blank_node_with_non_leading_extras()
    {

    }

    public function test_labeled_blank_node_with_PN_CHARS_BASE_character_boundaries()
    {

    }

    public function test_langtagged_LONG()
    {
        $content = $this->loadFixture("w3c-test-suite/langtagged_LONG.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_langtagged_LONG_with_subtag()
    {
        $content = $this->loadFixture("w3c-test-suite/langtagged_LONG_with_subtag.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/ex#']],
            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_LANG_TAG => ['Cheers', '@en-UK']],
            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_LANG_TAG => ['Cheers', '@en-UK']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_langtagged_non_LONG()
    {
        $content = $this->loadFixture("w3c-test-suite/langtagged_non_LONG.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_lantag_with_subtag()
    {
        $content = $this->loadFixture("w3c-test-suite/lantag_with_subtag.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en-us']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', '@en-us']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_last()
    {

    }

    public function test_LITERAL1()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL1.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_LITERAL1_all_controls()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL1_all_controls.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_LITERAL1_all_punctuation()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL1_all_punctuation.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => [' !"#$%&():;<=>?@[]^_`{|}~']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => [' !"#$%&():;<=>?@[]^_`{|}~']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_LITERAL1_ascii_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL1_ascii_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_LITERAL1_with_UTF8_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL1_with_UTF8_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_LITERAL2()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL2.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_LITERAL2_ascii_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL2_ascii_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_LITERAL2_with_UTF8_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL2_with_UTF8_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_literal_false()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_false.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['false']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['false']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG1()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG1.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_LITERAL_LONG1_ascii_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG1_ascii_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_LITERAL_LONG1_with_1_squote()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG1_with_1_squote.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["x'y"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["x'y"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG1_with_2_squotes()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG1_with_2_squotes.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["x''y"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["x''y"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG1_with_UTF8_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG1_with_UTF8_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_LITERAL_LONG2()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_LITERAL_LONG2_ascii_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2_ascii_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_LITERAL_LONG2_with_1_squote()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2_with_1_squote.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x"y']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x"y']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG2_with_2_squotes()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2_with_2_squotes.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x""y']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['x""y']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG2_with_REVERSE_SOLIDUS()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2_with_REVERSE_SOLIDUS.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/ns#']],
            [Parser::SUBJECT => ['http://example.org/ns#s']],
            [Parser::PREDICATE => ['http://example.org/ns#p1']],
            [Parser::OBJECT => ['test-\\\\']],
            [Parser::SUBJECT => ['http://example.org/ns#s']],
            [Parser::PREDICATE => ['http://example.org/ns#p1']],
            [Parser::OBJECT => ['test-\\\\']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_LITERAL_LONG2_with_UTF8_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/LITERAL_LONG2_with_UTF8_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_literal_true()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_true.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['true']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['true']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_BACKSPACE()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_BACKSPACE.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x08"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x08"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_CARRIAGE_RETURN()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_CARRIAGE_RETURN.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x0d"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x0d"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_CHARACTER_TABULATION()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_CHARACTER_TABULATION.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\t"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\t"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_escaped_BACKSPACE()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_escaped_BACKSPACE.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\b']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\b']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_escaped_CARRIAGE_RETURN()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_escaped_CARRIAGE_RETURN.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\r']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\r']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_escaped_CHARACTER_TABULATION()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_escaped_CHARACTER_TABULATION.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\t']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\t']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_escaped_FORM_FEED()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_escaped_FORM_FEED.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\f']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\f']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_escaped_LINE_FEED()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_escaped_LINE_FEED.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\n']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ['\\n']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_FORM_FEED()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_FORM_FEED.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x0c"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\x0c"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_LINE_FEED()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_LINE_FEED.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\n"]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT => ["\n"]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }


}