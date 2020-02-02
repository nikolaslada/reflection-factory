<?php
declare(strict_types=1);

/**
 * This file is a part of the Reflection Factory library.
 * Copyright (c) 2018 Nikolas Lada.
 * @author Nikolas Lada <nikolas.lada@gmail.com>
 */

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/ReflectionFactory.php';
require __DIR__ . '/../src/InputNotValidException.php';

require __DIR__ . '/../tests/domain/Article.php';

Tester\Environment::setup();

$reflectionFactory = new \NikolasLada\ReflectionFactory\ReflectionFactory;

$params = new \ArrayObject;
$params->id = 1;
$params->title = 'My first article';
$params->content = 'Content of the article.';
$params->created = '2018-03-20 12:00:00';
$params->updated = \null;
$params->authorName = 'Nikolas Lada';
$params->authorLink = 'https://nikolaslada.cz';

$beforeCreate = function() use ($params) {
  $params->created = new \DateTime($params->created);

  if (!\is_null($params->updated)) {
    $params->updated = new \DateTime($params->updated);
  }

  return $params;
};


$articleFromAO = $reflectionFactory->create(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $params,
    $beforeCreate
);

Assert::type(\NikolasLada\ReflectionFactory\Tests\Domain\Article::class, $articleFromAO);

/**
 * The create method with passed an array as $params depends on order items in array!
 */
$paramsB = [];
$paramsB['id'] = 1;
$paramsB['title'] = 'My first article';
$paramsB['content'] = 'Content of the article.';
$paramsB['created'] = '2018-03-20 12:00:00';
$paramsB['updated'] = \null;
$paramsB['authorName'] = 'Nikolas Lada';
$paramsB['authorLink'] = 'https://nikolaslada.cz';

$beforeCreate = function() use ($paramsB) {
  $paramsB['created'] = new \DateTime($paramsB['created']);
  
  return $paramsB;
};


$articleFromArray = $reflectionFactory->create(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $paramsB,
    $beforeCreate
);

Assert::type(\NikolasLada\ReflectionFactory\Tests\Domain\Article::class, $articleFromArray);
