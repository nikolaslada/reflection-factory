<?php
declare(strict_types=1);

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
   * @param array|\ArrayObject $params
   * @param \Closure $beforeCreateClosure
   * @return object
   * @throws \RuntimeException
   */
  public function createWithChecking(string $className, $params, \Closure $beforeCreateClosure) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    if (count($required) !== count($params)) {
      throw new \RuntimeException;
    }
    
    foreach ($required as $v) {
      if (!isset($params[$v])) {
        throw new \RuntimeException;
      }
      
    }
    
    $beforeCreateClosure();
    
    return $r->newInstanceArgs($params);
  }
  
  /**
   * @param string $className
   * @param \stdClass $params
   * @param \Closure $beforeCreateClosure
   * @return object
   * @throws \RuntimeException
   */
  public function createFromStdClassWithChecking(string $className, \stdClass $params, \Closure $beforeCreateClosure) {
    $r = new \ReflectionClass($className);
    $method = $r->getConstructor();
    $required = $method->getParameters();
    
    $input = [];
    foreach ($required as $v) {
      if (isset($params->$v)) {
        $input[$v] = $params->$v;
      } else {
        throw new \RuntimeException;
      }
      
    }
    
    $beforeCreateClosure();
    
    return $r->newInstanceArgs($input);
  }
  
}
