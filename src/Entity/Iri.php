<?php

namespace RDFPhp\Entity;

use LogicException;

/**
 * Class Iri
 * @package RDF\Entity
 *
 * This class represents an iri.
 */
final class Iri
{
    /** @var string */
    private $iri;

    /** @var string */
    private $rawIri;

    /**
     * Iri constructor.
     * @param string $iri
     */
    public function __construct(string $iri)
    {
        $this->rawIri = $iri;

        if (empty($iri)) {
            throw new LogicException("Invalid IRI [$iri]");
        }

        if (0 === strpos($iri, 'http')) {
            $this->iri = "<$iri>";
        } else {
            $components = explode(':', $iri);
            if (2 > count($components)) {
                throw new LogicException("Invalid IRI [$iri]");
            }
            list($prefix, $value) = $components;
            $this->iri = "$prefix:" . urlencode($value);
        }
    }

    public function raw()
    {
        return $this->rawIri;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->iri;
    }
}
