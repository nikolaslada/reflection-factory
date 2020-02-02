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

use \NikolasLada\ReflectionFactory\ReflectionFactory;

$params = new \stdClass;
$params->authorLink = 'https://nikolaslada.cz';
$params->authorName = 'Nikolas Lada';
$params->content = 'Content of the article.';
$params->created = '2018-03-20 12:00:00';
$params->id = 1;
$params->title = 'My first article';
$params->updated = '2018-03-20 12:01:13';

$beforeCreate = function() use ($params) {
  $params->created = new \DateTime($params->created);

  if (!\is_null($params->updated)) {
    $params->updated = new \DateTime($params->updated);
  }

  return $params;
};


$articleFromStdClass = ReflectionFactory::create(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $params,
    $beforeCreate
);

Assert::type(\NikolasLada\ReflectionFactory\Tests\Domain\Article::class, $articleFromStdClass);
