<?php


namespace Bytesystems\NumberGeneratorBundle\EventListener;


use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Service\NumberGenerator;
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
    protected $resolver;

    public function __construct(AnnotationReader $annotationReader, NumberGenerator $generator, PropertyHelper $propertyHelper,SegmentResolver $resolver)
    {
        $this->annotationReader = $annotationReader;
        $this->generator = $generator;
        $this->propertyHelper = $propertyHelper;
        $this->resolver = $resolver;
    }

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

        dump($annotations);

        foreach ($annotations as $property => $annotation) {
            $segment = $this->resolver->resolveSegment($object,$annotation);
            $nextNumber = $this->generator->getNextNumber($annotation->key, $segment, $annotation->pattern, $annotation->init);
            $this->propertyHelper->setValue($object,$property,$nextNumber);
        }
    }
}