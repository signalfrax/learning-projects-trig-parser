<?php


namespace RDFPhp\Entity;

use RDFPhp\Entity\EntityException;
use RDFPhp\Entity\Prefix;
use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Traversable;

/**
 * Class Prefixes
 * @package RDF\Entity
 */
final class Prefixes implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable, Serializable
{
    /** @var array */
    private $prefixes;

    /**
     * Prefixes constructor.
     * @param array $prefixes
     * @throws EntityException
     */
    public function __construct(array $prefixes)
    {
        foreach ($prefixes as $prefix) {
            if (!$prefix instanceof Prefix) {
                throw new EntityException("element is not of [" . Prefix::class . "]");
            }
        }
        $this->prefixes = $prefixes;
    }

    public function offsetExists($offset)
    {
        return isset($this->prefixes[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->prefixes[$offset]) ? $this->prefixes[$offset]: null;
    }

    public function offsetSet($offset, $value)
    {
        throw new EntityException("[" .  self::class . "] is immutable.");
    }

    public function offsetUnset($offset)
    {
        throw new EntityException("[" . self::class . "] is immutable.");
    }

    public function count()
    {
        return count($this->prefixes);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->prefixes);
    }

    public function jsonSerialize()
    {
        return $this->prefixes;
    }
    public function serialize()
    {
        return serialize($this->prefixes);
    }

    public function unserialize($serialized)
    {
        $this->prefixes = unserialize($serialized);
    }
}
