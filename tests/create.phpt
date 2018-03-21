<?php
declare(strict_types=1);

/**
 * This file is part of the Reflection Factory library.
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
$params->updated = NULL;
$params->authorName = 'Nikolas Lada';
$params->authorLink = 'https://nikolaslada.cz';

$beforeCreate = function() use ($params) {
  $params->created = new \DateTime($params->created);
  
  return $params;
};


$articleFromAO = $reflectionFactory->create(
    '\NikolasLada\ReflectionFactory\Tests\Domain\Article',
    $params,
    $beforeCreate
);

Assert::type('\NikolasLada\ReflectionFactory\Tests\Domain\Article', $articleFromAO);


$paramsB = [];
$paramsB['id'] = 1;
$paramsB['title'] = 'My first article';
$paramsB['content'] = 'Content of the article.';
$paramsB['created'] = '2018-03-20 12:00:00';
$paramsB['updated'] = NULL;
$paramsB['authorName'] = 'Nikolas Lada';
$paramsB['authorLink'] = 'https://nikolaslada.cz';

$beforeCreate = function() use ($paramsB) {
  $paramsB['created'] = new \DateTime($paramsB['created']);
  
  return $paramsB;
};


$articleFromArray = $reflectionFactory->create(
    '\NikolasLada\ReflectionFactory\Tests\Domain\Article',
    $paramsB,
    $beforeCreate
);

Assert::type('\NikolasLada\ReflectionFactory\Tests\Domain\Article', $articleFromArray);
