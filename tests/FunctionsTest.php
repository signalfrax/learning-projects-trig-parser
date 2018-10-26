<?php


namespace Tests;


use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{

    public function test_remove_dots_from_iri()
    {
        $this->assertEquals('/a/g', remove_dots_from_path("/a/b/c/./../../g"));
        $this->assertEquals('mid/6', remove_dots_from_path('mid/content=5/../6'));
        $this->assertEquals('/b/c/g', remove_dots_from_path('/b/c/g'));
        $this->assertEquals('', remove_dots_from_path('.........'));
        $this->assertEquals('/', remove_dots_from_path('/'));
        $this->assertEquals('/b/c/', remove_dots_from_path('/b/c/.'));
    }

    public function test_merge_iri_path()
    {
        $this->assertEquals('/bogus/description', merge_iri_path('/bogus/name', 'description'));
        $this->assertEquals('/description', merge_iri_path('', '/description'));
    }

    public function test_compose_iri_path()
    {
        $this->assertEquals('http://bogus/path?a=b&c=d#fragment', compose_iri_path([
            'scheme' => 'http',
            'host' => 'bogus',
            'path' => '/path',
            'query' => 'a=b&c=d',
            'fragment' => 'fragment'
        ]));

        $this->assertEquals('file:///path?a=b&c=d#fragment', compose_iri_path([
            'scheme' => 'file',
            'path' => '/path',
            'query' => 'a=b&c=d',
            'fragment' => 'fragment'
        ]));
    }

    public function test_resolve_relative_iri()
    {
        $base = parse_url('http://a/b/c/d;p?q');
        $this->assertEquals('g:h', resolve_relative_iri($base, 'g:h'));
        $this->assertEquals("http://a/b/c/g", resolve_relative_iri($base,"g"));
        $this->assertEquals("http://a/b/c/g", resolve_relative_iri($base,"./g"));
        $this->assertEquals("http://a/b/c/g/", resolve_relative_iri($base,"g/"));
        $this->assertEquals("http://a/g", resolve_relative_iri($base, "/g"));
        $this->assertEquals("http://g", resolve_relative_iri($base,"//g"));
        $this->assertEquals("http://a/b/c/d;p?y", resolve_relative_iri($base,"?y"));
        $this->assertEquals("http://a/b/c/g?y", resolve_relative_iri($base,"g?y"));
        $this->assertEquals("http://a/b/c/d;p?q#s", resolve_relative_iri($base,"#s"));
        $this->assertEquals("http://a/b/c/g#s", resolve_relative_iri($base,"g#s"));
        $this->assertEquals("http://a/b/c/g?y#s", resolve_relative_iri($base,"g?y#s"));
        $this->assertEquals("http://a/b/c/;x", resolve_relative_iri($base,";x"));
        $this->assertEquals("http://a/b/c/g;x", resolve_relative_iri($base,"g;x"));
        $this->assertEquals("http://a/b/c/g;x?y#s", resolve_relative_iri($base, "g;x?y#s"));
        $this->assertEquals("http://a/b/c/d;p?q", resolve_relative_iri($base,""));
        $this->assertEquals("http://a/b/c/", resolve_relative_iri($base,"."));
        $this->assertEquals("http://a/b/c/", resolve_relative_iri($base, "./"));
        $this->assertEquals("http://a/b/", resolve_relative_iri($base, ".."));
        $this->assertEquals("http://a/b/", resolve_relative_iri($base,"../"));
        $this->assertEquals("http://a/b/g", resolve_relative_iri($base, "../g"));
        $this->assertEquals("http://a/", resolve_relative_iri($base,"../..")); // infinite loop
        $this->assertEquals("http://a/", resolve_relative_iri($base, "../../"));
        $this->assertEquals("http://a/g", resolve_relative_iri($base, "../../g"));

        $base = parse_url('http://rdfphp');
        $this->assertEquals("http://rdfphp/g", resolve_relative_iri($base,"g"));

    }
}