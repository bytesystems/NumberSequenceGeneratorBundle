<?php


namespace Bytesystems\NumberGeneratorBundle\Service;


class SegmentResolver
{
    protected $propertyHelper;

    public function __construct(PropertyHelper $propertyHelper)
    {
        $this->propertyHelper = $propertyHelper;
    }

    public function resolveSegment($object, $annotation)
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
}