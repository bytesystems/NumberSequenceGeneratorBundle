<?php


namespace Bytesystems\NumberGeneratorBundle\Token;


class Token
{
    /**
     * @var string
     */
    protected $identifier;
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param string $replace
     * @param \DateTime $resetContext
     */
    public function __construct($tokenInfo, protected $replace, protected $resetContext)
    {
        $this->identifier = array_shift($tokenInfo);
        $this->parameters = $tokenInfo;

    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return \DateTime
     */
    public function getResetContext(): \DateTime
    {
        return $this->resetContext;
    }

    /**
     * @return string
     */
    public function getReplace(): string
    {
        return $this->replace;
    }

}