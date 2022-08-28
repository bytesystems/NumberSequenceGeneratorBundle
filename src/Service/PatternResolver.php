<?php


namespace Bytesystems\NumberGeneratorBundle\Service;


class PatternResolver
{
    protected $propertyHelper;

    public function __construct(PropertyHelper $propertyHelper)
    {
        $this->propertyHelper = $propertyHelper;
    }

    public function resolvePattern($object, $annotation,$value)
    {
        if($annotation->segments == null) return $annotation->pattern;

        $segments = $annotation->segments;

        foreach ($segments as $segment) {
            if($value == $segment->value) return $segment->pattern;
        }

        return $annotation->pattern;
    }
}