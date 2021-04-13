<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Token\Handler;

use Bytesystems\NumberGeneratorBundle\Token\Handler\DateTokenHandler;
use Bytesystems\NumberGeneratorBundle\Token\Token;
use Cassandra\Date;
use PHPUnit\Framework\TestCase;

class DateTokenHandlerTest extends TestCase
{
    protected $dateTimeToken;
    protected $sequenceToken;
    protected $handler;

    protected function setUp():void
    {
        parent::setUp();

        $this->handler = new DateTokenHandler();
        $this->dateTimeToken = new Token(['y'],'{y}',new \DateTime());
        $this->sequenceToken = new Token(['#'],'{#|6}',new \DateTime());
    }



    public function testGetValue()
    {
        $this->assertEquals(date('y'),$this->handler->getValue($this->dateTimeToken,1));
        $this->expectException(\InvalidArgumentException::class);
        $this->handler->getValue($this->sequenceToken,1);

    }

    public function testHandles()
    {
       $this->assertTrue($this->handler->handles($this->dateTimeToken));
       $this->assertFalse($this->handler->handles($this->sequenceToken));
    }



    public function testAllTokensHandled()
    {
        $allowedFormatChars = [
            'd',
            'D',
            'm',
            'M',
            'y',
            'Y',
            'h',
            'H',
        ];

        $today = new \DateTime();

        foreach ($allowedFormatChars as $tokenChar) {

            $token = new Token([$tokenChar],sprintf('{%s}',$tokenChar),$today);
            $this->assertTrue($this->handler->handles($token));
            $this->assertEquals(date($tokenChar),$this->handler->getValue($token,1),sprintf("Handling key: %s, %s",$tokenChar, $token->getIdentifier()));
        }

    }
}
