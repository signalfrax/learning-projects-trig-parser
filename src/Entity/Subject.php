<?php


namespace RDFPhp\Entity;

use RDFPhp\Namespaces;
use RDFPhp\Entity\Predicates;
use JsonSerializable;
use Serializable;

/**
 * Class Subject
 * @package RDF\Entity
 */
final class Subject implements JsonSerializable, Serializable
{
    /** @var string */
    protected $name;

    /** @var Predicates  */
    protected $predicates;

    /** @var string */
    protected $type;

    public function __construct(string $name, Predicates $predicates)
    {
        $this->name = $name;
        $this->predicates = $predicates;
        $type = $this->predicates->get(Namespaces::RDF_TYPE);
        $this->type = (!is_null($type)) ? $type->objects()[0]->value() : null;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function predicates(): Predicates
    {
        return $this->predicates;
    }

    public function getObjectValue(string $predicateName)
    {
        /** @var Predicate $predicate */
        $predicate = $this->predicates->get($predicateName);
        return (!is_null($predicate)) ? $predicate->objects()->all() : null;
    }

    public function hasPredicate(string $predicateName): bool
    {
        return (!is_null($this->predicates)) ? $this->predicates()->has($predicateName) : false;
    }

    public function hasObject($object, string $predicateName = null): bool
    {
        return (!is_null($this->predicates)) ? $this->predicates->hasObject($object, $predicateName): false;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'predicates' => $this->predicates
        ];
    }

    public function serialize()
    {
        return serialize(['name' => $this->name, 'type' => $this->type, 'predicates' => $this->predicates]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->name = $decoded['name'];
        $this->type = $decoded['type'];
        $this->predicates = $decoded['predicates'];
    }
}
