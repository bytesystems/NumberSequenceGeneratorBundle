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
class Bar
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
    private $bar;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $baz;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $foo;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @NG\Sequence(
     *      key="qux",
     *      segment="{quux}",
     *      pattern="QUX{#|6}",
     *      segments={
     *          @NG\Segment(value="foo",pattern="QUXFOO{#|6}"),
     *          @NG\Segment(value="bar",pattern="QUXBAR{#|6}"),
     *          @NG\Segment(value="baz",pattern="QUXBAZ{#|6}")
     *     })
     * @var string
     */
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
    public function getBar(): ?string
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