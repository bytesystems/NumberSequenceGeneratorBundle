<?php


namespace Bytesystems\NumberGeneratorBundle\Token;

class TokenHandlerRegistry
{
    /**
     * @var array
     */
    protected $handlers = [];

    public function addHandler(TokenHandlerInterface $tokenHandler)
    {
        $this->handlers[] = $tokenHandler;
    }

    /**
     * @return TokenHandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}