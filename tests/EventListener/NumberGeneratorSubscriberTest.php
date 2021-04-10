<?php

namespace Bytesystems\NumberGeneratorBundle\Tests\EventListener;

use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Bytesystems\NumberGeneratorBundle\Repository\NumberSequenceRepository;
use Bytesystems\NumberGeneratorBundle\Tests\Entity\Foo;
use DateTime;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NumberGeneratorSubscriberTest extends KernelTestCase
{
    use FixturesTrait;

    private $repository;

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

    }


    public function testPrePersist()
    {
        $this->loadFixtures();

        $object = new Foo();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($object);
        $em->flush();

        $results = $em->getRepository(Foo::class)->findAll();
        $this->assertCount(1,$results);

        $results = $this->repository->findAll();

        $this->assertCount(3,$results);


    }

    public function testBarSequence()
    {
        $this->loadFixtures();

        $object = new Foo();

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($object);
        $em->flush();

        $barSequence = $this->repository->findOneBy(['key' => 'bar']);
        $this->assertInstanceOf(NumberSequence::class,$barSequence);
        $this->assertEquals(1001,$barSequence->getCurrent());
    }

    public function testBarSequenceForMultipleEntities()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        for($i=0;$i<10;$i++)
        {
            $object = new Foo();
            $em->persist($object);
            $em->flush();
        }

        $barSequence = $this->repository->findOneBy(['key' => 'bar']);
        $this->assertInstanceOf(NumberSequence::class,$barSequence);
        $this->assertEquals(1011,$barSequence->getCurrent());
    }

    public function testBazSequence()
    {
        $this->loadFixtures();

        $object = new Foo();

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($object);
        $em->flush();

        $barSequence = $this->repository->findOneBy(['key' => 'baz']);
        $this->assertInstanceOf(NumberSequence::class,$barSequence);
        $this->assertEquals(1,$barSequence->getCurrent());
    }

    public function testBazSequenceWithSegment()
    {
        $this->loadFixtures();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $bazSequence = new NumberSequence();
        $bazSequence->setKey('baz');
        $bazSequence->setSegment('id');
        $bazSequence->setCurrent(241);
        $bazSequence->setUpdatedAt(new DateTime());

        $em->persist($bazSequence);
        $em->flush();

        $object = new Foo();
        $em->persist($object);
        $em->flush();

        $sequence = $this->repository->findOneBy(['key' => 'baz','segment' => 'id']);
        $this->assertInstanceOf(NumberSequence::class,$sequence);
        $this->assertEquals('id',$sequence->getSegment());
        $this->assertEquals(242,$sequence->getCurrent());

        // Make sure the bar sequence was generated.

        $sequence = $this->repository->findOneBy(['key' => 'bar']);
        $this->assertInstanceOf(NumberSequence::class,$sequence);
        $this->assertEquals(1001,$sequence->getCurrent());
    }

    public function testPersistResolvedSegmentNotConfigured()
    {
        $this->loadFixtures();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $bazSequence = new NumberSequence();
        $bazSequence->setKey('foo');
        $bazSequence->setSegment('thudValue');
        $bazSequence->setCurrent(149);
        $bazSequence->setUpdatedAt(new DateTime());

        $em->persist($bazSequence);
        $em->flush();


        $object = new Foo();
        $em->persist($object);
        $em->flush();

        $sequence = $this->repository->findOneBy(['key' => 'foo']);
        $this->assertInstanceOf(NumberSequence::class,$sequence);
        $this->assertEquals(150,$sequence->getCurrent());
        $this->assertEquals('thudValue',$sequence->getSegment());
    }

    /**
     * If the resolved Segment is not configured, the Generator should fall back to the base sequence with segment = null
     */
    public function testPersistResolvedSegmentConfigured()
    {


        $this->loadFixtures();

        $object = new Foo();

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($object);
        $em->flush();

        $sequence = $this->repository->findOneBy(['key' => 'foo']);
        $this->assertInstanceOf(NumberSequence::class,$sequence);
        $this->assertEquals(1,$sequence->getCurrent());
        $this->assertEquals(null,$sequence->getSegment());



    }
}
