<?php


namespace Bytesystems\NumberGeneratorBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Bytesystems\NumberGeneratorBundle\Repository\NumberSequenceRepository;


/**
 * Class NumberSequence
 * @package Bytesystems\NumberGeneratorBundle\Entity
 */
#[ORM\Entity(repositoryClass: NumberSequenceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'bytesystems_number_sequence')]
#[UniqueConstraint(name: 'sequence_unique', columns: ['sequence', 'segment'])]
class NumberSequence
{
    const DEFAULT_INIT = 0;

    /**
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    protected $id;


    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, name: 'sequence')]
    protected $key;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $segment;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 60, nullable: true)]
    protected $pattern ='{#}';

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    protected $currentNumber = self::DEFAULT_INIT;

    /**
     * @return int
     */
    public function getNextNumber(?int $initialValue = 0): int
    {
        $currentValue = max($this->currentNumber, $initialValue ?? 0);
        $this->currentNumber = $currentValue +1;
        return $this->currentNumber;
    }

    /**
     * @return int
     */
    public function getCurrentNumber(): int
    {
        return $this->currentNumber;
    }

    /**
     * @param int $currentNumber
     * @return NumberSequence
     */
    public function setCurrentNumber(int $currentNumber): NumberSequence
    {
        $this->currentNumber = $currentNumber;
        return $this;
    }

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime')]
    protected $updatedAt;

    public function __clone()
    {
        if ($this->getId()) {
            $this->id = null;
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return NumberSequence
     */
    public function setKey(string $key): NumberSequence
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSegment(): ?string
    {
        return $this->segment;
    }

    /**
     * @param string|null $segment
     * @return NumberSequence
     */
    public function setSegment(?string $segment): NumberSequence
    {
        $this->segment = $segment;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string|null $pattern
     * @return NumberSequence
     */
    public function setPattern(?string $pattern): NumberSequence
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return NumberSequence
     */
    public function setUpdatedAt(DateTime $updatedAt): NumberSequence
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function beforeSaving() {
        $this->updatedAt = new \DateTime('now');
    }
}