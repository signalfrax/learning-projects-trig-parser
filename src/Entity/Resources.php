<?php


namespace RDF\Entity;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Traversable;
use RDF\Entity\Resource;

/**
 * Class Resources
 * @package RDF\Entity
 */
final class Resources implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable, Serializable
{
    private $resources = [];

    public function __construct(array $resources)
    {
        foreach ($resources as $resource) {
            if (!$resource instanceof Resource) {
                throw new EntityException("element is not of [" . Resource::class . "]");
            }
        }
        $this->resources = $resources;
    }

    /**
     * Get array representation.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->resources;
    }

    /**
     * Returns resources NOT matching the filters.
     * This function essentially does the reverse of 'filter'
     *
     * @param string|null $graph
     * @param string|null $subjectName
     * @param string|null $predicateName
     * @param null $objectValue
     * @return Resources|null
     * @throws EntityException
     */
    public function exclude(string $graph = null, string $subjectName = null, string $predicateName = null, $objectValue = null): ?Resources
    {
        return $this->_filter(true, $graph, $subjectName, $predicateName, $objectValue);
    }

    /**
     * Get Resources matching the given filters.
     *
     * @param string|null $graph
     * @param string|null $subjectName
     * @param string|null $predicateName
     * @param null $objectValue
     * @return Resources|null
     * @throws EntityException
     */
    public function filter(string $graph = null, string $subjectName = null, string $predicateName = null, $objectValue = null): ?Resources
    {
        return $this->_filter(false, $graph, $subjectName, $predicateName, $objectValue);
    }

    public function offsetExists($offset)
    {
        return isset($this->resources[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->resources[$offset]) ? $this->resources[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        throw new EntityException("[" .self::class . "] is immutable.");
    }

    public function offsetUnset($offset)
    {
        throw new EntityException("[" . self::class . "] is immutable.");
    }

    public function getIterator()
    {
        return new ArrayIterator($this->resources);
    }

    public function count()
    {
        return count($this->resources);
    }

    public function jsonSerialize()
    {
        return $this->resources;
    }

    public function serialize()
    {
        return serialize($this->resources);
    }

    public function unserialize($serialized)
    {
        $this->resources = unserialize($serialized);
    }

    /**
     * This method allows us to fetch resources by graph, subject, predicate and object value.
     * Multiple filters will perform an AND operation e.g fetch all resource whose graph AND subject match.
     *
     * @param bool $inverse
     * @param string|null $graph
     * @param string|null $subjectName
     * @param string|null $predicateName
     * @param null $objectValue
     * @return Resources|null
     * @throws EntityException
     */
    protected function _filter(bool $inverse, string $graph = null, string $subjectName = null, string $predicateName = null, $objectValue = null): ?Resources
    {
        if (empty($this->resources)) {
            return null;
        }

        // The filter process is done using a score.
        // Every resource that matches the total score will be filtered.
        // The total score is calculated based on the amount of filters used. e.g. $graph and $subjectName will be a score of 2 etc.
        // Only filters that have been provided will be considered when scoring resources.
        // The reason for using score is to keep the number of conditional statements to a minimum.
        $totalScore = 0;
        $totalScore = (!is_null($graph)) ? $totalScore + 1 : $totalScore;
        $totalScore = (!is_null($subjectName)) ? $totalScore + 1 : $totalScore;
        $totalScore = (!is_null($predicateName) || !is_null($objectValue)) ? $totalScore + 1 : $totalScore;

        // Fetch the resources that match the total score.
        $resources = array_reduce($this->resources, function ($carry, Resource $resource) use ($inverse, $totalScore, $graph, $subjectName, $predicateName, $objectValue) {
            $score = 0;

            // Check if there's a match for resource graph.
            // Notice that we only do this check if graph has been provided as one of the filters.
            if (!is_null($graph) && !is_null($resource->graph()) && $resource->graph()->name() == $graph) {
                $score++;
            }

            // Check if there's a match for resource subject.
            // Notice that we only do this check if subject has been provided as one of the filters.
            if (!is_null($subjectName) && !is_null($resource->subject()) && $resource->subject()->name() == $subjectName) {
                $score++;
            }

            // Check if there's a match for resource predicate and/or object.
            // Notice that we only do this check if predicate and/or object has been provided as one of the filters.
            if (!is_null($predicateName) && !is_null($objectValue) && $resource->hasObject($objectValue, $predicateName)) {
                $score++;
            } elseif (!is_null($predicateName) && is_null($objectValue) && $resource->hasPredicate($predicateName)) {
                $score++;
            } elseif (!is_null($objectValue) && is_null($predicateName) && $resource->hasObject($objectValue)) {
                $score++;
            }

            // Here we collect the resource that match the score.
            // Inverse means that collect the resources that DO NOT match the filters.
            // This is used for the exclude functionality.
            if ($score == $totalScore) {
                $carry[] = $resource;
            } elseif ($inverse && $score < $totalScore) {
                $carry[] = $resource;
            }
            return $carry;
        }, []);

        return (!empty($resources)) ? new Resources($resources) : null;

    }
}
