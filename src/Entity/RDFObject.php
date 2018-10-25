<?php


namespace RDFPhp\Entity;

use RDFPhp\Entity\Iri;
use JsonSerializable;
use Serializable;

/**
 * Class RDFObject
 * @package RDF\Entity
 */
final class RDFObject implements JsonSerializable, Serializable
{
    protected $value;
    protected $langTag;
    protected $dataType;
    protected $isResource = false;

    public function __construct($value, string $langTag = null, string $dataType = null)
    {
        $this->value = (!$value instanceof Iri) ? $value : $value->raw();
        $this->isResource = ($value instanceof Iri);
        $this->langTag = $langTag;
        $this->dataType = $dataType;
    }

    public function value()
    {
        return $this->value;
    }

    public function langTag()
    {
        return $this->langTag;
    }

    public function dataType()
    {
        return $this->dataType;
    }

    public function isResource()
    {
        return $this->isResource;
    }

    public function jsonSerialize()
    {
        return [
            'value' => $this->value,
            'langTag' => $this->langTag,
            'dataType' => $this->dataType,
            'isResource' => $this->isResource,
        ];
    }

    public function serialize()
    {
        return serialize([
            'value' => $this->value,
            'langTag' => $this->langTag,
            'dataType' => $this->dataType,
            'isResource' => $this->isResource,
            ]);
    }

    public function unserialize($serialized)
    {
        $decoded = unserialize($serialized);
        $this->value = $decoded['value'];
        $this->langTag = $decoded['langTag'];
        $this->dataType = $decoded['dataType'];
        $this->isResource = $decoded['isResource'];
    }
}
