<?php


namespace RDF\Entity;

use RDF\Entity\Prefixes;
use JsonSerializable;
use Serializable;

/**
 * Class Resource
 * @package RDF\Entity
 */
final class Resource implements JsonSerializable, Serializable
{
    /** @var Prefixes */
    protected $prefixes;

    /** @var Graph */
    protected $graph;

    /** @var Subject */
    protected $subject;

    /**
     * Resource constructor.
     * @param Subject|null $subject
     * @param Graph|null $graph
     * @param Prefixes|null $prefixes
     */
    public function __construct(Subject $subject = null, Graph $graph = null, Prefixes $prefixes = null)
    {
        $this->subject = $subject;
        $this->graph = $graph;
        $this->prefixes = $prefixes;
    }

    public function subject(): ?Subject
    {
        return $this->subject;
    }

    public function graph(): ?Graph
    {
        return $this->graph;
    }

    public function prefixes(): ?Prefixes
    {
        return $this->prefixes;
    }

    /**
     * check if this resource has the predicate.
     *
     * @param string $predicateName
     * @return bool
     */
    public function hasPredicate(string $predicateName): bool
    {
        return (is_null($this->subject)) ? false : $this->subject->hasPredicate($predicateName);
    }

    /**
     * Check if this resource has the object.
     *
     * @param $object
     * @param string|null $predicateName
     * @return bool
     */
    public function hasObject($object, string $predicateName = null): bool
    {
        return (is_null($this->subject)) ? false : $this->subject->hasObject($object, $predicateName);
    }

    /**
     * @param string $predicateName
     * @return mixed
     */
    public function getObjectValue(string $predicateName)
    {
        return $this->subject->getObjectValue($predicateName);
    }

    public function jsonSerialize()
    {
        return [
            'graph' => $this->graph,
            'prefixes' => $this->prefixes,
            'subject' => $this->subject
        ];
    }


    public function serialize()
    {
        return serialize(['graph' => $this->graph, 'prefixes' => $this->prefixes, 'subject' => $this->subject]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->graph = $decoded['graph'];
        $this->prefixes = $decoded['prefixes'];
        $this->subject = $decoded['subject'];
    }
}
