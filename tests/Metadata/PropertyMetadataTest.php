<?php

namespace Freedemster\DTO\Metadata;

use Freedemster\DTO\Tests\Helper\AccessorTrait;
use Freedemster\DTO\Tests\Helper\Fixture\DTOTestClass;
use Freedemster\DTO\Tests\Helper\Fixture\OriginalTestClass;

/**
 * Class PropertyMetadataTest
 * @package Freedemster\DTO\Metadata
 */
class PropertyMetadataTest extends \PHPUnit_Framework_TestCase
{

    use AccessorTrait;

    /**
     * @var ClassMetadataInterface
     */
    private $metadata;

    /**
     * @return void
     */
    public function testSetDTOName()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        self::assertEquals('age', $this->get($property, 'DTOName'));

        $property->setDTOName('newName');
        self::assertEquals('newName', $this->get($property, 'DTOName'));
    }

    /**
     * @return void
     */
    public function testGetDTOName()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        self::assertEquals('age', $property->getDTOName());

        $this->set($property, 'DTOName', 'newName');
        self::assertEquals('newName', $property->getDTOName());
    }

    /**
     * @return void
     */
    public function testSetAccessType()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        self::assertEquals(PropertyMetadata::ACCESS_RW, $this->get($property, 'accessType'));

        $property->setAccessType(PropertyMetadata::ACCESS_WRITE);
        self::assertEquals(PropertyMetadata::ACCESS_WRITE, $this->get($property, 'accessType'));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage Invalid access type, expects one of: ACCESS_RW, ACCESS_READ or ACCESS_WRITE.
     *
     * @return void
     */
    public function testSetAccessTypeException()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        $property->setAccessType('some');
    }

    /**
     * @return void
     */
    public function canRead()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        self::assertTrue($property->canRead());

        $property->setAccessType(PropertyMetadata::ACCESS_WRITE);
        self::assertFalse($property->canRead());

        $property->setAccessType(PropertyMetadata::ACCESS_READ);
        self::assertTrue($property->canRead());
    }

    /**
     * @return void
     */
    public function canWrite()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        self::assertTrue($property->canWrite());

        $property->setAccessType(PropertyMetadata::ACCESS_WRITE);
        self::assertTrue($property->canWrite());

        $property->setAccessType(PropertyMetadata::ACCESS_READ);
        self::assertFalse($property->canWrite());
    }

    /**
     * @return void
     */
    public function testSetGetter()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city');
        $getter = $this->get($property, 'getter')->bindTo($object, $object);
        self::assertEquals($object->city, $getter());

        $property->setGetter('firstName');
        $getter = $this->get($property, 'getter')->bindTo($object, $object);
        self::assertEquals($object->getFirstName(), $getter());

        $property->setGetter('getFirstName');
        $getter = $this->get($property, 'getter')->bindTo($object, $object);
        self::assertEquals($object->getFirstName(), $getter());
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage Expected string which represent property or method name.
     *
     * @return void
     */
    public function testSetGetterInvalidException()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        $property->setGetter(function () {
            return 'some';
        });
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage Can't find property or method with name 'some'.
     *
     * @return void
     */
    public function testSetGetterUnknownException()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        $property->setGetter('some');
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city');
        self::assertEquals($object->city, $property->get($object));

        $property->setGetter('firstName');
        self::assertEquals($object->getFirstName(), $property->get($object));

        $property->setGetter('getFirstName');
        self::assertEquals($object->getFirstName(), $property->get($object));

        $this->set($object, 'firstName', 'other first name');
        self::assertEquals('other first name', $property->get($object));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage This property has only write access.
     *
     * @return void
     */
    public function testGetException()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city')
            ->setAccessType(PropertyMetadata::ACCESS_WRITE);
        $property->get($object);
    }

    /**
     * @return void
     */
    public function testSetSetter()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city');
        $setter = $this->get($property, 'setter')->bindTo($object, $object);
        $setter('some');
        self::assertEquals('some', $object->city);

        $property->setSetter('firstName');
        $setter = $this->get($property, 'setter')->bindTo($object, $object);
        $setter('new first name');
        self::assertEquals('new first name', $object->getFirstName());
        self::assertEquals('some', $object->city);

        $property->setSetter('setFirstName');
        $setter = $this->get($property, 'setter')->bindTo($object, $object);
        $setter('other first name');
        self::assertEquals('other first name', $object->getFirstName());
        self::assertEquals('some', $object->city);
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage Expected string which represent property or method name.
     *
     * @return void
     */
    public function testSetSetterInvalidException()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        $property->setSetter(function () {
            return 'some';
        });
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage Can't find property or method with name 'some'.
     *
     * @return void
     */
    public function testSetSetterUnknownException()
    {
        $property = PropertyMetadata::create($this->metadata, 'age');
        $property->setSetter('some');
    }

    /**
     * @return void
     */
    public function testSet()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city');
        $property->set($object, 'another city');
        self::assertEquals('another city', $this->get($object, 'city'));

        $property->setSetter('firstName');
        $property->set($object, 'new first name');
        self::assertEquals('new first name', $this->get($object, 'firstName'));
        self::assertEquals('another city', $this->get($object, 'city'));

        $property->setSetter('setFirstName');
        $property->set($object, 'first name');
        self::assertEquals('first name', $this->get($object, 'firstName'));
        self::assertEquals('another city', $this->get($object, 'city'));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOPropertyMetadataException
     * @expectedExceptionMessage This property has only read access.
     *
     * @return void
     */
    public function testSetException()
    {
        $object = new OriginalTestClass('First', 'Last');

        $property = PropertyMetadata::create($this->metadata, 'city')
            ->setAccessType(PropertyMetadata::ACCESS_READ);
        $property->set($object, 'another value');
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->metadata = new ClassMetadata(
            OriginalTestClass::class,
            DTOTestClass::class
        );
    }
}
