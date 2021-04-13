<?php


namespace Bytesystems\NumberGeneratorBundle\Token\Handler;


use Bytesystems\NumberGeneratorBundle\Token\Token;
use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerInterface;

class DateTokenHandler implements TokenHandlerInterface
{
    protected $allowedFormatChars = [
        'd',
        'D',
        'm',
        'M',
        'y',
        'Y',
        'h',
        'H',
    ];

    public function handles(Token $token):bool
    {
        return in_array($token->getIdentifier(),$this->allowedFormatChars);
    }

    public function getValue(Token $token, $value)
    {
        if(!$this->handles($token))
        {
            throw new \InvalidArgumentException(sprintf("Invalid token for that handler. Token: %s", $token->getIdentifier()));
        }
        return date($token->getIdentifier());
    }

    public function requestsReset(Token $token): bool
    {
        return false;
    }
}