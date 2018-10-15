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

}