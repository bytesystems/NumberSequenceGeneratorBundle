<?php


namespace Bytesystems\NumberGeneratorBundle\Token;


interface TokenHandlerInterface
{

    public function handles(Token $token):bool;
    public function getValue(Token $token, $value);
    public function requestsReset(Token $token):bool;
}