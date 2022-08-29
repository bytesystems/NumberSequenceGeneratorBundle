<?php


namespace Bytesystems\NumberGeneratorBundle\Service;


class SegmentResolver
{
    protected $propertyHelper;

    public function __construct(PropertyHelper $propertyHelper)
    {
        $this->propertyHelper = $propertyHelper;
    }

    public function resolveSegmentationValue($object, $annotation)
    {
        $segment = $annotation->segment == null ? '' : $annotation->segment;

        $matches = [];
        if (preg_match_all('/{(.*?)}/', $segment, $matches)) {
            foreach ((array) $matches[1] as $key => $property) {
                $segmentPart = $this->propertyHelper->getValue($object, $property);
                $segment = str_replace($matches[0][$key], $segmentPart, $segment);
            }
        }

        return $segment;
    }

    public function resolveSegment($annotation,$value)
    {
        if($annotation->segments == null) return null;
        $segments = $annotation->segments;

        foreach ($segments as $segment) {
            if($value == $segment->value) return $segment;
        }

        return null;
    }
}