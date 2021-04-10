<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Annotation;

use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;

class AnnotationReaderTest extends TestCase
{
    private $annotationReader;

    protected function setUp(): void
    {
        $this->annotationReader = new AnnotationReader();

        class_exists(Sequence::class);
    }


    public function testGetPropertiesWithAnnotationSequence()
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(new \ReflectionClass(Foo::class),Sequence::class);


        $this->assertIsArray($result);
        $this->assertCount(3,$result);
        $this->assertSame(
            ['bar','baz','foo'],
            array_keys($result)
        );

        // Get the annotation for the bar property
        $sequence = $result['bar'];

        $this->assertInstanceOf(Sequence::class,$sequence);
        $this->assertEquals(1000,$sequence->init);
        $this->assertEquals("bar",$sequence->key);
        $this->assertEquals(null,$sequence->segment);

    }

    public function testSequenceAnnotation()
    {
        $result = $this->annotationReader->getPropertiesWithAnnotation(new \ReflectionClass(Foo::class),Sequence::class);

        $sequence = $result['bar'];

        $this->assertInstanceOf(Sequence::class,$sequence);
        $this->assertEquals(1000,$sequence->init);
        $this->assertEquals("bar",$sequence->key);
        $this->assertEquals(null,$sequence->segment);
    }
}
