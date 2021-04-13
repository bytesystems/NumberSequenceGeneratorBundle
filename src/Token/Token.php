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
     * @var string
     */
    protected $replace;
    /**
     * @var \DateTime
     */
    protected $resetContext;

    public function __construct($tokenInfo, $replace, $resetContext)
    {
        $this->identifier = array_shift($tokenInfo);
        $this->parameters = $tokenInfo;
        $this->replace = $replace;
        $this->resetContext = $resetContext;

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