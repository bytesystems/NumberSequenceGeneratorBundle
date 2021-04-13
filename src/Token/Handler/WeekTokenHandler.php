<?php


namespace Bytesystems\NumberGeneratorBundle\Token\Handler;


use Bytesystems\NumberGeneratorBundle\Token\Token;
use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerInterface;

class WeekTokenHandler implements TokenHandlerInterface
{
    public function handles(Token $token): bool
    {
        return $token->getIdentifier() === 'w' || $token->getIdentifier() === 'W';
    }

    public function getValue(Token $token, $value)
    {
        if(!$this->handles($token))
        {
            throw new \InvalidArgumentException(sprintf("Invalid token for that handler. Token: %s", $token->getIdentifier()));
        }
        return sprintf('%00d',date('W'));
    }

    public function requestsReset(Token $token): bool
    {
        return false;
    }
}