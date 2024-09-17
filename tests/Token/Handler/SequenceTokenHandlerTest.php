<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Token\Handler;

use Bytesystems\NumberGeneratorBundle\Token\Handler\SequenceTokenHandler;
use Bytesystems\NumberGeneratorBundle\Token\Token;
use PHPUnit\Framework\TestCase;

class SequenceTokenHandlerTest extends TestCase
{
    protected $dateTimeToken;
    protected $sequenceToken;
    protected $handler;

    protected function setUp():void
    {
        $this->handler = new SequenceTokenHandler();
        $this->dateTimeToken = new Token(['y'],'{y}',new \DateTime());
        $this->sequenceToken = new Token(['#',6],'{#|6}',new \DateTime());
    }
    public function testGetValue()
    {
        $this->assertEquals('000314',$this->handler->getValue($this->sequenceToken,314));
        $this->assertEquals('31415926535',$this->handler->getValue($this->sequenceToken,31415926535));
        $this->expectException(\InvalidArgumentException::class);
        $this->handler->getValue($this->dateTimeToken,1);
    }

    public function testRequestsReset()
    {
        $token = new Token(['#',6,'H'],'{#|6|H}',new \DateTime());
        $this->assertFalse($this->handler->requestsReset($token),'Check for reset on hour, but last date is now.');


        $token = new Token(['#',6,'Y'],'{#|6|Y}',(new \DateTime())->modify('-1 year'));
        $this->assertTrue($this->handler->requestsReset($token),'Check for reset on year');

        $token = new Token(['#',6,'d'],'{#|6|d}',(new \DateTime())->modify('-20 days'));
        $this->assertTrue($this->handler->requestsReset($token),'Check for request on day');

        // The hour part does not change in the next test, so it should fall back on the next greater reset period (y->m->w->d->h)

        $token = new Token(['#',6,'h'],'{#|6|h}',(new \DateTime())->modify('-24 hour'));
        $this->assertTrue($this->handler->requestsReset($token),'Check for request on hour.');

        $token = new Token(['#',6,'h'],'{#|6|h}',(new \DateTime())->modify('-1 hour'));
        $this->assertTrue($this->handler->requestsReset($token),'Check for request on hour.');

        $token = new Token(['#',6,'W'],'{#|6|W}',new \DateTime());
        $this->assertFalse($this->handler->requestsReset($token),'Check for request on week');

        $token = new Token(['#',6,'W'],'{#|6|W}',(new \DateTime())->modify('-20 days'));
        $this->assertTrue($this->handler->requestsReset($token),'Check for request on week.');
    }

    public function testHandles()
    {
        $this->assertTrue($this->handler->handles($this->sequenceToken));
        $this->assertFalse($this->handler->handles($this->dateTimeToken));
    }
}
