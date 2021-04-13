<?php

namespace Bytesystems\NumberGeneratorBundle\Service;

use Bytesystems\NumberGeneratorBundle\Annotation\Sequence;
use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Bytesystems\NumberGeneratorBundle\Token\Token;
use Bytesystems\NumberGeneratorBundle\Token\TokenFactory;
use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerRegistry;
use DateTime;
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
    /**
     * @var TokenHandlerRegistry
     */
    protected $tokenHandlerRegistry;

    public function __construct(NumberSequenceRepository $repository, TokenHandlerRegistry $tokenHandlerRegistry)
    {

        $this->repository = $repository;
        $this->tokenHandlerRegistry = $tokenHandlerRegistry;
    }

    public function getNextNumber(string $key, ?string $segment = null, ?string $pattern = null, ?int $initialValue = 0 ): string
    {
        $sequence = $this->getSequence($key,$segment, $pattern);

        $pattern = $sequence->getPattern();
        $tokens = $this->tokenize($pattern,$sequence->getUpdatedAt());

        if($this->checkForReset($tokens))
        {
            $sequence->setCurrentNumber($initialValue ?? 0);
        }
        $nextNumber = $sequence->getNextNumber($initialValue ?? 0);
        $this->repository->flush();


        return $this->replace($tokens,$pattern,$nextNumber);
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
            $sequence->setPattern($pattern == null ? '{#}' : $pattern);
            $this->repository->add($sequence,false);
        }
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