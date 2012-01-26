<?php
/**
 * File Constructor.php
 *
 * PHP version 5.2
 *
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor.php
 *
 */
Constructor::includeConstructor('Parameter');
/**
 * Base class for constructors.
 * Provide a Factory to construct 'Constructor' objects 
 * and convenients methods to describe vars instanciations and parameters.
 *  
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor
 *
 */
class Constructor
{
    protected $constructor;
    protected $parameters = array();
    /**
     * Constructor
     * 
     * @param mixed $constructor depends on child
     */
    public function __construct($constructor)
    {
        $this->constructor = $constructor;
    }
    /**
     * Return the next position in an indexed array
     * 
     * @param array $array an array
     * 
     * @return int
     */
    protected function nextPosition($array)
    {
        if (count($array) === 0) {
            return 0;
        }
        $existing  = array_keys($array);
        $positions = range(0, max($existing) + 1);
        return array_shift(array_diff($positions, $existing));
    }
    /**
     * create an instance within Constructor properties and given parameters
     * 
     * @return object
     */
    public function instanciate()
    {
        return $this->instanciateArray(func_get_args());
    }
    /**
     * create an instance from parameters array
     * 
     * @param array $parameters parameters to merge with predefined ones.
     * 
     * @return object
     */
    public function instanciateArray(array $parameters=array())
    {
        $parameters = $this->buildParameters($parameters);
        return $this->buildInstance($this->constructor, $parameters);
    }
    /**
     * build the instance with parameters : should be overriden by child
     * 
     * @param mixed $constructor class, function or method use to instanciate var 
     * @param array $parameters  list of parameters to pass to constructor
     * 
     * @return mixed an instance from the constructor
     */
    public function buildInstance($constructor, array $parameters=array())
    {
        return $constructor;
    }
    /**
     * Build parameters from $parameters by returning an ordered array of values
     * 
     * @param array $parameters array of mixed parameters (object or values)
     * 
     * @return array
     */
    public function buildParameters(array $parameters=array())
    {
        $parameters = array_replace($this->parameters, $parameters);
        foreach ($this->parameters as $param) {
            $position = $param->getPosition();
            $parameters[$position] = $param->getValue($parameters[$position]);
        }
        $nextPos = $this->nextPosition($parameters);
        if ($nextPos !== count($parameters)) {
            $msg = sprintf('Missing argument at position "%d"', $nextPos);
            throw new InvalidArgumentException($msg);
        }
        return $parameters;
    }
    /**
     * Define a parameter from Constructor_Parameter object or value and default
     * 
     * @param mixed $parameter Constructor_Parameter or parameter value
     * @param int   $position  position of the parameter
     * @param mixed $default   default value of the parameter
     * 
     * @return object $this for chaining
     */
    public function defParameter($parameter, $position=null, $default=null)
    {
        if ($parameter instanceof Constructor_Parameter) {
            $position  = $parameter->getPosition();
        } else {
            if (is_null($position)) {
                $position = $this->nextPosition($this->parameters);
            }
            $parameter = new Constructor_Parameter($position, $parameter, $default);
        }
        $this->parameters[$position] = $parameter;
        return $this;
    }
    /**
     * Return all defined parameters
     * 
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    /**
     * Return the parameter defined at position $position
     * 
     * @param int $position the position of the parameter
     * 
     * @return Constructor_Parameter
     */
    public function getParameter($position)
    {
        if (isset($this->parameters[$position])) {
            return $this->parameters[$position];
        }
    }
    /**
     * Factory method that return a "constructor" object within $constParams
     * 
     * @param mixed $constParams used to find wich type of constructor it is
     * 
     * @return object
     */
    final public static function from($constParams)
    {
        if ($constParams instanceof Constructor) {
            return $constParams;
        } elseif (is_callable($constParams)) {
            self::includeConstructor('Callable');
            return new Constructor_Callable($constParams);
        } elseif (class_exists($constParams, true)) {
            self::includeConstructor('Class');
            return new Constructor_Class($constParams);
        }
        return new self($constParams);
    }
    /**
     * Include a file needed for a constructor element 
     * 
     * @param string $name constructor element to include
     * 
     * @return null
     */
    final public static function includeConstructor($name)
    {
        $arPath = array(__DIR__, 'Constructor', ucfirst($name).'.php');
        include_once implode(DIRECTORY_SEPARATOR, $arPath); 
    }
}