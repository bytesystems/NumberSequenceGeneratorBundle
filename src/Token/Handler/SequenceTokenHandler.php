<?php


namespace Bytesystems\NumberGeneratorBundle\Token\Handler;


use Bytesystems\NumberGeneratorBundle\Token\Token;
use Bytesystems\NumberGeneratorBundle\Token\TokenHandlerInterface;

class SequenceTokenHandler implements TokenHandlerInterface
{
    protected $allowResetOn = [
        'y',
        'm',
        'w',
        'd',
        'h',
    ];


    public function handles(Token $token): bool
    {
        return $token->getIdentifier() === '#';
    }

    public function getValue(Token $token, $value)
    {
        if(!$this->handles($token))
        {
            throw new \InvalidArgumentException(sprintf("Invalid token for that handler. Token: %s", $token->getIdentifier()));
        }

        $params = $token->getParameters();
        $padding = $params[0] ?? 0;
        // Maybe throw overflow here?
        return sprintf("%0{$padding}d", $value);
    }

    public function requestsReset(Token $token): bool
    {
        $params = $token->getParameters();
        $reset = $params[1] ?? null;
        if(null != $reset)
        {
            $reset = strtolower((string) $reset);
            if(!in_array($reset,$this->allowResetOn)) {
                throw new \InvalidArgumentException(sprintf("Can't reset on period '%s'", $reset));
            }

            $old = '';
            $new = '';

            foreach ($this->allowResetOn as $item) {
                $old.=$token->getResetContext()->format($item);
                $new.=date($item);

                if($item == $reset)
                {
                    return $old != $new;
                }

            }
        }
        return false;
    }
}