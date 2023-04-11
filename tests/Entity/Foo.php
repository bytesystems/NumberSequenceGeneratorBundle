<?php


namespace Bytesystems\NumberGeneratorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "bar", pattern: "BAR{#|6}", init: 1000)]
    private $bar;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "baz", segment: "id")]
    private $baz;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "foo", segment: "{thud}")]
    private $foo;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(
        key: "qux",
        segment: "{quux}",
        segments: [
            new \Bytesystems\NumberGeneratorBundle\Attribute\Segment(value: "foo", pattern: "QUXFOO{#|6}"),
            new \Bytesystems\NumberGeneratorBundle\Attribute\Segment(value: "bar", pattern: "QUXBAR{#|6}"),
            new \Bytesystems\NumberGeneratorBundle\Attribute\Segment(value: "baz", pattern: "QUXBAZ{#|6}")
        ],
        pattern: "QUX{#|6}"
    )]
    private $qux;

    private $quux = 'quuxValue';

    /**
     * @return string
     */
    public function getQux(): string
    {
        return $this->qux;
    }

    /**
     * @param string $qux
     * @return Foo
     */
    public function setQux(string $qux): Foo
    {
        $this->qux = $qux;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuux(): string
    {
        return $this->quux;
    }

    /**
     * @param string $quux
     * @return Foo
     */
    public function setQuux(string $quux): Foo
    {
        $this->quux = $quux;
        return $this;
    }



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