<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Service\PropertyHelper;
use Bytesystems\NumberGeneratorBundle\Service\SegmentResolver;
use Bytesystems\NumberGeneratorBundle\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SegmentResolverTest extends TestCase
{

    public function testResolveSegment()
    {
        $propertyAccessor = new PropertyAccessor();
        $propertyHelper = new PropertyHelper($propertyAccessor);
        $segmentResolver = new SegmentResolver($propertyHelper);
        $annotationReader = new AnnotationReader();

        $foo = new Foo();

        $annotations = $annotationReader->getPropertiesWithAnnotation(new \ReflectionClass(Foo::class),Sequence::class);
        $annotation = $annotations['foo'];
        $segment = $segmentResolver->resolveSegment($foo,$annotation);
        $this->assertEquals("thudValue",$segment);
    }
}
