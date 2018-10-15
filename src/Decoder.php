<?php


namespace RDF\Decoder;

use RDF\Entity\Graph;
use RDF\Entity\Predicates;
use RDF\Entity\Prefix;
use RDF\Entity\RDFObject;
use RDF\Entity\RDFObjects;
use RDF\Entity\Resource;
use RDF\Entity\Resources;
use RDF\Entity\Subject;
use RDF\Parser\Parser;
use RDF\Entity\Predicate;
use RDF\Entity\Prefixes;

/**
 * Class Decoder
 * @package RDF\Decoder
 */
class Decoder
{
    /** @var Parser */
    protected $parser;
    protected $content;
    protected $prefixes = [];

    /** @var Graph */
    protected $graph;
    protected $subject;
    protected $subjects = [];
    protected $predicates = [];

    /** @var Resources */
    protected $resources;
    protected $pendingResources = [];

    protected $lastToken = null;

    public function __construct(Parser $parser, string $content)
    {
        $this->parser = $parser;
        $this->content = $content;
    }

    /**
     * @return Resources
     * @throws \RDF\Entity\EntityException
     */
    public function decode(): Resources
    {
        if (!is_null($this->resources)) {
            return $this->resources;
        }

        foreach ($this->parser->parse($this->content) as $token) {
            $values = $token['values'];
            switch ($token['keyword']) {
                case Parser::PREFIX:
                    $this->lastToken = Parser::PREFIX;
                    $this->prefixes[] = new Prefix($values[0], $values[1]);
                    break;
                case Parser::GRAPH:
                    $this->lastToken = Parser::GRAPH;
                    $this->graph = new Graph(current($values));
                    $this->appendSubject($this->subject);
                    $this->createResource();
                    break;
                case Parser::SUBJECT:

                    if (Parser::SUBJECT == $this->lastToken) {
                        $this->createResource();
                    }

                    $this->lastToken = Parser::SUBJECT;
                    $this->appendSubject(current($values));
                    break;
                case Parser::PREDICATE:
                    $this->lastToken = Parser::PREDICATE;
                    $this->predicate = current($values);
                    $this->predicates[$this->predicate] = (isset($this->predicates[$this->predicate])) ? $this->predicates[$this->predicate] : [];
                    break;
                case Parser::OBJECT:
                    $this->lastToken = Parser::OBJECT;
                    $this->predicates[$this->predicate][] = new RDFObject(current($values));
                    break;
                case Parser::OBJECT_WITH_LANG_TAG:
                    $this->lastToken = Parser::OBJECT_WITH_LANG_TAG;
                    $this->predicates[$this->predicate][] = new RDFObject($values[0], $values[1]);
                    break;
                case Parser::OBJECT_WITH_DATATYPE:
                    $this->lastToken = Parser::OBJECT_WITH_DATATYPE;
                    $this->predicates[$this->predicate][] = new RDFObject($values[0], null, $values[1]);
                    break;
                case Parser::OBJECT_IRI:
                    $this->lastToken = Parser::OBJECT_IRI;
                    $this->predicates[$this->predicate][] = new RDFObject(new Iri($values[0]));
                    break;
            }
        }

        // Flush remaining subject.
        if (!empty($this->subject)) {
            $this->appendSubject($this->subject);
        }

        $this->createResource();
        $this->resources = new Resources($this->pendingResources);
        return $this->resources;
    }

    /**
     * @throws \RDF\Entity\EntityException
     */
    protected function createResource()
    {
        $prefixes = !empty($this->prefixes) ? new Prefixes($this->prefixes) : null;
        foreach ($this->subjects as $subject => $predicates) {
            $this->pendingResources[] = new Resource($this->createSubject($subject, $predicates), $this->graph, $prefixes);
        }
        $this->graph = null;
        $this->subject = null;
        $this->subjects = [];
        $this->predicates = [];
    }

    /**
     * @param string $subject
     *
     * There two orders in which subjects can appear: BEFORE or AFTER it's predicate(s).
     * Subject appear BEFORE it's predicate(s) when the triples are in a graph
     * Subject appear AFTER it's predicate(s) when the triples are NOT in a graph.
     * So we need to account for both these situations.
     */
    protected function appendSubject(string $subject = "")
    {
        if (!empty($this->predicates) && empty($this->subject)) { // Subject appear AFTER it's predicates.

            $this->subjects[$subject] = isset($this->subjects[$subject]) ? $this->subjects[$subject] : [];
            $this->subjects[$subject] = $this->mergePredicates($this->subjects[$subject], $this->predicates);
            $this->predicates = [];

        } elseif (!empty($this->predicates) && !empty($this->subject)) { // Subject appear BEFORE it's predicates

            $this->subjects[$this->subject] = isset($this->subjects[$this->subject]) ? $this->subjects[$this->subject] : [];
            $this->subjects[$this->subject] = $this->mergePredicates($this->subjects[$this->subject], $this->predicates);
            $this->predicates = [];
            $this->subject = $subject;

        } elseif (empty($this->predicates) && empty($this->subject)) { // subject appear BEFORE it's predicates.

            $this->subject = $subject;
        }
    }

    /**
     * @param string $subject
     * @param array $data
     * @return Subject
     * @throws \RDF\Entity\EntityException
     */
    protected function createSubject(string $subject, array $data): Subject
    {
        $predicates = [];
        foreach ($data as $predicate => $objects) {
            $predicates[] = new Predicate($predicate, new RDFObjects($objects));
        }
        return new Subject($subject, new Predicates($predicates));
    }

    /**
     * Merge predicate arrays.
     *
     * Takes care of merging predicates with the same name and append new ones.
     *
     * @param array $a
     * @param array $b
     * @return array
     */
    protected function mergePredicates(array $a, array $b): array
    {
        if (empty($a)) {
            return $b;
        }

        $c = $a;
        // Merge the duplicates.
        $duplicates = array_keys(array_intersect_key($a, $b));
        foreach ($duplicates as $duplicate) {
            $c[$duplicate] = array_merge($c[$duplicate], array_values($b[$duplicate]));
        }

        $diff = array_keys(array_diff_key($b, $a));
        foreach ($diff as $d) {
            $c[$d] = $b[$d];
        }

        return $c;
    }
}
