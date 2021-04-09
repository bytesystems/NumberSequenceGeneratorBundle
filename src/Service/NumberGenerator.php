<?php

namespace Bytesystems\NumberGeneratorBundle\Service;

use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Doctrine\ORM\EntityManagerInterface;
use Bytesystems\NumberGeneratorBundle\Repository\NumberSequenceRepository;

class NumberGenerator
{

    /**
     * @var NumberSequenceRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var PropertyHelper
     */
    protected $propertyHelper;

    public function __construct(NumberSequenceRepository $repository, EntityManagerInterface $entityManager)
    {

        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getNextNumber(string $key, string $segment = null, string $pattern = null, int $initialValue = 0 ): int
    {

        $sequence = $this->getSequence($key,$segment, $pattern);

        $currentValue = max($sequence->getCurrent(), $annotation->init ?? 0);

        $sequence->setCurrent($currentValue+1);
        $this->entityManager->flush();
        return $sequence->getCurrent();
    }


    /**
     * @param string $key
     * @param string|null $segment
     * @param string|null $pattern
     *
     * @return NumberSequence
     */
    protected function getSequence(string $key, string $segment = null, string $pattern = null): NumberSequence
    {

        $sequences = $this->repository->getSequence($key, $segment);

        $sequence = array_filter($sequences, function($sequence) {
            return $sequence->getSegment() === null;
        })[0] ?? null;

        if($segment)
        {
            $sequence = array_filter($sequences, function($sequence) use ($segment) {
                return $sequence->getSegment() === $segment;
            })[0] ?? $sequence;
        }

        if (null === $sequence) {
            $sequence = new NumberSequence();
            $sequence->setKey($key);
            $sequence->setPattern($pattern);
            $this->repository->add($sequence,false);
        }
        return $sequence;
    }
}