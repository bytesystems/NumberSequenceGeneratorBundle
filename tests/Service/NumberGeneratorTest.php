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

    private $repository;
    private $em;

    protected function setUp():void
    {
        parent::setUp();
        $this->bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $this->repository = new NumberSequenceRepository(
            self::$kernel->getContainer()->get('doctrine'),
            NumberSequence::class
        );

        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

    }

//    public function testGetNextNumberForExistingSequence()
//    {
//
//    }

    public function testGetNextNumberForNotExistingSequence()
    {
        $this->loadFixtures();

        $numberGenerator = new NumberGenerator($this->repository,$this->em);

        $generatedNumber = $numberGenerator->getNextNumber('Key', null, null, 1000);
        $this->assertEquals(1001,$generatedNumber);
    }

//    public function testGetNextNumberForExistingSequenceWithSegment()
//    {
//
//    }
//
//    public function testGetNextNumberForNotExistingSequenceWithSegment()
//    {
//
//    }
}
