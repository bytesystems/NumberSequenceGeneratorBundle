<?php

namespace Bytesystems\NumberGeneratorBundle\Service;

use Bytesystems\NumberGeneratorBundle\Attribute\Segment;
use Bytesystems\NumberGeneratorBundle\Attribute\Sequence;
use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Bytesystems\NumberGeneratorBundle\Repository\NumberSequenceRepository;
use Bytesystems\NumberGeneratorBundle\Token\Token;
use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerRegistry;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

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
    /**
     * @var TokenHandlerRegistry
     */
    protected $tokenHandlerRegistry;

    public function __construct(NumberSequenceRepository $repository, TokenHandlerRegistry $tokenHandlerRegistry)
    {

        $this->repository = $repository;
        $this->tokenHandlerRegistry = $tokenHandlerRegistry;
    }

    public function getNextNumber(Sequence $annotation, ?string $selector = null, ?Segment $segment = null): string
    {
        $sequence = $this->getSequence($annotation, $selector, $segment);

        $pattern = $sequence->getPattern();
        $tokens = $this->tokenize($pattern,$sequence->getUpdatedAt());

        if($this->checkForReset($tokens))
        {
            $sequence->setCurrentNumber($annotation->init ?? 0);
        }
        $nextNumber = $sequence->getNextNumber($annotation->init ?? 0);
        $this->repository->flush();


        return $this->replace($tokens,$pattern,$nextNumber);
    }



    /**
     * @return NumberSequence
     */
    protected function getSequence(Sequence $annotation, ?string $selector = null, ?Segment $segment = null): NumberSequence
    {
        $defaultPattern = $annotation->pattern ?? '{#}';
        $defaultSequence = $this->repository->getSequence($annotation->key);

        if(null === $defaultSequence) {
            $sequence = new NumberSequence();
            $sequence->setKey($annotation->key);
            $sequence->setPattern($defaultPattern);
            $this->repository->add($sequence,false);
            $defaultSequence = $sequence;
        }


        if(null === $selector) return $defaultSequence;

        $segmentSequence = $this->repository->getSegmentedSequence($annotation->key,$selector);

        if(null !== $segmentSequence) return $segmentSequence;
        if(null === $segment) return $defaultSequence;

        $sequence = new NumberSequence();
        $sequence->setKey($annotation->key);
        $sequence->setSegment($selector);
        $sequence->setPattern($segment->pattern);
        $this->repository->add($sequence,false);
        return $sequence;

    }

    private function tokenize(string $pattern, DateTime $lastUpdate): array
    {
        $tokens = [];

        if (preg_match_all('~{([^|}]*?\|?.*)}~U', $pattern, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $info = explode('|', $matches[1][$i]);
                $tokens[] = new Token($info,$matches[0][$i],$lastUpdate);


            }
        }
        return $tokens;
    }

    /**
     * @param array $tokens
     * @return bool
     */
    protected function checkForReset(array $tokens): bool
    {
        foreach ($tokens as $token) {
            foreach ($this->tokenHandlerRegistry->getHandlers() as $tokenHandler) {
                if ($tokenHandler->handles($token)) {
                    if ($tokenHandler->requestsReset($token)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param array $tokens
     * @param string $pattern
     * @param int $value
     * @return string
     */
    protected function replace(array $tokens, string $pattern, int $value): string
    {
        $processedPattern = $pattern;
        /* @var $token Token */
        foreach ($tokens as $token) {
            foreach ($this->tokenHandlerRegistry->getHandlers() as $tokenHandler) {
                if ($tokenHandler->handles($token)) {
                    $processedPattern = str_replace($token->getReplace(),$tokenHandler->getValue($token,$value),$processedPattern);
                }
            }
        }
        return $processedPattern;
    }
}