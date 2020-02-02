<?php
declare(strict_types=1);

/**
 * This file is a part of the Reflection Factory library.
 * Copyright (c) 2018, 2020 Nikolas Lada.
 * @author Nikolas Lada <nikolas.lada@gmail.com>
 */

namespace NikolasLada\ReflectionFactory;

class ReflectionFactory {

  /**
   * It depends on order of items in array!
   * @return object
   */
  public static function createFromArray(string $className, array $params) {
    $r = new \ReflectionClass($className);
    return $r->newInstanceArgs($params);
  }
  
  /**
   * If you pass an array as $params, it depends on order of items!
   * @return object
   * @throws InputNotValidException
   */
  public static function create(string $className, $params, \Closure $beforeCreate) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    $interfaces = $r->getInterfaces();

    if (array_key_exists(\Countable::class, $interfaces))
    {
      $countRequired = count($required);
      $countParams = count($params);

      if ($countRequired !== $countParams)
      {
        throw new InputNotValidException(
            "Different number of parameters! Class $className requires $countRequired parameters. Passed $countParams parameters."
        );
      }
      
    }
    
    $params = $beforeCreate();
    
    if (is_array($params)) {
      self::check($required, $params);
      $input = $params;
    } else {
      $input = self::checkStrictly($required, $params);
    }
    
    return $r->newInstanceArgs($input);
  }
  
  /**
   * @return object
   * @throws InputNotValidException
   */
  public static function createFromStdClass(string $className, \stdClass $params, \Closure $beforeCreate) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $params = $beforeCreate();
    $input = self::checkStrictly($required, $params);
    
    return $r->newInstanceArgs($input);
  }
  
  /**
   * @param object $params Pass a instance of a class that has got at least one public property.
   * @throws InputNotValidException
   */
  private static function checkStrictly(array $required, $params): array {
    $input = [];
    foreach ($required as $v) {
      /* @var $v \ReflectionParameter */
      $key = $v->getName();

      if (isset($params->$key)) {
        $input[] = $params->$key;
      } elseif (\is_null($params->$key)) {
        $input[] = NULL;
      } else {
        throw new InputNotValidException($key);
      }
    }
    
    return $input;
  }
  
  /**
   * @throws InputNotValidException
   */
  private static function check(array $required, array $params) {
    foreach ($required as $v) {
      /* @var $v \ReflectionParameter */
      $key = $v->getName();
      if (!isset($params[$key])) {
        if (!\is_null($params[$key])) {
          throw new InputNotValidException($key);
        }
      }
      
    }
  }

}
