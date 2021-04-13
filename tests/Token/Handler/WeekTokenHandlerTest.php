<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Token\Handler;

use Bytesystems\NumberGeneratorBundle\Token\Handler\WeekTokenHandler;
use Bytesystems\NumberGeneratorBundle\Token\Token;
use PHPUnit\Framework\TestCase;

class WeekTokenHandlerTest extends TestCase
{

    protected $dateTimeToken;
    protected $weekToken;
    protected $sequenceToken;
    protected $handler;

    protected function setUp():void
    {
        parent::setUp();

        $this->handler = new WeekTokenHandler();
        $this->weekToken = new Token(['w'],'{w}',new \DateTime());
        $this->dateTimeToken = new Token(['y'],'{y}',new \DateTime());
        $this->sequenceToken = new Token(['#'],'{#|6}',new \DateTime());
    }

    public function testHandles()
    {
        $this->assertTrue($this->handler->handles($this->weekToken),'Should handle the {w}-Token');
        $this->assertFalse($this->handler->handles($this->dateTimeToken),'Should not handle the {y}-Token');
        $this->assertFalse($this->handler->handles($this->sequenceToken), 'Should not handle the {#}-Token');
    }

    public function testGetValue()
    {
        $this->assertEquals(sprintf("%00d", date('W')),$this->handler->getValue($this->weekToken,1));
        $this->expectException(\InvalidArgumentException::class);
        $this->handler->getValue($this->sequenceToken,1);

    }
}
