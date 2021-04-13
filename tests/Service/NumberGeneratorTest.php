<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Bytesystems\NumberGeneratorBundle\Repository\NumberSequenceRepository;
use Bytesystems\NumberGeneratorBundle\Service\NumberGenerator;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NumberGeneratorTest extends KernelTestCase
{

    use FixturesTrait;

    protected function setUp():void
    {
        parent::setUp();
        $this->bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

    }

    public function testGetNextNumberForNotExistingSequence()
    {
        $this->loadFixtures();

        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');

        $generatedNumber = $numberGenerator->getNextNumber('Key', null, null, 1000);
        $this->assertEquals(1001,$generatedNumber);
    }

    public function testTokenize()
    {
        $this->loadFixtures();
        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');
        $generatedNumber = $numberGenerator->getNextNumber('Key', null, "IV-{Y}{m}-{#|4}-ID", 1000);
        $expected = sprintf("IV-%s-1001-ID",date('Ym'));
        $this->assertEquals($expected,$generatedNumber);
        $generatedNumber = $numberGenerator->getNextNumber('Key2', null, "IV{y}-{#|6|y}", 1000);
        $expected = sprintf("IV%s-001001",date('y'));
        $this->assertEquals($expected,$generatedNumber);

    }
}
