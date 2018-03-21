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

$params = new \stdClass;
$params->authorLink = 'https://nikolaslada.cz';
$params->authorName = 'Nikolas Lada';
$params->content = 'Content of the article.';
$params->created = '2018-03-20 12:00:00';
$params->id = 1;
$params->title = 'My first article';
$params->updated = NULL;

$beforeCreate = function() use ($params) {
  $params->created = new \DateTime($params->created);
  
  return $params;
};


$articleFromStdClass = $reflectionFactory->create(
    '\NikolasLada\ReflectionFactory\Tests\Domain\Article',
    $params,
    $beforeCreate
);

Assert::type('\NikolasLada\ReflectionFactory\Tests\Domain\Article', $articleFromStdClass);
