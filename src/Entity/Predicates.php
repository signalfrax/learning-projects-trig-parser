<?php


namespace RDFPhp\Entity;

use ArrayAccess;
use ArrayIterator;
use Countable;
use RDFPhp\Entity\EntityException;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Traversable;

/**
 * Class Predicates
 * @package RDF\Entity
 */
final class Predicates implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable, Serializable
{
    private $predicates = [];

    public function __construct(array $predicates)
    {
        foreach ($predicates as $predicate) {
            if (!$predicate instanceof Predicate) {
                throw new EntityException("element is not of [" . Predicate::class . "]");
            }
        }
        $this->predicates = $predicates;
    }

    public function all(): array
    {
        return $this->predicates;
    }

    public function has(string $predicateName): bool
    {
        /** @var Predicate $predicate */
        foreach ($this->predicates as $predicate) {
            if ($predicate->name() == $predicateName) {
                return true;
            }
        }
        return false;
    }

    public function get(string $predicateName): ?Predicate
    {
        /** @var Predicate $predicate */
        foreach ($this->predicates as $predicate) {
            if ($predicate->name() == $predicateName) {
                return $predicate;
            }
        }
        return null;
    }

    public function hasObject($object, string $predicateName = null): bool
    {
        $predicate = (!is_null($predicateName)) ? $this->get($predicateName) : null;
        if (!is_null($predicate)) {
            return $predicate->hasObject($object);
        }
        /** @var Predicate $predicate */
        foreach ($this->predicates as $predicate) {
            if ($predicate->hasObject($object)) {
                return true;
            }
        }
        return false;
    }

    public function offsetExists($offset)
    {
        return isset($this->predicates[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->predicates[$offset]) ? $this->predicates[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        throw new EntityException("[" . self::class . "] is immutable.");
    }

    public function offsetUnset($offset)
    {
        throw new EntityException("[" . self::class . "] is immutable.");
    }

    public function getIterator()
    {
        return new ArrayIterator($this->predicates);
    }

    public function count()
    {
        return count($this->predicates);
    }

    public function jsonSerialize()
    {
        return $this->predicates;
    }

    public function serialize()
    {
        return serialize($this->predicates);
    }

    public function unserialize($serialized)
    {
        $this->predicates = unserialize($serialized);
    }
}
