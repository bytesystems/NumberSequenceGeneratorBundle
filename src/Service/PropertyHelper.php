<?php


namespace Bytesystems\NumberGeneratorBundle\Service;


use Exception;
use InvalidArgumentException;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyHelper
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;



    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * Returns the value of the specified $property from $object.
     *
     * @param object $object                the object with the named $property
     * @param string $property              the property name on the $object
     *
     * @return mixed                        value of the property $property on the object $object
     *
     * @throws InvalidArgumentException     if the property does not exist
     */
    public function getValue(object $object, string $property)
    {
        try {
            $value = $this->propertyAccessor->getValue($object, $property);
        } catch (Exception $exception) {
            try {
                $reflection = new ReflectionProperty($object,$property);
                $reflection->setAccessible(true);
                $value = $reflection->getValue($object);

            } catch (Exception $exception) {
                throw new InvalidArgumentException(
                    sprintf('Can\'t read property "%s" on class "%s". The property does not exist.',$property,get_class($object))
                );
            }
        }

        return $value;
    }

    /**
     * Sets the value of the property on the specified object
     *
     * @param object $object    the object with the named $property
     * @param string $property  the property name on the $object
     * @param mixed  $value     the new value for the property
     *
     * @return void
     *
     * @throws InvalidArgumentException if the property does not exist
     */
    public function setValue(object $object, string $property, $value): void
    {
        try {
            $this->propertyAccessor->setValue($object,$property,$value);
        } catch (Exception $e) {
            try {
                $reflection = new ReflectionProperty($object,$property);
                $reflection->setAccessible(true);
                $reflection->setValue($object,$value);
            } catch (Exception $exception) {
                throw new InvalidArgumentException(
                    sprintf('Can\'t write property "%s" on class "%s". The property does not exist.',$property,get_class($object))
                );
            }
        }
    }
}