<?php


namespace RDF\Entity;

use JsonSerializable;
use Serializable;

/**
 * Class Prefix
 * @package RDF\Entity
 */
final class Prefix implements JsonSerializable, Serializable
{
    protected $name;

    protected $iri;

    public function __construct(string $name, string $iri)
    {
        $this->name = $name;
        $this->iri = $iri;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function iri(): string
    {
        return $this->iri;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'iri' => $this->iri,
        ];
    }

    public function serialize()
    {
        return serialize(['name' => $this->name, 'iri' => $this->iri]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->name = $decoded['name'];
        $this->iri = $decoded['iri'];
    }
}
