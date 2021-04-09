<?php
namespace Bytesystems\NumberGeneratorBundle\Tests;

class PropertyMock
{
    private $privateProp = "private";
    public $publicProp = "public";

    public function setPrivateProperty($value)
    {
        $this->privateProp = $value;
    }

    public function getPrivateProp()
    {
        return $this->privateProp;
    }

    public function setPublicProperty($value)
    {
        $this->publicProp = $value;
    }

    public function getPublicProperty()
    {
        return $this->publicProp;
    }
}