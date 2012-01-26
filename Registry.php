<?php
/**
 * File Registry.php
 *
 * PHP version 5.2
 *
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Registry.php
 *
 */
require_once __DIR__.DIRECTORY_SEPARATOR.'Constructor.php';
/**
 * Registry helps to store and instanciate global vars.
 * You can set how vars are instanciated with constructors definitions,
 * and provide a way to replace singletons by storing unique instance of objects.
 *  
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Registry
 *
 */
class Registry
{
    protected static $properties   = array();
    protected static $constructors = array();
    /**
     * Set a static property, or override one if already exists
     * 
     * @param string $property the property name
     * @param mixed  $value    the property value
     * 
     * @return null
     */
    public static function set($property, $value)
    {
        self::$properties[$property] =& $value;
    }
    /**
     * return a static property or default if not exists
     *  
     * @param string $property the property name
     * @param mixed  $default  the default value to return if not exists
     * 
     * @return mixed the property value or default
     */
    public static function &get($property, $default=null)
    {
        if (self::has($property)) {
            $default =& self::$properties[$property];
        }
        return $default;
    }
    /**
     * Test whether a property is defined.
     * 
     * @param string $property the property name
     * 
     * @return bool
     */
    
    public static function has($property)
    {
        return isset(self::$properties[$property]);
    }
    /**
     * delete a property 
     * 
     * @param string $property the property name we want to remove
     * 
     * @return null
     */
    public static function del($property)
    {
        unset(self::$properties[$property]);
    }
    /**
     * define a constructor to build objects from an alias.
     * 
     * @param string $name        the alias used for the instance
     * @param mixed  $constructor class, or function name that construct an object
     * 
     *  @return null
     */
    public static function setConstructor($name, $constructor)
    {
        self::$constructors[$name] = Constructor::from($constructor);
    }
    /**
     * return a constructor from an alias
     * 
     * @param string $name the alias used for the instance
     * 
     * @return null
     */
    public static function getConstructor($name)
    {
        if (self::hasConstructor($name)) {
            return self::$constructors[$name];
        }
    }
    /**
     * Return whether a Constructor is set 
     * 
     * @param string $name the alias of the constructor
     * 
     * @return bool
     */
    public static function hasConstructor($name)
    {
        return isset(self::$constructors[$name]);
    }
    /**
     * Remove the Constructor with alias $name
     * 
     * @param string $name the alias of the constructor to remove
     * 
     * @return null
     */
    public static function delConstructor($name)
    {
        unset(self::$constructors[$name]);
    }
    /**
     * return the result of constructor $name unique instanciation. 
     * Set it if not exists with arguments passed after name.
     * 
     * @param string $name a property name (same as Constructor alias to create)
     * 
     * @return mixed
     */
    public static function instance($name)
    {
        if (self::has($name) === false) {
            $args = func_get_args();
            $name = array_shift($args);
            self::set($name, self::constructArray($name, $args));
        }
        return self::get($name);
    }
    /**
     * Instanciate the Constructor $name with given arguments
     * 
     * @param string $name constructor alias
     * 
     * @return mixed
     */
    public static function construct($name)
    {
        $args = func_get_args();
        return self::constructArray(array_shift($args), $args);
    }
    /**
     * Instanciate the Constructor $name with given arguments as array
     * 
     * @param string $name constructor alias
     * @param array  $args arguments passed to constructor
     * 
     * @return mixed
     */
    public static function constructArray($name, $args=array())
    {
        if (self::hasConstructor($name)) {
            return self::$constructors[$name]->instanciateArray($args);
        }
    }
}