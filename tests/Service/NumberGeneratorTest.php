<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\Service;

use Bytesystems\NumberGeneratorBundle\Attribute\Sequence;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NumberGeneratorTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;

    protected function setUp():void
    {
        $this->bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testGetNextNumberForNotExistingSequence()
    {
        $this->databaseTool->loadFixtures();

        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');

        $annotation = new Sequence(key: 'Key', init: 1000);
//        $annotation->key = 'Key';
//        $annotation->init = 1000;

        $generatedNumber = $numberGenerator->getNextNumber($annotation);
        $this->assertEquals(1001,$generatedNumber);
    }

    public function testTokenize()
    {
        $this->databaseTool->loadFixtures();
        $numberGenerator = $this->getContainer()->get('bytesystems_number_generator.service.number_generator');
        $annotation = new Sequence(key: 'Key', pattern: "IV-{Y}{m}-{#|4}-ID", init: 1000);
//        $annotation->key = 'Key';
//        $annotation->pattern = "IV-{Y}{m}-{#|4}-ID";
//        $annotation->init = 1000;
        $generatedNumber = $numberGenerator->getNextNumber($annotation);
        $expected = sprintf("IV-%s-1001-ID",date('Ym'));
        $this->assertEquals($expected,$generatedNumber);
        $annotation->key = 'Key2';
        $annotation->pattern = "IV{y}-{#|6|y}";
        $annotation->init = 1000;
        $generatedNumber = $numberGenerator->getNextNumber($annotation);
        $expected = sprintf("IV%s-001001",date('y'));
        $this->assertEquals($expected,$generatedNumber);

    }
}
