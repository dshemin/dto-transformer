<?php

namespace Freedemster\DTO\Transformer;

use Freedemster\DTO\Metadata\ClassMetadata;
use Freedemster\DTO\Metadata\PropertyMetadata;
use Freedemster\DTO\Tests\Helper\AccessorTrait;
use Freedemster\DTO\Tests\Helper\Fixture\DTOTestClass;
use Freedemster\DTO\Tests\Helper\Fixture\OriginalTestClass;

/**
 * Class TransformerTest
 * @package Freedemster\DTO\Transformer
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    use AccessorTrait;

    /**
     * @var Transformer
     */
    private $transformer;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @return void
     */
    public function testObjectToDTO()
    {
        $object = new OriginalTestClass('John', 'Smith');
        $object->city = 'Denwer';
        $object->setPlainPassword('123456');

        /** @var DTOTestClass $dto */
        $dto = $this->transformer->objectToDTO($object, $this->metadata);

        self::assertInstanceOf(DTOTestClass::class, $dto);
        self::assertEquals(10, $dto->age);
        self::assertEquals('Denwer', $dto->cityName);
        self::assertEquals('John Smith', $dto->fullName);
        self::assertEquals($object->getBirthAt(), $dto->birthAt);
        self::assertEquals($object->getUserRole(), $dto->role);
        self::assertEquals(md5('123456'), $dto->password);
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOTransformException
     * @expectedExceptionMessage Can't transform 'stdClass' object, expects 'Freedemster\DTO\Tests\Helper\Fixture\OriginalTestClass'.
     *
     * @return void
     */
    public function testObjectToDTOException()
    {
        $object = (object) [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'age' => 10,
        ];
        $this->transformer->objectToDTO($object, $this->metadata);
    }

    /**
     * @return void
     */
    public function testDTOToObject()
    {
        $dto = new DTOTestClass();
        $dto->firstName = 'John';
        $dto->lastName = 'Smith';
        $dto->age = 40;
        $dto->role = 'admin';
        $dto->password = 'pass-test';

        /** @var OriginalTestClass $object */
        $object = $this->transformer->DTOToObject($dto, $this->metadata);
        self::assertInstanceOf(OriginalTestClass::class, $object);
        self::assertEquals('John', $object->getFirstName());
        self::assertEquals('Smith', $object->getLastName());
        self::assertEquals('John Smith', $object->getFullName());
        self::assertEquals(40, $this->get($object, 'age'));
        self::assertEquals('admin', $object->getUserRole());
        self::assertEquals(md5('pass-test'), $this->get($object, 'password'));
    }

    /**
     * @expectedException \Freedemster\DTO\Exceptions\DTOTransformException
     * @expectedExceptionMessage Can't transform 'stdClass' object, expects 'Freedemster\DTO\Tests\Helper\Fixture\DTOTestClass'.
     *
     * @return void
     */
    public function testDTOToObjectException()
    {
        $object = (object) [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'age' => 10,
        ];
        $this->transformer->DTOToObject($object, $this->metadata);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->transformer = new Transformer();
        $this->metadata = new ClassMetadata(
            OriginalTestClass::class,
            DTOTestClass::class
        );
        $this->metadata
            ->addProperty('firstName')
            ->setAccessType(PropertyMetadata::ACCESS_WRITE);
        $this->metadata
            ->addProperty('lastName')
            ->setAccessType(PropertyMetadata::ACCESS_WRITE);
        $this->metadata->addProperty('age');
        $this->metadata->addProperty('city', 'cityName');
        $this->metadata
            ->addProperty('getFullName', 'fullName')
            ->setAccessType(PropertyMetadata::ACCESS_READ);
        $this->metadata->addProperty('getBirthAt', 'birthAt')
            ->setAccessType(PropertyMetadata::ACCESS_READ);
        $this->metadata
            ->addProperty('role')
            ->setSetter('setAccessRole')
            ->setGetter('getUserRole');
        $this->metadata
            ->addProperty('password')
            ->setSetter('setPlainPassword');
    }

}
