<?php
declare(strict_types=1);

/**
 * This file is a part of the Reflection Factory library.
 * Copyright (c) 2018 Nikolas Lada.
 * @author Nikolas Lada <nikolas.lada@gmail.com>
 */

namespace NikolasLada\ReflectionFactory;

final class ReflectionFactory {

  /**
   * It depends on order of items in array!
   * @param string $className
   * @param array $params
   * @return object
   */
  public function createFromArray(string $className, array $params) {
    $r = new \ReflectionClass($className);
    return $r->newInstanceArgs($params);
  }
  
  /**
   * If you pass an array as $params, it depends on order of items!
   * @param string $className
   * @param mixed $params Pass the array type or a instance of a class that has got at least one public property.
   * @param \Closure $beforeCreate
   * @return object
   * @throws InputNotValidException
   */
  public function create(string $className, $params, \Closure $beforeCreate) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $interfaces = $r->getInterfaces();
    if (array_key_exists('\Countable', $interfaces)) {
      if (count($required) !== count($params)) {
        throw new InputNotValidException;
      }
      
    }
    
    $params = $beforeCreate();
    
    if (is_array($params)) {
      $this->check($required, $params);
      $input = $params;
    } else {
      $input = $this->checkStrictly($required, $params);
    }
    
    return $r->newInstanceArgs($input);
  }
  
  /**
   * @param string $className
   * @param \stdClass $params
   * @param \Closure $beforeCreate
   * @return object
   * @throws InputNotValidException
   */
  public function createFromStdClass(string $className, \stdClass $params, \Closure $beforeCreate) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $params = $beforeCreate();
    $input = $this->checkStrictly($required, $params);
    
    return $r->newInstanceArgs($input);
  }
  
  /**
   * @param array $required
   * @param object $params Pass a instance of a class that has got at least one public property.
   * @throws InputNotValidException
   * @return array
   */
  private function checkStrictly(array $required, $params): array {
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
   * @param array $required
   * @param array $params
   * @throws InputNotValidException
   */
  private function check(array $required, array $params) {
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
