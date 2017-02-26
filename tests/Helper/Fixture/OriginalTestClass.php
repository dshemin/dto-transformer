<?php

namespace Freedemster\DTO\Tests\Helper\Fixture;

/**
 * Class OriginalTestClass
 * Class for testing transformation.
 *
 * @package Freedemster\DTO\Tests\Helper\Fixture
 */
class OriginalTestClass
{

    private $firstName;

    private $lastName;

    private $age = 10;

    public $city = 'City';

    private $role = 'user';

    protected $password;

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setAccessRole($role)
    {
        $this->role = $role;
    }

    public function getUserRole()
    {
        return $this->role;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setPlainPassword($password)
    {
        return $this->password = md5($password);
    }

    public function getBirthAt()
    {
        return date_create()->modify('- '. $this->age .' years')->format('Y-m-d');
    }
}
