Data Transfer Object Transformer
================================

A data transfer object (DTO) is an object that carries data between processes.
[more about ...](https://en.wikipedia.org/wiki/Data_transfer_object)

This package provide basic classes for holding class and property metadata's and
class for transformation between DTO and original object.

Example
-------

For example we have some original object which we want to convert in two others
structures:

```php
class Original {
    private $firstName;
    private $lastName;
    private $age

    public function getFullName()
    {
        return $this->firstName .' '. $this->lastName;
    }
    
    public function getBirthAt()
    {
        return date_create()
            ->modify('- '. $this->age .' years')
            ->format('Y-m-d');
    }
}
```

First Data Transfer Object class which used for creating new Original class
instances (for example we got it from one of REST API method):

```php
class FirstDTO {
    public $firstName;
    public $lastName;
    public $age;
}
```

Second Data Transfer Object class which used for representing information about
Original instance (for example serialized and sent as response from api method):

```php
class SecondDTO {
    public $fullName;
    public $birthAt;
}
```

So we need to create two different metadata's for each Data Transfer Object's:

```php
$firstMetadata = new ClassMetadata(
    Original::class,
    FirstDTO::class
);

$firstMetadata
    ->addProperty('firstName')
    ->setAccessType(PropertyMetadata::ACCESS_WRITE);
$firstMetadata
    ->addProperty('lastName')
    ->setAccessType(PropertyMetadata::ACCESS_WRITE);
$firstMetadata
    ->addProperty('age')
    ->setAccessType(PropertyMetadata::ACCESS_WRITE);

$secondMetadata
    ->addProperty('fullName')
    ->setGetter('getFullName')
    ->setAccessType(PropertyMetadat::ACCESS_READ);
$secondMetadata
    ->addProperty('birthAt')
    ->setGetter('getBirthAt')
    ->setAccessType(PropertyMetadat::ACCESS_READ);
```

And now we can transform object:

```php
$transformer = new Transformer();

$dto = new FirstDTO();
$dto->firstName = 'John';
$dto->lastName = 'Smith';
$dto->age = 32;

$object = $transformer->DTOToObject($dto, $firstMetadata);
$newDto = $transformer->ObjectToDTO($object, $secondMetadata);
```