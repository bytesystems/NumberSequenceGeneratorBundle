<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Service\PatternResolver;
use Bytesystems\NumberGeneratorBundle\Service\PropertyHelper;
use Bytesystems\NumberGeneratorBundle\Service\SegmentResolver;
use Bytesystems\NumberGeneratorBundle\Tests\Entity\Foo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PatternResolverTest extends TestCase
{
    public function testResolvePattern()
    {
        $propertyAccessor = new PropertyAccessor();
        $propertyHelper = new PropertyHelper($propertyAccessor);
        $segmentResolver = new SegmentResolver($propertyHelper);
        $patternResolver = new PatternResolver($propertyHelper);
        $annotationReader = new AnnotationReader();

        $foo = new Foo();

        $annotations = $annotationReader->getPropertiesWithAnnotation(new \ReflectionClass(Foo::class),Sequence::class);
        $annotation = $annotations['qux'];
        $segment = $segmentResolver->resolveSegment($foo,$annotation);
        $pattern = $patternResolver->resolvePattern($foo,$annotation,$segment);
        $this->assertEquals("QUX{#|6}",$pattern);
        $foo->setQuux('bar');
        $segment = $segmentResolver->resolveSegment($foo,$annotation);
        $pattern = $patternResolver->resolvePattern($foo,$annotation,$segment);
        $this->assertEquals("QUXBAR{#|6}",$pattern);
    }
}
