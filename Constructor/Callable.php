<?php
/**
 * File Constructor_Callable.php
 *
 * PHP version 5.2
 *
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Callable.php
 *
 */
/**
 * A Constructor that call a function or a method to instanciate a var.
 *  
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Callable
 *
 */
class Constructor_Callable extends Constructor
{
    /**
     * call $constructor with $parameters as arguments
     * 
     * @param func  $constructor callable function or method
     * @param array $parameters  list of parameters to pass to constructor
     * 
     * @return mixed an instance from the constructor
     */
    public function buildInstance($constructor, array $parameters=array())
    {
        return call_user_func_array($constructor, $parameters);
    }
}