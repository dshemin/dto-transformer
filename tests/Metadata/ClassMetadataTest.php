<?php

namespace Freedemster\DTO\Metadata;

use Freedemster\DTO\Tests\Helper\AccessorTrait;
use Freedemster\DTO\Tests\Helper\Fixture\DTOTestClass;
use Freedemster\DTO\Tests\Helper\Fixture\OriginalTestClass;

/**
 * Class ClassMetadataTest
 * @package Freedemster\DTO\Metadata
 */
class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{

    use AccessorTrait;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @return void
     */
    public function testSetOriginalClass()
    {
        self::assertEquals(OriginalTestClass::class, $this->get($this->metadata, 'originalClass'));

        $this->metadata->setOriginalClass('\stdClass');
        self::assertEquals('\stdClass', $this->get($this->metadata, 'originalClass'));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOClassMetadataException
     * @expectedExceptionMessage Can't find class by '\UnknownClass' name.
     *
     * @return void
     */
    public function testSetOriginalClassException()
    {
        $this->metadata->setOriginalClass('\UnknownClass');
    }

    /**
     * @return void
     */
    public function testGetOriginalClass()
    {
        self::assertEquals(OriginalTestClass::class, $this->metadata->getOriginalClass());

        $this->set($this->metadata, 'originalClass', '\stdClass');
        self::assertEquals('\stdClass', $this->metadata->getOriginalClass());
    }

    /**
     * @return void
     */
    public function testGetOriginalReflection()
    {
        $reflection = $this->metadata->getOriginalReflection();
        self::assertInstanceOf('\ReflectionClass', $reflection);
        self::assertEquals(OriginalTestClass::class, $reflection->getName());

        $this->metadata->setOriginalClass('stdClass');

        $reflection = $this->metadata->getOriginalReflection();
        self::assertInstanceOf('\ReflectionClass', $reflection);
        self::assertEquals('stdClass', $reflection->getName());
    }

    /**
     * @return void
     */
    public function testSetDTOClass()
    {
        self::assertEquals(DTOTestClass::class, $this->get($this->metadata, 'DTOClass'));

        $this->metadata->setDTOClass('\stdClass');
        self::assertEquals('\stdClass', $this->get($this->metadata, 'DTOClass'));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOClassMetadataException
     * @expectedExceptionMessage Can't find class by '\UnknownClass' name.
     *
     * @return void
     */
    public function testSetDTOClassException()
    {
        $this->metadata->setDTOClass('\UnknownClass');
    }

    /**
     * @return void
     */
    public function testGetDTOClass()
    {
        self::assertEquals(DTOTestClass::class, $this->get($this->metadata, 'DTOClass'));

        $this->set($this->metadata, 'DTOClass', '\stdClass');
        self::assertEquals('\stdClass', $this->metadata->getDTOClass());
    }

    /**
     * @return void
     */
    public function testAddProperty()
    {
        self::assertCount(0, $this->get($this->metadata, 'properties'));

        $this->metadata->addProperty('age');

        self::assertCount(1, $this->get($this->metadata, 'properties'));
    }

    /**
     * @return void
     */
    public function testGetProperties()
    {
        self::assertCount(0, $this->metadata->getProperties());

        $property = PropertyMetadata::create($this->metadata, 'age');
        $this->set($this->metadata, 'properties', [ $property ]);

        self::assertCount(1, $this->metadata->getProperties());
        self::assertContains($property, $this->metadata->getProperties());
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->metadata = ClassMetadata::create(
            OriginalTestClass::class,
            DTOTestClass::class
        );
    }

}
