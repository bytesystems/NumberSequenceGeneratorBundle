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
    /**
     * @var PatternResolver
     */
    private $patternResolver;

    public function __construct(AnnotationReader $annotationReader, NumberGenerator $generator, PropertyHelper $propertyHelper,SegmentResolver $segmentResolver, PatternResolver $patternResolver)
    {
        $this->annotationReader = $annotationReader;
        $this->generator = $generator;
        $this->propertyHelper = $propertyHelper;
        $this->segmentResolver = $segmentResolver;
        $this->patternResolver = $patternResolver;
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
            $segment = $this->segmentResolver->resolveSegment($object,$annotation);
            $pattern = $this->patternResolver->resolvePattern($object,$annotation,$segment);

            $nextNumber = $this->generator->getNextNumber($annotation->key, $segment, $pattern, $annotation->init);
            $this->propertyHelper->setValue($object,$property,$nextNumber);
        }
    }
}
