<?php

function write_asserts(array $actual): string {
    $codes = [
        "Parser::PREFIX",
        "Parser::GRAPH",
        "Parser::SUBJECT",
        "Parser::PREDICATE",
        "Parser::OBJECT",
        "Parser::OBJECT_WITH_LANG_TAG",
        "Parser::OBJECT_WITH_DATATYPE",
        "Parser::OBJECT_IRI",
        "Parser::BASE",
        "Parser::SUBJECT_BLANK_NODE",
        "Parser::OBJECT_BLANK_NODE",
        "Parser::GRAPH_BLANK_NODE",
    ];

    $string = array_reduce($actual, function($carry, $element) use ($codes) {
        $carry .= sprintf("[ %s => [ '%s' ]],\n", $codes[key($element)], implode(',', current($element)));
        return $carry;
    }, "");
    return sprintf("\$this->assertEquals([%s],\$actual);", $string);
}