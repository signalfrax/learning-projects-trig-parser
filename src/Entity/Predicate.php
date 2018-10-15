<?php


namespace RDF\Entity;

use RDF\Entity\RDFObjects;
use JsonSerializable;
use Serializable;

/**
 * Class Predicate
 * @package RDF\Entity
 *
 * Represent a predicate.
 */
final class Predicate implements JsonSerializable, Serializable
{
    /** @var string */
    protected $name;

    /** @var RDFObjects */
    protected $objects;

    public function __construct(string $name, RDFObjects $objects)
    {
        $this->name = $name;
        $this->objects = $objects;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function objects(): RDFObjects
    {
        return $this->objects;
    }

    public function hasObject($object): bool
    {
        if (is_null($this->objects)) {
            return false;
        }
        return $this->objects->has($object);
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'objects' => $this->objects,
        ];
    }

    public function serialize()
    {
        return serialize(['name' => $this->name, 'objects' => $this->objects]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->name = $decoded['name'];
        $this->objects = $decoded['objects'];
    }
}
