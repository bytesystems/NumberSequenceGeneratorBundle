<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Annotation;

use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Tests\AnnotatedMock;
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
        $result = $this->annotationReader->getPropertiesWithAnnotation(new \ReflectionClass(AnnotatedMock::class),Sequence::class);


        $this->assertIsArray($result);
        $this->assertCount(2,$result);
        $this->assertSame(
            ['bar','baz'],
            array_keys($result)
        );

    }
}
