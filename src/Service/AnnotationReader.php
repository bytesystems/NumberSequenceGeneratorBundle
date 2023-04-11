<?php


namespace Bytesystems\NumberGeneratorBundle\Service;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use ReflectionClass;

class AnnotationReader extends DoctrineAnnotationReader
{
    /**
     * Returns a list of annotation instances for the properties having the specified annotation.
     *
     * @param ReflectionClass $class          The ReflectionClass of the class from which
     *                                        the class annotations should be read
     * @param string          $annotationName The FQCN of the annotation class
     *
     * @return object[]
     */

    public function getPropertiesWithAnnotation(ReflectionClass $class, $annotationName)
    {
        $annotatedProperties = [];

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $annotation = $this->getPropertyAnnotation($property, $annotationName);
            if ($annotation instanceof $annotationName) {
                $annotatedProperties[$property->getName()] = $annotation;
            }
        }

        $parentClass = $class->getParentClass();
        if ($parentClass instanceof ReflectionClass) {
            $annotatedProperties = array_merge(
                $annotatedProperties,
                $this->getPropertiesWithAnnotation($parentClass, $annotationName)
            );
        }

        return $annotatedProperties;
    }

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