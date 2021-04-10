<?php


namespace Bytesystems\NumberGeneratorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bytesystems\NumberGeneratorBundle\Annotation as NG;

/**
 * Class Foo
 * @package Bytesystems\NumberGeneratorBundle\Tests\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Foo
{
    private $thud = "thudValue";

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @NG\Sequence(key="bar",init=1000)
     * @var string
     */
    private $bar;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @NG\Sequence(key="baz",segment="id")
     * @var string
     */
    private $baz;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @NG\Sequence(key="foo",segment="{thud}")
     * @var string
     */
    private $foo;

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @return Foo
     */
    public function setFoo(string $foo): Foo
    {
        $this->foo = $foo;
        return $this;
    }

    /**
     * @return string
     */
    public function getBar(): string
    {
        return $this->bar;
    }

    /**
     * @param string $bar
     * @return Foo
     */
    public function setBar(string $bar): Foo
    {
        $this->bar = $bar;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaz(): string
    {
        return $this->baz;
    }

    /**
     * @param string $baz
     * @return Foo
     */
    public function setBaz(string $baz): Foo
    {
        $this->baz = $baz;
        return $this;
    }
}