<?php


namespace Bytesystems\NumberGeneratorBundle\EventListener;


use Bytesystems\NumberGeneratorBundle\Annotation\AnnotationReader;
use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Service\NumberGenerator;
use Bytesystems\NumberGeneratorBundle\Service\PropertyHelper;
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

    public function __construct(AnnotationReader $annotationReader, NumberGenerator $generator,PropertyHelper $propertyHelper)
    {
        $this->annotationReader = $annotationReader;
        $this->generator = $generator;
        $this->propertyHelper = $propertyHelper;
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

        foreach ($annotations as $property => $annotation) {
            $segment = $this->resolveSegment($object,$annotation);
            $nextNumber = $this->generator->getNextNumber($annotation->key, $segment, $annotation->pattern, $annotation->init);
            $this->propertyHelper->setValue($object,$property,$nextNumber);
        }
    }

    protected function resolveSegment($object, $annotation)
    {
        $segment = $annotation->segment;

        $matches = [];
        if (preg_match_all('/{(.*?)}/', $segment, $matches)) {
            foreach ((array) $matches[1] as $key => $property) {
                $segmentPart = $this->propertyHelper->getValue($object, $property);
                $segment = str_replace($matches[0][$key], $segmentPart, $segment);
            }
        }

        return $segment;

    }
}