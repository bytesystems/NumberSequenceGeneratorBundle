<?php


namespace Bytesystems\NumberGeneratorBundle\EventListener;


use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Service\NumberGenerator;
use Bytesystems\NumberGeneratorBundle\Service\PatternResolver;
use Bytesystems\NumberGeneratorBundle\Service\PropertyHelper;
use Bytesystems\NumberGeneratorBundle\Service\SegmentResolver;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

class NumberGeneratorSubscriber implements EventSubscriber
{

    protected $annotationReader;
    protected $generator;
    /**
     * @var PropertyHelper
     */
    protected $propertyHelper;
    /**
     * @var SegmentResolver
     */
    protected $segmentResolver;

    public function __construct(AnnotationReader $annotationReader, NumberGenerator $generator, PropertyHelper $propertyHelper,SegmentResolver $segmentResolver)
    {
        $this->annotationReader = $annotationReader;
        $this->generator = $generator;
        $this->propertyHelper = $propertyHelper;
        $this->segmentResolver = $segmentResolver;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        $reflectionClass = new ReflectionClass($object);
        $annotations = $this->annotationReader->getPropertiesWithAnnotation($reflectionClass, Sequence::class);

        if(count($annotations) == 0) return;

        foreach ($annotations as $property => $annotation) {
            $selector = $this->segmentResolver->resolveSegmentationValue($object,$annotation);
            $segment = $this->segmentResolver->resolveSegment($annotation,$selector);


            $nextNumber = $this->generator->getNextNumber($annotation, $selector, $segment);
            $this->propertyHelper->setValue($object,$property,$nextNumber);
        }
    }
}
