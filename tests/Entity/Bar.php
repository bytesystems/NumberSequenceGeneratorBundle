<?php


namespace Bytesystems\NumberGeneratorBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Foo
 * @package Bytesystems\NumberGeneratorBundle\Tests\Entity
 */
#[ORM\Entity]
#[ORM\Table]
class Bar
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
    #[ORM\Column(type: 'string', nullable: true)]
    private $bar;


    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $baz;

    /**
     * @var string
     */
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

    /**
     * @return string
     */
    public function getQux(): string
    {
        return $this->qux;
    }

    /**
     * @param string $qux
     * @return Bar
     */
    public function setQux(string $qux): Bar
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
     * @return Bar
     */
    public function setQuux(string $quux): Bar
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
     * @return Bar
     */
    public function setFoo(string $foo): Bar
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
     * @return Bar
     */
    public function setBar(string $bar): Bar
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
     * @return Bar
     */
    public function setBaz(string $baz): Bar
    {
        $this->baz = $baz;
        return $this;
    }
}