<?php


namespace RDF\Entity;

use JsonSerializable;
use Serializable;

/**
 * Class Graph
 * @package RDF\Entity
 */
final class Graph implements JsonSerializable, Serializable
{
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function serialize()
    {
        return serialize(['name' => $this->name]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->name = $decoded['name'];
    }
}
