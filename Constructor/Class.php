<?php
/**
 * File Constructor_Class.php
 *
 * PHP version 5.2
 *
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Class.php
 *
 */
/**
 * A Constructor that make objects of the class given by 'constructor'.
 *  
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Class
 *
 */
class Constructor_Class extends Constructor
{
    /**
     * call $constructor with $parameters as arguments
     * 
     * @param func  $constructor class name
     * @param array $parameters  list of parameters to pass to constructor
     * 
     * @return object an instance of the constructor
     */
    public function buildInstance($constructor, array $parameters=array())
    {
        $class = new ReflectionClass($constructor);
        if ($parameters !== array() && is_object($class->getConstructor())) {
            return $class->newInstanceArgs($parameters);
        } else {
            return $class->newInstance();
        }
    }
    
}