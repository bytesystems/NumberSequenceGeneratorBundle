<?php


namespace Bytesystems\NumberGeneratorBundle\Service;
use ReflectionClass;

class AnnotationReader
{
    public function getPropertiesWithAttribute(ReflectionClass $class, string $attributeName): array
    {
        $annotatedProperties = [];

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $attributes = $property->getAttributes($attributeName);
            foreach ($attributes as $attribute) {
                $annotatedProperties[$property->getName()] = $attribute->newInstance();
            }
        }

        $parentClass = $class->getParentClass();
        if ($parentClass instanceof ReflectionClass) {
            $annotatedProperties = array_merge(
                $annotatedProperties,
                $this->getPropertiesWithAttribute($parentClass, $attributeName)
            );
        }

        return $annotatedProperties;
    }
}