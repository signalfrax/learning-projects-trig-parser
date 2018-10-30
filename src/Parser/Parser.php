<?php


namespace RDFPhp\Parser;

use Generator;

/**
 * Interface Parser
 * @package RDF\Parser
 */
interface Parser
{
    const PREFIX = 0;
    const GRAPH = 1;
    const SUBJECT = 2;
    const PREDICATE = 3;
    const OBJECT = 4;
    const OBJECT_WITH_LANG_TAG = 5;
    const OBJECT_WITH_DATATYPE = 6;
    const OBJECT_IRI = 7;
    const BASE = 8;
    const SUBJECT_BLANK_NODE = 9;
    const OBJECT_BLANK_NODE = 10;
    const GRAPH_BLANK_NODE = 11;
    const BLANK_NODE_PROPERTY_LIST_OPEN = 12;
    const BLANK_NODE_PROPERTY_LIST_CLOSE = 13;

    /**
     * This method must 'yield' and array of the following
     * the array structure is like this [
     *  'keyword' => one of the constants defined in this class
     *  'values' => []
     *  ]
     *
     * @param string $message
     * @return Generator
     */
    public function parse(string $message): Generator;
}
