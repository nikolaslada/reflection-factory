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

/**
 * The createFromArray method depends on order items in array!
 */
$paramsB = [];
$paramsB['id'] = 1;
$paramsB['title'] = 'My first article';
$paramsB['content'] = 'Content of the article.';
$paramsB['created'] = '2018-03-20 12:00:00';
$paramsB['updated'] = '2018-03-20 12:01:13';
$paramsB['authorName'] = 'Nikolas Lada';
$paramsB['authorLink'] = 'https://nikolaslada.cz';

$paramsB['created'] = new \DateTime($paramsB['created']);

if (!\is_null($paramsB['updated'])) {
  $paramsB['updated'] = new \DateTime($paramsB['updated']);
}


$articleFromArray = $reflectionFactory->createFromArray(
    \NikolasLada\ReflectionFactory\Tests\Domain\Article::class,
    $paramsB
);

Assert::type(\NikolasLada\ReflectionFactory\Tests\Domain\Article::class, $articleFromArray);
