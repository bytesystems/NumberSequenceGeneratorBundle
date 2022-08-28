<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NumberGeneratorTest extends KernelTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected function setUp():void
    {
        parent::setUp();
        $this->bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testGetNextNumberForNotExistingSequence()
    {
        $this->databaseTool->loadFixtures();

        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');

        $generatedNumber = $numberGenerator->getNextNumber('Key', null, null, 1000);
        $this->assertEquals(1001,$generatedNumber);
    }

    public function testTokenize()
    {
        $this->databaseTool->loadFixtures();
        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');
        $generatedNumber = $numberGenerator->getNextNumber('Key', null, "IV-{Y}{m}-{#|4}-ID", 1000);
        $expected = sprintf("IV-%s-1001-ID",date('Ym'));
        $this->assertEquals($expected,$generatedNumber);
        $generatedNumber = $numberGenerator->getNextNumber('Key2', null, "IV{y}-{#|6|y}", 1000);
        $expected = sprintf("IV%s-001001",date('y'));
        $this->assertEquals($expected,$generatedNumber);

    }
}
