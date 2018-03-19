<?php
declare(strict_types=1);

/**
 * This file is part of the Reflection Factory library.
 * Copyright (c) 2018 Nikolas Lada.
 * @author Nikolas Lada <nikolas.lada@gmail.com>
 */

namespace NikolasLada\ReflectionFactory;

final class ReflectionFactory {

  /**
   * @param string $className
   * @param array $params
   * @return object
   */
  public function createFromArray(string $className, array $params) {
    $r = new \ReflectionClass($className);
    return $r->newInstanceArgs($params);
  }
  
  /**
   * @param string $className
   * @param mixed $params Pass the array type or a instance of a class that implements \ArrayAccess.
   * @param \Closure $beforeCreate
   * @param bool $isStrict
   * @return object
   * @throws InputNotValidException
   */
  public function createWithChecking(string $className, $params, \Closure $beforeCreate, $isStrict = FALSE) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $interfaces = $r->getInterfaces();
    if (array_key_exists('\Countable', $interfaces)) {
      if (count($required) !== count($params)) {
        throw new InputNotValidException;
      }
      
    }
    
    if (!array_key_exists('\ArrayAccess', $interfaces) || $isStrict) {
      $input = $this->checkStrictly($required, $params);
    } else {
      $this->check($required, $params);
      $input = $params;
    }
    
    $beforeCreate();
    
    return $r->newInstanceArgs($input);
  }
  
  /**
   * @param string $className
   * @param \stdClass $params
   * @param \Closure $beforeCreate
   * @return object
   * @throws InputNotValidException
   */
  public function createFromStdClassWithChecking(string $className, \stdClass $params, \Closure $beforeCreate) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $input = $this->checkStrictly($required, $params);
    $beforeCreate();
    
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
      } else {
        throw new InputNotValidException;
      }
      
    }
    
    return $input;
  }
  
  /**
   * @param array $required
   * @param mixed $params array or \ArrayAccess
   * @throws InputNotValidException
   */
  private function check(array $required, $params) {
    foreach ($required as $v) {
      /* @var $v \ReflectionParameter */
      $key = $v->getName();
      if (!isset($params[$key])) {
        throw new InputNotValidException;
      }
      
    }
  }

}
