<?php


namespace RDFPhp\Entity;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Traversable;

/**
 * Class RDFObjects
 * @package RDF\Entity
 */
final class RDFObjects implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable, Serializable
{
    private $objects = [];

    public function __construct(array $objects)
    {
        foreach ($objects as $object) {
            if (!$object instanceof RDFObject) {
                throw new EntityException("element is not of [" . RDFObject::class . "]");
            }
        }
        $this->objects = $objects;
    }

    public function all(): array
    {
        return $this->objects;
    }

    public function has($value): bool
    {
        /** @var RDFObject $object */
        foreach ($this->objects as $object) {
            if ($object->value() == $value) {
                return true;
            }
        }
        return false;
    }

    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
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
        return new ArrayIterator($this->objects);
    }

    public function count()
    {
        return count($this->objects);
    }

    public function jsonSerialize()
    {
        return $this->objects;
    }

    public function serialize()
    {
        return serialize($this->objects);
    }

    public function unserialize($serialized)
    {
        $this->objects = unserialize($serialized);
    }
}
