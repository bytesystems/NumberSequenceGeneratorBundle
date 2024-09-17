<?php


namespace Bytesystems\NumberGeneratorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Foo
 * @package Bytesystems\NumberGeneratorBundle\Tests\Entity
 */
#[ORM\Entity]
#[ORM\Table]
class Foo
{
    private $thud = "thudValue";

    /**
     *
     * @var integer
     */
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "bar", pattern: "BAR{#|6}", init: 1000)]
    #[ORM\Column(type: 'string', nullable: true)]
    private $bar;


    /**
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "baz", segment: "id")]
    #[ORM\Column(type: 'string', nullable: true)]
    private $baz;

    /**
     * @var string
     */
    #[\Bytesystems\NumberGeneratorBundle\Attribute\Sequence(key: "foo", segment: "{thud}")]
    #[ORM\Column(type: 'string', nullable: true)]
    private $foo;

    /**
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
    #[ORM\Column(type: 'string', nullable: true)]
    private $qux;

    private $quux = 'quuxValue';

    public function getQux(): ?string
    {
        return $this->qux;
    }

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


    public function getFoo(): string
    {
        return $this->foo;
    }

    public function setFoo(string $foo): Foo
    {
        $this->foo = $foo;
        return $this;
    }

    public function getBar(): string
    {
        return $this->bar;
    }
    public function setBar(string $bar): Foo
    {
        $this->bar = $bar;
        return $this;
    }

    public function getBaz(): string
    {
        return $this->baz;
    }

    public function setBaz(string $baz): Foo
    {
        $this->baz = $baz;
        return $this;
    }


}