# Reflection factory - repeat your code less!
The factory class which loads parameters and injects them into constructors by reflection.

## Installation
```bash
composer require nikolas-lada/reflection-factory
```
It requires PHP version 7.1+.

## Usage
For \stdClass:
```php
use \NikolasLada\ReflectionFactory\ReflectionFactory;

$data = new \stdClass;

/**
 * no matter of property order
 */
$data->created = '2018-03-20 12:00:00';
$data->id = 1;
$data->updated = \null;

$beforeCreate = function() use ($data) {
  $data->created = new \DateTime($data->created);

  if (!\is_null($data->updated)) {
    $data->updated = new \DateTime($data->updated);
  }

  return $data;
};

$article = ReflectionFactory::createFromStdClass(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $data,
    $beforeCreate
);

// or

$article = ReflectionFactory::create(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $data,
    $beforeCreate
);
```

For array:
```php
use \NikolasLada\ReflectionFactory\ReflectionFactory;

$data = [];

/**
 * The createFromArray method depends on order items in array!
 */
$data['id'] = 1;
$data['created'] = '2018-03-20 12:00:00';
$data['updated'] = '2018-03-20 12:01:13';

$data['created'] = new \DateTime($data['created']);

if (!\is_null($data['updated'])) {
  $data['updated'] = new \DateTime($data['updated']);
}

$article = ReflectionFactory::createFromArray(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $data
);

// or

$beforeCreate = function() use ($data) {
  $data['created'] = new \DateTime($data['created']);
  
  return $data;
};

$article = ReflectionFactory::create(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $data,
    $beforeCreate
);
```

## License

The Reflection factory library is open-sourced software licensed under:
- [GNU GPL 2.0](https://opensource.org/licenses/GPL-2.0),
- [GNU GPL 3.0](https://opensource.org/licenses/GPL-3.0),
- [GNU LGPL 3.0](https://opensource.org/licenses/LGPL-3.0) and
- [MIT license](https://opensource.org/licenses/MIT).