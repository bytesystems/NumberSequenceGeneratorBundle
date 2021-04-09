<?php
namespace Bytesystems\NumberGeneratorBundle\Tests;

use Bytesystems\NumberGeneratorBundle\Annotation as NG;

class AnnotatedMock
{


    private $foo = "thud";

    /**
     * @NG\Sequence(key="for",segment="foo")
     *
     * @var string
     */
    private $bar = "waldo";

    /**
     * @NG\Sequence(key="for",segment="foo")
     *
     * @var string
     */
    public $baz = "fred";

    public function getFooValue()
    {
        return $this->foo;
    }

    public function getBarValue()
    {
        return $this->bar;
    }

    public function getBazValue()
    {
        return $this->baz;
    }
}