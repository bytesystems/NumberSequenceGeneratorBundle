<?php
namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Bytesystems\NumberGeneratorBundle\Service\PropertyHelper;
use Bytesystems\NumberGeneratorBundle\Tests\PropertyMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PropertyHelperTest extends TestCase
{

    public function testSetValue()
    {
        $propertyAccessor = new PropertyAccessor();
        $propertyHelper = new PropertyHelper($propertyAccessor);

        $object = new PropertyMock();

        // Set a value on a public property.
        $propertyHelper->setValue($object,"publicProp","newPublic");
        $this->assertEquals("newPublic",$object->getPublicProperty());

        // Set a value on a private property.
        $propertyHelper->setValue($object,"privateProp","newPrivate");
        $this->assertEquals("newPrivate",$object->getPrivateProp());

        // Set a value on a non-existent property.
        $this->expectException(\InvalidArgumentException::class);
        $propertyHelper->setValue($object,"nonexistent","newValue");
    }

    public function testGetValue()
    {
        $propertyAccessor = new PropertyAccessor();
        $propertyHelper = new PropertyHelper($propertyAccessor);

        $object = new PropertyMock();

        // Get value off public property.
        $this->assertEquals("public",$propertyHelper->getValue($object,"publicProp"));

        // Get value off a private property.
        $this->assertEquals("private",$propertyHelper->getValue($object,"privateProp"));

        // Get value off a non-existent property.
        $this->expectException(\InvalidArgumentException::class);
        $propertyHelper->getValue($object,"nonexistent");
    }


}

