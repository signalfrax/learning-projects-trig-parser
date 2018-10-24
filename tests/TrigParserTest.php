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
        $this->markTestSkipped("Add support for blank nodes.");
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
        $this->markTestSkipped("Add support for blank nodes");
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
        $this->markTestSkipped("Add support for blank nodes.");
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
            [Parser::OBJECT_WITH_DATATYPE => ['1.0', Namespaces::XSD_DECIMAL]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1.0', Namespaces::XSD_DECIMAL]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['1E0', Namespaces::XSD_DOUBLE]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1E0', Namespaces::XSD_DOUBLE]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_INTEGER]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_INTEGER]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_blankNodePropertyList_as_object()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_blankNodePropertyList_as_subject()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_blankNodePropertyList_containing_collection()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_blankNodePropertyList_with_multiple_triples()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_collection_object()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_collection_subject()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
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
            [Parser::OBJECT_WITH_DATATYPE => ['1e0', Namespaces::XSD_DOUBLE]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1e0', Namespaces::XSD_DOUBLE]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_empty_collection()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_first()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
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

    public function test_IRI_resolution()
    {
        $this->markTestIncomplete("Implement");
        $content = $this->loadFixture("w3c-test-suite/IRI-resolution.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_IRI_resolution_01()
    {
        $this->markTestIncomplete("Implement");
        $content = $this->loadFixture("w3c-test-suite/IRI-resolution-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_IRI_resolution_02()
    {
        $this->markTestIncomplete("Implement");
        $content = $this->loadFixture("w3c-test-suite/IRI-resolution-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_IRI_resolution_07()
    {
        $this->markTestIncomplete("Implement");
        $content = $this->loadFixture("w3c-test-suite/IRI-resolution-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_IRI_resolution_08()
    {
        $this->markTestIncomplete("Implement");
        $content = $this->loadFixture("w3c-test-suite/IRI-resolution-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
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
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
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
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
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
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_object()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_subject()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_with_leading_digit()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_with_leading_underscore()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_with_non_leading_extras()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_labeled_blank_node_with_PN_CHARS_BASE_character_boundaries()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
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
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en']],
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
            [Parser::OBJECT_WITH_LANG_TAG => ['Cheers', Namespaces::RDF_LANG_STRING, '@en-UK']],
            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_LANG_TAG => ['Cheers', Namespaces::RDF_LANG_STRING, '@en-UK']],
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
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en']],
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
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en-us']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['chat', Namespaces::RDF_LANG_STRING, '@en-us']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_last()
    {
        $this->markTestIncomplete("Add proper support for blanks and collection");
    }

    public function test_latin1_iri()
    {
        $content = $this->loadFixture("w3c-test-suite/latin1_iri.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['ÀÁÂÃÄÅÆÇÈÉÊË', 'http://áâãäåæçèé.example/']],
            [Parser::SUBJECT => ['http://áâãäåæçèé.example/ÐÑÒÓÔÕÖØÙÚ']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://áâãäåæçèé.example/ÐÑÒÓÔÕÖØÙÚ']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
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
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => [' !"#$%&():;<=>?@[]^_`{|}~', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => [' !"#$%&():;<=>?@[]^_`{|}~', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['false', Namespaces::XSD_BOOLEAN]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['false', Namespaces::XSD_BOOLEAN]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["x'y", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["x'y", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["x''y", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["x''y", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['x"y', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x"y', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['x""y', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['x""y', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['test-\\', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://example.org/ns#s']],
            [Parser::PREDICATE => ['http://example.org/ns#p1']],
            [Parser::OBJECT_WITH_DATATYPE => ['test-\\', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['߿ࠀ࿿က쿿퀀퟿�𐀀𿿽񀀀󿿽􀀀􏿽', Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ['true', Namespaces::XSD_BOOLEAN]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['true', Namespaces::XSD_BOOLEAN]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\x08", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\x08", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\x0d", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\x0d", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\t", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\t", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\x8", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\x8", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\r", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\r", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\t", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\t", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\f", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\f", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\n", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\n", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\x0c", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\x0c", Namespaces::XSD_STRING]],
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
            [Parser::OBJECT_WITH_DATATYPE => ["\n", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\n", Namespaces::XSD_STRING]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_numeric_escape4()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_numeric_escape4.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\u{006F}", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\u{006F}", Namespaces::XSD_STRING]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_numeric_escape8()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_numeric_escape8.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\u{0000006F}", Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["\u{0000006F}", Namespaces::XSD_STRING]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_literal_with_REVERSE_SOLIDUS()
    {
        $content = $this->loadFixture("w3c-test-suite/literal_with_REVERSE_SOLIDUS.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['\\', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['\\', Namespaces::XSD_STRING]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_assigned_nfc_bmp_PN_CHARS_BASE_character_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_assigned_nfc_bmp_PN_CHARS_BASE_character_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_assigned_nfc_PN_CHARS_BASE_character_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_assigned_nfc_PN_CHARS_BASE_character_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localname_with_COLON()
    {
        $content = $this->loadFixture("w3c-test-suite/localname_with_COLON.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s:']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s:']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_leading_digit()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_leading_digit.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/0']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/0']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_leading_underscore()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_leading_underscore.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/_']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/_']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_nfc_PN_CHARS_BASE_character_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_nfc_PN_CHARS_BASE_character_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_non_leading_extras()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_non_leading_extras.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/a·̀ͯ‿.⁀']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/a·̀ͯ‿.⁀']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_localName_with_PN_CHARS_BASE_character_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/localName_with_PN_CHARS_BASE_character_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_negative_numeric()
    {
        $content = $this->loadFixture("w3c-test-suite/negative_numeric.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['-1', Namespaces::XSD_INTEGER]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['-1', Namespaces::XSD_INTEGER]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_nested_blankNodePropertyLists()
    {
        $this->markTestIncomplete("Implement proper support for blanks and collection");
    }

    public function test_nested_collection()
    {
        $this->markTestIncomplete("Implement proper support for blanks and collection");
    }

    public function test_number_sign_following_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/number_sign_following_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o#numbersign']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_number_sign_following_PNAME_NS()
    {
        $content = $this->loadFixture("w3c-test-suite/number_sign_following_PNAME_NS.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/#numbersign']],
            [Parser::SUBJECT => ['http://a.example/s']],
        ], $actual);
    }

    public function test_numeric_with_leading_0()
    {
        $content = $this->loadFixture("w3c-test-suite/numeric_with_leading_0.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['01', Namespaces::XSD_INTEGER]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['01', Namespaces::XSD_INTEGER]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_objectList_with_two_objects()
    {
        $content = $this->loadFixture("w3c-test-suite/objectList_with_two_objects.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_old_style_prefix()
    {
        $content = $this->loadFixture("w3c-test-suite/old_style_prefix.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_percent_escaped_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/percent_escaped_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/%25']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/%25']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_positive_numeric()
    {
        $content = $this->loadFixture("w3c-test-suite/positive_numeric.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['+1', Namespaces::XSD_INTEGER]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['+1', Namespaces::XSD_INTEGER]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_predicateObjectList_with_two_objectLists()
    {
        $content = $this->loadFixture("w3c-test-suite/predicateObjectList_with_two_objectLists.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::PREDICATE => ['http://a.example/p2']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::PREDICATE => ['http://a.example/p2']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefix_only_IRI()
    {
        $content = $this->loadFixture("w3c-test-suite/prefix_only_IRI.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/s']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefix_reassigned_and_used()
    {
        $content = $this->loadFixture("w3c-test-suite/prefix_reassigned_and_used.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::PREFIX => ['p', 'http://b.example/']],
            [Parser::SUBJECT => ['http://b.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://b.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefix_with_non_leading_extras()
    {
        $content = $this->loadFixture("w3c-test-suite/prefix_with_non_leading_extras.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['a·̀ͯ‿.⁀', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefix_with_PN_CHARS_BASE_character_boundaries()
    {
        $content = $this->loadFixture("w3c-test-suite/prefix_with_PN_CHARS_BASE_character_boundaries.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['AZazÀÖØöø˿Ͱͽ΄῾‌‍⁰↉Ⰰ⿕、ퟻ﨎ﷇﷰ￯𐀀𪘀', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefixed_IRI_object()
    {
        $content = $this->loadFixture("w3c-test-suite/prefixed_IRI_object.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefixed_IRI_predicate()
    {
        $content = $this->loadFixture("w3c-test-suite/prefixed_IRI_predicate.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p', 'http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_prefixed_name_datatype()
    {
        $content = $this->loadFixture("w3c-test-suite/prefixed_name_datatype.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['xsd', 'http://www.w3.org/2001/XMLSchema#']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_INTEGER]],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_INTEGER]],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_repeated_semis_at_end()
    {
        $content = $this->loadFixture("w3c-test-suite/repeated_semis_at_end.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::PREDICATE => ['http://a.example/p2']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::PREDICATE => ['http://a.example/p2']],
            [Parser::OBJECT_IRI => ['http://a.example/o2']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_repeated_semis_not_at_end()
    {
        $content = $this->loadFixture("w3c-test-suite/repeated_semis_not_at_end.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::SUBJECT => ['http://a.example/s']],
            [Parser::PREDICATE => ['http://a.example/p1']],
            [Parser::OBJECT_IRI => ['http://a.example/o1']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_reserved_escaped_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/reserved_escaped_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['p','http://a.example/']],
            [Parser::SUBJECT => ['http://a.example/_~.-!$&\'()*+,;=/?#@%00']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::SUBJECT => ['http://a.example/_~.-!$&\'()*+,;=/?#@%00']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_sole_blankNodePropertyList()
    {
        $this->markTestIncomplete("Add proper support for blanks and collections.");

    }

    public function test_SPARQL_style_prefix()
    {
        $this->markTestIncomplete("Need add proper support for sparql style prefixes.");
    }

    /**
     * @expectedException  \RDF\Parser\ParserException
     */
    public function test_trig_bnodeplist_graph_bad_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-bnodeplist-graph-bad-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_collection_graph_bad_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-collection-graph-bad-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_collection_graph_bad_02()
    {
        $this->markTestSkipped("Add support to decode unicode escape characters.");

        $content = $this->loadFixture("w3c-test-suite/trig-collection-graph-bad-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_eval_bad_01()
    {
        $this->markTestSkipped("Add support to decode unicode escape characters.");
        $content = $this->loadFixture("w3c-test-suite/trig-eval-bad-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_eval_bad_02()
    {
        $this->markTestSkipped("Add support to decode unicode escape characters.");
        $content = $this->loadFixture("w3c-test-suite/trig-eval-bad-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_eval_bad_03()
    {
        $this->markTestSkipped("Add support to decode unicode escape characters.");
        $content = $this->loadFixture("w3c-test-suite/trig-eval-bad-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_eval_bad_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-eval-bad-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_eval_struct_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-eval-struct-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_eval_struct_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-eval-struct-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_10()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_graph_bad_11()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-graph-bad-11.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_kw_graph_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g1']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g2']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g3']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g4']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g5']],
        ], $actual);
    }

    public function test_trig_kw_graph_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['_:a']],
        ], $actual);
    }

    public function test_trig_kw_graph_07()
    {
        $this->markTestSkipped("Add support for blank nodes");
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['[]']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['[]']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['[]']],
        ], $actual);
    }

    public function test_trig_kw_graph_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_kw_graph_10()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-kw-graph-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::GRAPH => ['http://example/g']],
        ], $actual);
    }

    public function test_trig_subm_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', '#']],
            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['#x']],
            [Parser::OBJECT_IRI => ['#y']],
            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['#x']],
            [Parser::OBJECT_IRI => ['#y']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/base1#']],
            [Parser::PREFIX => ['a', 'http://example.org/base2#']],
            [Parser::PREFIX => ['b', 'http://example.org/base3#']],
            [Parser::SUBJECT => ['http://example.org/base1#a']],
            [Parser::PREDICATE => ['http://example.org/base1#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base1#c']],
            [Parser::SUBJECT => ['http://example.org/base2#a']],
            [Parser::PREDICATE => ['http://example.org/base2#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base2#c']],
            [Parser::SUBJECT => ['http://example.org/base1#a']],
            [Parser::PREDICATE => ['http://example.org/base2#a']],
            [Parser::OBJECT_IRI => ['http://example.org/base3#a']],
            [Parser::SUBJECT => ['http://example.org/base1#a']],
            [Parser::PREDICATE => ['http://example.org/base1#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base1#c']],
            [Parser::SUBJECT => ['http://example.org/base2#a']],
            [Parser::PREDICATE => ['http://example.org/base2#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base2#c']],
            [Parser::SUBJECT => ['http://example.org/base1#a']],
            [Parser::PREDICATE => ['http://example.org/base2#a']],
            [Parser::OBJECT_IRI => ['http://example.org/base3#a']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/base#']],
            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://example.org/base#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base#c']],
            [Parser::OBJECT_IRI => ['http://example.org/base#d']],
            [Parser::OBJECT_IRI => ['http://example.org/base#e']],
            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://example.org/base#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base#c']],
            [Parser::OBJECT_IRI => ['http://example.org/base#d']],
            [Parser::OBJECT_IRI => ['http://example.org/base#e']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/base#']],
            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://example.org/base#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base#c']],
            [Parser::PREDICATE => ['http://example.org/base#d']],
            [Parser::OBJECT_IRI => ['http://example.org/base#e']],
            [Parser::PREDICATE => ['http://example.org/base#f']],
            [Parser::OBJECT_IRI => ['http://example.org/base#g']],
            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://example.org/base#b']],
            [Parser::OBJECT_IRI => ['http://example.org/base#c']],
            [Parser::PREDICATE => ['http://example.org/base#d']],
            [Parser::OBJECT_IRI => ['http://example.org/base#e']],
            [Parser::PREDICATE => ['http://example.org/base#f']],
            [Parser::OBJECT_IRI => ['http://example.org/base#g']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_05()
    {
        $this->markTestSkipped("Add support for blank node.");
        $content = $this->loadFixture("w3c-test-suite/trig-subm-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/base#']],

            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://example.org/base#a']],
            [Parser::OBJECT_IRI => ['http://example.org/base#b']],

            [Parser::SUBJECT => ['http://example.org/base#c']],
            [Parser::PREDICATE => ['http://example.org/base#d']],
            [Parser::OBJECT => ['[]']],

            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://example.org/base#a']],
            [Parser::OBJECT_IRI => ['http://example.org/base#b']],
            [Parser::SUBJECT => ['http://example.org/base#c']],
            [Parser::PREDICATE => ['http://example.org/base#d']],
            [Parser::OBJECT => ['[]']],
            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_06()
    {
        $this->markTestIncomplete("Add proper support for blanks and collections.");
    }

    public function test_trig_subm_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/base#']],

            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']],
            [Parser::OBJECT_IRI => ['http://example.org/base#b']],

            [Parser::SUBJECT => ['http://example.org/base#a']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']],
            [Parser::OBJECT_IRI => ['http://example.org/base#b']],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_08()
    {
        $this->markTestIncomplete("Add proper support for blanks and collections.");
    }

    public function test_trig_subm_09()
    {
        $this->markTestIncomplete("Add proper support for blanks and collections.");
    }

    public function test_trig_subm_10()
    {
        $this->markTestIncomplete("Investigate more on 'datatyped literals using an OWL cardinality constraint'");
    }

    public function test_trig_subm_11()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-11.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example.org/res1']],
            [Parser::PREDICATE => ['http://example.org/prop1']],
            [Parser::OBJECT_WITH_DATATYPE => ['000000', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res2']],
            [Parser::PREDICATE => ['http://example.org/prop2']],
            [Parser::OBJECT_WITH_DATATYPE => ['0', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res3']],
            [Parser::PREDICATE => ['http://example.org/prop3']],
            [Parser::OBJECT_WITH_DATATYPE => ['000001', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res4']],
            [Parser::PREDICATE => ['http://example.org/prop4']],
            [Parser::OBJECT_WITH_DATATYPE => ['2', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res5']],
            [Parser::PREDICATE => ['http://example.org/prop5']],
            [Parser::OBJECT_WITH_DATATYPE => ['4', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res1']],
            [Parser::PREDICATE => ['http://example.org/prop1']],
            [Parser::OBJECT_WITH_DATATYPE => ['000000', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res2']],
            [Parser::PREDICATE => ['http://example.org/prop2']],
            [Parser::OBJECT_WITH_DATATYPE => ['0', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res3']],
            [Parser::PREDICATE => ['http://example.org/prop3']],
            [Parser::OBJECT_WITH_DATATYPE => ['000001', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res4']],
            [Parser::PREDICATE => ['http://example.org/prop4']],
            [Parser::OBJECT_WITH_DATATYPE => ['2', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org/res5']],
            [Parser::PREDICATE => ['http://example.org/prop5']],
            [Parser::OBJECT_WITH_DATATYPE => ['4', Namespaces::XSD_INTEGER]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_12()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-12.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['ex1', 'http://example.org/ex1#']],
            [Parser::PREFIX => ['ex-2', 'http://example.org/ex2#']],
            [Parser::PREFIX => ['ex3_', 'http://example.org/ex3#']],
            [Parser::PREFIX => ['ex4-', 'http://example.org/ex4#']],

            [Parser::SUBJECT => ['http://example.org/ex1#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex1#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['a', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex2#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex2#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['b', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex3#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex3#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['c', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex4#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex4#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['d', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex1#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex1#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['a', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex2#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex2#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['b', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex3#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex3#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['c', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex4#foo-bar']],
            [Parser::PREDICATE => ['http://example.org/ex4#foo_bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['d', Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_13()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-13.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#']],
            [Parser::PREFIX => ['ex', 'http://example.org/ex#']],
            [Parser::PREFIX => ['', 'http://example.org/myprop#']],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#_1']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#_2']],
            [Parser::OBJECT_WITH_DATATYPE => ['2', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://example.org/myprop#_abc']],
            [Parser::OBJECT_WITH_DATATYPE => ['def', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://example.org/myprop#_345']],
            [Parser::OBJECT_WITH_DATATYPE => ['678', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#_1']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#_2']],
            [Parser::OBJECT_WITH_DATATYPE => ['2', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://example.org/myprop#_abc']],
            [Parser::OBJECT_WITH_DATATYPE => ['def', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#foo']],
            [Parser::PREDICATE => ['http://example.org/myprop#_345']],
            [Parser::OBJECT_WITH_DATATYPE => ['678', Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_14()
    {
        $this->markTestSkipped('Add support for blank nodes');
        $content = $this->loadFixture("w3c-test-suite/trig-subm-14.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/ron']],

            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://example.org/ron']],
            [Parser::OBJECT => ['[]']],

            [Parser::SUBJECT => ['http://example.org/ron']],
            [Parser::PREDICATE => ['http://example.org/ron']],
            [Parser::OBJECT_IRI => ['http://example.org/ron']],

            [Parser::SUBJECT => ['[]']],
            [Parser::PREDICATE => ['http://example.org/ron']],
            [Parser::OBJECT => ['[]']],

            [Parser::SUBJECT => ['http://example.org/ron']],
            [Parser::PREDICATE => ['http://example.org/ron']],
            [Parser::OBJECT_IRI => ['http://example.org/ron']],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_15()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-15.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/ex#']],

            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["a long\n\tliteral\nwith\nnewlines", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["a long\n\tliteral\nwith\nnewlines", Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_16()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-16.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/foo#']],

            [Parser::SUBJECT => ['http://example.org/foo#a']],
            [Parser::PREDICATE => ['http://example.org/foo#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["\nthis \ris a \u{00012451}long\t\nliteral\u{ABCD}\n", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/foo#d']],
            [Parser::PREDICATE => ['http://example.org/foo#e']],
            [Parser::OBJECT_WITH_DATATYPE => ["\tThis \u{ABCD}is\r \u{00012451}another\n\none\n", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/foo#a']],
            [Parser::PREDICATE => ['http://example.org/foo#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["\nthis \ris a \u{00012451}long\t\nliteral\u{ABCD}\n", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/foo#d']],
            [Parser::PREDICATE => ['http://example.org/foo#e']],
            [Parser::OBJECT_WITH_DATATYPE => ["\tThis \u{ABCD}is\r \u{00012451}another\n\none\n", Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_17()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-17.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/#']],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0", Namespaces::XSD_DECIMAL]],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0", Namespaces::XSD_DECIMAL]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_18()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-18.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/#']],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/#c']],
            [Parser::PREDICATE => ['http://example.org/#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["", Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/#c']],
            [Parser::PREDICATE => ['http://example.org/#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["", Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_19()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-19.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org#']],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0", Namespaces::XSD_DECIMAL]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["1", Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org#e']],
            [Parser::PREDICATE => ['http://example.org#f']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0e0", Namespaces::XSD_DOUBLE]],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0", Namespaces::XSD_DECIMAL]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["1", Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org#e']],
            [Parser::PREDICATE => ['http://example.org#f']],
            [Parser::OBJECT_WITH_DATATYPE => ["1.0e0", Namespaces::XSD_DOUBLE]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_20()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-20.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org#']],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1.0", Namespaces::XSD_DECIMAL]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1", Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org#e']],
            [Parser::PREDICATE => ['http://example.org#f']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1.0e0", Namespaces::XSD_DOUBLE]],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1.0", Namespaces::XSD_DECIMAL]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1", Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example.org#e']],
            [Parser::PREDICATE => ['http://example.org#f']],
            [Parser::OBJECT_WITH_DATATYPE => ["-1.0e0", Namespaces::XSD_DOUBLE]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_21()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-21.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/ex#']],

            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_DATATYPE => ['John said: \ "Hello World!"', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_DATATYPE => ['John said: \ "Hello World!"', Namespaces::XSD_STRING]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_22()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-22.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org#']],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ['true', Namespaces::XSD_BOOLEAN]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ['false', Namespaces::XSD_BOOLEAN]],

            [Parser::SUBJECT => ['http://example.org#a']],
            [Parser::PREDICATE => ['http://example.org#b']],
            [Parser::OBJECT_WITH_DATATYPE => ['true', Namespaces::XSD_BOOLEAN]],

            [Parser::SUBJECT => ['http://example.org#c']],
            [Parser::PREDICATE => ['http://example.org#d']],
            [Parser::OBJECT_WITH_DATATYPE => ['false', Namespaces::XSD_BOOLEAN]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_23()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-23.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/#']],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_IRI => ['http://example.org/#c']],

            [Parser::SUBJECT => ['http://example.org/#d']],
            [Parser::PREDICATE => ['http://example.org/#e']],
            [Parser::OBJECT_IRI => ['http://example.org/#f']],

            [Parser::SUBJECT => ['http://example.org/#g']],
            [Parser::PREDICATE => ['http://example.org/#h']],
            [Parser::OBJECT_IRI => ['http://example.org/#i']],
            [Parser::OBJECT_IRI => ['http://example.org/#j']],

            [Parser::SUBJECT => ['http://example.org/#k']],
            [Parser::PREDICATE => ['http://example.org/#l']],
            [Parser::OBJECT_IRI => ['http://example.org/#m']],
            [Parser::PREDICATE => ['http://example.org/#n']],
            [Parser::OBJECT_IRI => ['http://example.org/#o']],
            [Parser::PREDICATE => ['http://example.org/#p']],
            [Parser::OBJECT_IRI => ['http://example.org/#q']],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_IRI => ['http://example.org/#c']],

            [Parser::SUBJECT => ['http://example.org/#d']],
            [Parser::PREDICATE => ['http://example.org/#e']],
            [Parser::OBJECT_IRI => ['http://example.org/#f']],

            [Parser::SUBJECT => ['http://example.org/#g']],
            [Parser::PREDICATE => ['http://example.org/#h']],
            [Parser::OBJECT_IRI => ['http://example.org/#i']],
            [Parser::OBJECT_IRI => ['http://example.org/#j']],

            [Parser::SUBJECT => ['http://example.org/#k']],
            [Parser::PREDICATE => ['http://example.org/#l']],
            [Parser::OBJECT_IRI => ['http://example.org/#m']],
            [Parser::PREDICATE => ['http://example.org/#n']],
            [Parser::OBJECT_IRI => ['http://example.org/#o']],
            [Parser::PREDICATE => ['http://example.org/#p']],
            [Parser::OBJECT_IRI => ['http://example.org/#q']],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);
    }

    public function test_trig_subm_24()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-24.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example.org/#']],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_IRI => ["http://example.org/#c"]],

            [Parser::SUBJECT => ['http://example.org/#a']],
            [Parser::PREDICATE => ['http://example.org/#b']],
            [Parser::OBJECT_IRI => ["http://example.org/#c"]],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_25()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-25.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['foo', 'http://example.org/foo#']],
            [Parser::PREFIX => ['foo', 'http://example.org/bar#']],

            [Parser::SUBJECT => ['http://example.org/bar#blah']],
            [Parser::PREDICATE => ['http://example.org/bar#blah']],
            [Parser::OBJECT_IRI => ['http://example.org/bar#blah']],

            [Parser::SUBJECT => ['http://example.org/bar#blah']],
            [Parser::PREDICATE => ['http://example.org/bar#blah']],
            [Parser::OBJECT_IRI => ['http://example.org/bar#blah']],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    public function test_trig_subm_26()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-subm-26.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.345', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['1', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['1.0', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['1.', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['1.000000000', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.3', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.234000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.2340000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.23400000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.234000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.2340000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.23400000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.234000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.2340000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.23400000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.234000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.2340000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.23400000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.234000000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.2340000000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['2.23400000000000000000005', 'http://www.w3.org/2001/XMLSchema#decimal']],

            [Parser::SUBJECT => ['http://example.org/foo']],
            [Parser::PREDICATE => ['http://example.org/bar']],
            [Parser::OBJECT_WITH_DATATYPE => ['1.2345678901234567890123457890', 'http://www.w3.org/2001/XMLSchema#decimal']],

        ], $actual);

    }

    public function test_trig_subm_27()
    {
        $this->markTestSkipped("Add proper support for base iri.");

        $content = $this->loadFixture("w3c-test-suite/trig-subm-27.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['a1']],
            [Parser::PREDICATE => ['b1']],
            [Parser::OBJECT_IRI => ['c1']],

            [Parser::SUBJECT => ['http://example.org/bar#blah']],
            [Parser::PREDICATE => ['http://example.org/bar#blah']],
            [Parser::OBJECT_IRI => ['http://example.org/bar#blah']],

            [Parser::GRAPH => ['http://example/graph']],
        ], $actual);

    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_base_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-base-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_base_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-base-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_base_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-base-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_base_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-base-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_base_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-base-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_blank_label_dot_end()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-blank-label-dot-end.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_esc_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-esc-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_esc_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-esc-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_esc_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-esc-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_esc_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-esc-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_kw_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-kw-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_kw_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-kw-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_kw_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-kw-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_kw_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-kw-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_kw_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-kw-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_lang_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-lang-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_list_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-list-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_list_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-list-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_list_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-list-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_list_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-list-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_LITERAL2_with_langtag_and_datatype()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-LITERAL2_with_langtag_and_datatype.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_ln_dash_start()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-ln-dash-start.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_ln_escape()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-ln-escape.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_ln_escape_start()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-ln-escape-start.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_missing_ns_dot_end()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-missing-ns-dot-end.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_missing_ns_dot_start()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-missing-ns-dot-start.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_10()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_11()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-11.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_12()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-12.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_n3_extras_13()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-n3-extras-13.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_ns_dot_end()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-ns-dot-end.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_ns_dot_start()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-ns-dot-start.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_num_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-num-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_num_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-num-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_num_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-num-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_num_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-num-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_num_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-num-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_number_dot_in_anon()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-number-dot-in-anon.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_pname_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-pname-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_pname_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-pname-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_pname_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-pname-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_prefix_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-prefix-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_string_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-string-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_07()
    {
        $this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_09()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_10()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_12()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-12.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_13()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-13.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_14()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-14.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_15()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-15.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_16()
    {
        //$this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-16.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_struct_17()
    {
        $this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-struct-17.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_uri_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-uri-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_uri_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-uri-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_uri_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-uri-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_uri_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-uri-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bad_uri_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bad-uri-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_base_01()
    {
        $this->markTestIncomplete("Add support for base.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-base-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::BASE => ['http://example/']],
        ], $actual);
    }

    public function test_trig_syntax_base_02()
    {
       $this->markTestSkipped("Add proper support for blank nodes.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-base-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_base_03()
    {
        $this->markTestSkipped("Add proper support for base.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-base-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_base_04()
    {
        $this->markTestSkipped("Add proper support for base.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-base-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_blank_label()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-blank-label.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

    }

    public function test_trig_syntax_bnode_02()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bnode_01()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_03()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_04()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_05()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_06()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_07()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_08()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_bnode_09()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_syntax_bnode_10()
    {
        $this->markTestSkipped("Add proper support for blank label.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-bnode-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_datatypes_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-datatypes-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['xsd', 'http://www.w3.org/2001/XMLSchema#']],

            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', 'http://www.w3.org/2001/XMLSchema#byte']],

        ], $actual);

    }

    public function test_trig_syntax_datatypes_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-datatypes-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#']],
            [Parser::PREFIX => ['xsd', 'http://www.w3.org/2001/XMLSchema#']],

            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', 'http://www.w3.org/2001/XMLSchema#string']],

        ], $actual);

    }

    public function test_trig_syntax_file_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-file-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEmpty($actual);
    }

    public function test_trig_syntax_file_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-file-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEmpty($actual);
    }

    public function test_trig_syntax_file_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-file-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEmpty($actual);
    }

    public function test_trig_syntax_kw_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-kw-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['true', Namespaces::XSD_BOOLEAN]],

        ], $actual);

    }

    public function test_trig_syntax_kw_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-kw-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['false', Namespaces::XSD_BOOLEAN]],

        ], $actual);
    }

    public function test_trig_syntax_kw_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-kw-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']],
            [Parser::OBJECT_IRI => ['http://example/C']],
        ], $actual);
    }

    public function test_trig_syntax_lists_01()
    {
        $this->markTestSkipped("Add proper support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-lists-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_lists_02()
    {
        $this->markTestSkipped("Add proper support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-lists-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_lists_03()
    {
        $this->markTestSkipped("Add proper support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-lists-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_lists_04()
    {
        $this->markTestSkipped("Add proper support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-lists-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_lists_05()
    {
        $this->markTestSkipped("Add proper support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-lists-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_ln_colons()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-ln-colons.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::SUBJECT => ['http://example/s:1']],
            [Parser::PREDICATE => ['http://example/p:1']],
            [Parser::OBJECT_IRI => ['http://example/o:1']],

            [Parser::SUBJECT => ['http://example/s::2']],
            [Parser::PREDICATE => ['http://example/p::2']],
            [Parser::OBJECT_IRI => ['http://example/o::2']],

            [Parser::SUBJECT => ['http://example/3:s']],
            [Parser::PREDICATE => ['http://example/3:p']],
            [Parser::OBJECT_IRI => ['http://example/3']],

            [Parser::SUBJECT => ['http://example/:s']],
            [Parser::PREDICATE => ['http://example/:p']],
            [Parser::OBJECT_IRI => ['http://example/:o']],

            [Parser::SUBJECT => ['http://example/:s:']],
            [Parser::PREDICATE => ['http://example/:p:']],
            [Parser::OBJECT_IRI => ['http://example/:o:']],

        ], $actual);

    }

    public function test_trig_syntax_ln_dots()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-ln-dots.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::SUBJECT => ['http://example/s.1']],
            [Parser::PREDICATE => ['http://example/p.1']],
            [Parser::OBJECT_IRI => ['http://example/o.1']],

            [Parser::SUBJECT => ['http://example/s..2']],
            [Parser::PREDICATE => ['http://example/p..2']],
            [Parser::OBJECT_IRI => ['http://example/o..2']],

            [Parser::SUBJECT => ['http://example/3.s']],
            [Parser::PREDICATE => ['http://example/3.p']],
            [Parser::OBJECT_IRI => ['http://example/3']],

        ], $actual);

    }

    public function test_trig_syntax_minimal_whitespace_01()
    {
        $this->markTestSkipped("Add proper support for blank nodes, collections and base");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-minimal-whitespace-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_trig_syntax_ns_dots()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-ns-dots.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['e.g', 'http://example/']],

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);

    }

    public function test_trig_syntax_number_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],

        ], $actual);

    }

    public function test_trig_syntax_number_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['-123', Namespaces::XSD_INTEGER]],

        ], $actual);

    }

    public function test_trig_syntax_number_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['+123', Namespaces::XSD_INTEGER]],

        ], $actual);

    }

    public function test_trig_syntax_number_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123.0', Namespaces::XSD_DECIMAL]],

        ], $actual);

    }

    public function test_trig_syntax_number_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['.1', Namespaces::XSD_DECIMAL]],

        ], $actual);

    }

    public function test_trig_syntax_number_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['-123.0', Namespaces::XSD_DECIMAL]],

        ], $actual);

    }

    public function test_trig_syntax_number_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['+123.0', Namespaces::XSD_DECIMAL]],

        ], $actual);

    }

    public function test_trig_syntax_number_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],

        ], $actual);

    }

    public function test_trig_syntax_number_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123.0e1', Namespaces::XSD_DOUBLE]],

        ], $actual);

    }

    public function test_trig_syntax_number_10()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['-123e-1', Namespaces::XSD_DOUBLE]],

        ], $actual);

    }

    public function test_trig_syntax_number_11()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-number-11.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['s']],
            [Parser::PREDICATE => ['p']],
            [Parser::OBJECT_WITH_DATATYPE => ['123.E+1', Namespaces::XSD_DOUBLE]],

        ], $actual);
    }

    public function test_trig_syntax_pname_esc_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-pname-esc-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/~.-!$&\'()*+,;=/?#@_%AA']],

        ], $actual);
    }

    public function test_trig_syntax_pname_esc_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-pname-esc-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/0123~.-!$&\'()*+,;=/?#@_%AA123']],

        ], $actual);
    }

    public function test_trig_syntax_pname_esc_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-pname-esc-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/xyz~']],
            [Parser::PREDICATE => ['http://example/abc.:']],
            [Parser::OBJECT_IRI => ['http://example/']],

        ], $actual);
    }

    public function test_trig_syntax_prefix_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
        ], $actual);
    }

    public function test_trig_syntax_prefix_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
        ], $actual);
    }

    public function test_trig_syntax_prefix_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/123']],
        ], $actual);

    }

    public function test_trig_syntax_prefix_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/%20']],
        ], $actual);

    }

    public function test_trig_syntax_prefix_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/']],
            [Parser::PREDICATE => ['http://example/']],
            [Parser::OBJECT_IRI => ['http://example/']],
        ], $actual);

    }

    public function test_trig_syntax_prefix_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::PREFIX => ['x', 'http://example/']],

            [Parser::SUBJECT => ['http://example/a:b:c']],
            [Parser::PREDICATE => ['http://example/d:e:f']],
            [Parser::OBJECT_IRI => ['http://example/:::']],

        ], $actual);

    }

    public function test_trig_syntax_prefix_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['x', 'http://example/']],

            [Parser::SUBJECT => ['http://example/a-b-c']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);

    }

    public function test_trig_syntax_prefix_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['x', 'http://example/']],

            [Parser::SUBJECT => ['http://example/_']],
            [Parser::PREDICATE => ['http://example/p_1']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);

    }

    public function test_trig_syntax_prefix_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-prefix-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::PREFIX => ['x', 'http://example/']],

            [Parser::SUBJECT => ['http://example/a%3E']],
            [Parser::PREDICATE => ['http://example/%25']],
            [Parser::OBJECT_IRI => ['http://example/a%3Eb']],

        ], $actual);

    }

    public function test_trig_syntax_str_esc_01()
    {
        $this->markTestIncomplete("Add support for escaping in string literals");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-str-esc-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['a\n', Namespaces::XSD_STRING]],

        ], $actual);

    }

    public function test_trig_syntax_str_esc_02()
    {
        $this->markTestIncomplete("Add support for escaping in string literals");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-str-esc-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['a\u0020b', Namespaces::XSD_STRING]],

        ], $actual);

    }

    public function test_trig_syntax_str_esc_03()
    {
        $this->markTestIncomplete("Add support for escaping in string literals");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-str-esc-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['a\U00000020b', Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['string', Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['string', Namespaces::RDF_LANG_STRING, '@en']],

        ], $actual);
    }

    public function test_trig_syntax_string_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['string', Namespaces::RDF_LANG_STRING, '@en-uk']],

        ], $actual);
    }

    public function test_trig_syntax_string_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['string', Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['string', Namespaces::RDF_LANG_STRING, '@en']],

        ], $actual);
    }

    public function test_trig_syntax_string_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ['string', Namespaces::RDF_LANG_STRING, '@en-uk']],

        ], $actual);
    }

    public function test_trig_syntax_string_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ['abc""def\'\'ghi', Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_08()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-08.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["abc\ndef", Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_09()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-09.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_DATATYPE => ["abc\ndef", Namespaces::XSD_STRING]],

        ], $actual);
    }

    public function test_trig_syntax_string_10()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-10.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ["abc\ndef", Namespaces::RDF_LANG_STRING, '@en']],

        ], $actual);
    }

    public function test_trig_syntax_string_11()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-string-11.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_WITH_LANG_TAG => ["abc\ndef", Namespaces::RDF_LANG_STRING, '@en']],

        ], $actual);
    }

    public function test_trig_syntax_struct_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::OBJECT_IRI => ['http://example/o2']],

        ], $actual);
    }

    public function test_trig_syntax_struct_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],

        ], $actual);
    }

    public function test_trig_syntax_struct_03()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],

        ], $actual);
    }

    public function test_trig_syntax_struct_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],

        ], $actual);
    }

    public function test_trig_syntax_struct_05()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],
            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_IRI => ['http://example/o1']],
            [Parser::PREDICATE => ['http://example/p2']],
            [Parser::OBJECT_IRI => ['http://example/o2']],

        ], $actual);
    }

    public function test_trig_syntax_struct_06()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-06.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_struct_07()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-struct-07.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_uri_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-uri-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_uri_02()
    {
        $this->markTestIncomplete("Add support for decoding unicode encoded strings.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-uri-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/\u0053']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_uri_03()
    {
        $this->markTestIncomplete("Add support for decoding unicode encoded strings.");
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-uri-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/\U00000053']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

        ], $actual);
    }

    public function test_trig_syntax_uri_04()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-syntax-uri-04.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::SUBJECT => ['http://example/s']],
            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['scheme:!$%25&\'()*+,-./0123456789:/@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz~?#']],

        ], $actual);
    }

    public function test_trig_turtle_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

            [Parser::PREDICATE => ['http://example/q']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],
            [Parser::OBJECT_WITH_DATATYPE => ['456', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example/s']],

            [Parser::PREDICATE => ['http://example/p1']],
            [Parser::OBJECT_WITH_DATATYPE => ['more', Namespaces::XSD_STRING]],
            [Parser::SUBJECT => ['http://example/s1']],

        ], $actual);
    }

    public function test_trig_turtle_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

            [Parser::PREDICATE => ['http://example/q']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],
            [Parser::OBJECT_WITH_DATATYPE => ['456', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example/s']],

            [Parser::PREFIX => ['', 'http://example/ns#']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::SUBJECT => ['http://example/s']],

        ], $actual);
    }

    public function test_trig_turtle_03()
    {
        $this->markTestIncomplete("Add support for blank nodes");
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-03.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

            [Parser::PREDICATE => ['http://example/q']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],
            [Parser::OBJECT_WITH_DATATYPE => ['456', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example/s']],

            [Parser::PREFIX => ['', 'http://example/ns#']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::SUBJECT => ['http://example/s']],

        ], $actual);
    }

    public function test_trig_turtle_05()
    {
        $this->markTestIncomplete("Add support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-05.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

            [Parser::PREDICATE => ['http://example/q']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],
            [Parser::OBJECT_WITH_DATATYPE => ['456', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example/s']],

            [Parser::PREFIX => ['', 'http://example/ns#']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::SUBJECT => ['http://example/s']],

        ], $actual);
    }

    public function test_trig_turtle_06()
    {
        $this->markTestIncomplete("Add support for collections.");
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example/']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],

            [Parser::PREDICATE => ['http://example/q']],
            [Parser::OBJECT_WITH_DATATYPE => ['123', Namespaces::XSD_INTEGER]],
            [Parser::OBJECT_WITH_DATATYPE => ['456', Namespaces::XSD_INTEGER]],

            [Parser::SUBJECT => ['http://example/s']],

            [Parser::PREFIX => ['', 'http://example/ns#']],

            [Parser::PREDICATE => ['http://example/p']],
            [Parser::OBJECT_IRI => ['http://example/o']],
            [Parser::SUBJECT => ['http://example/s']],

        ], $actual);
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_turtle_bad_01()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-bad-01.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    /**
     * @expectedException \RDF\Parser\ParserException
     */
    public function test_trig_turtle_bad_02()
    {
        $content = $this->loadFixture("w3c-test-suite/trig-turtle-bad-02.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }
    }

    public function test_two_LITERAL_LONG2s()
    {
        $content = $this->loadFixture("w3c-test-suite/two_LITERAL_LONG2s.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['', 'http://example.org/ex#']],

            [Parser::SUBJECT => ['http://example.org/ex#a']],
            [Parser::PREDICATE => ['http://example.org/ex#b']],
            [Parser::OBJECT_WITH_DATATYPE => ['first long literal', Namespaces::XSD_STRING]],

            [Parser::SUBJECT => ['http://example.org/ex#c']],
            [Parser::PREDICATE => ['http://example.org/ex#d']],
            [Parser::OBJECT_WITH_DATATYPE => ['second long literal', Namespaces::XSD_STRING]],

        ], $actual);

    }

    public function test_underscore_in_localName()
    {
        $content = $this->loadFixture("w3c-test-suite/underscore_in_localName.trig");
        $p = new TrigParser();

        $actual = [];
        foreach ($p->parse($content) as $token => $values) {
            $actual[][$token] = $values;
        }

        $this->assertEquals([

            [Parser::PREFIX => ['p', 'http://a.example/']],

            [Parser::SUBJECT => ['http://a.example/s_']],
            [Parser::PREDICATE => ['http://a.example/p']],
            [Parser::OBJECT_IRI => ['http://a.example/o']],

        ], $actual);

    }

}