<?php
/**
 * File Constructor_Parameter.php
 *
 * PHP version 5.2
 *
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Parameter.php
 *
 */
/**
 * Represent parameters sent to constructors in order to instanciate vars.
 *  
 * @category Classes
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Parameter
 *
 */
class Constructor_Parameter
{
    protected $position;
    protected $value;
    protected $callback;
    protected $default;
    /**
     * Constructor
     * 
     * @param int   $position position of the parameter in function
     * @param mixed $value    value of the parameter
     * @param mixed $default  default value if value is null
     */
    public function __construct($position, $value=null, $default=null)
    {
        $this->position = (int) $position;
        $this->setCallback($value);
        if (!is_null($value) && !isset($this->callback)) {
            $this->setValue($value);
        }
        $this->default = $default;
    }
    /**
     * Return the position of the parameter
     * 
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * Set the value of the parameter
     *  
     * @param mixed $value parameter value
     * 
     * @return null
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * unset value of parameter
     * 
     * @return null
     */
    public function unsetValue()
    {
        unset($this->value);
    }
    /**
     * Set Default value, returned if neither value or callback is set
     * 
     * @param mixed $value default value
     * 
     * @return null
     */
    public function setDefault($value)
    {
        $this->default = $value;
    }
    /**
     * Set default value to null
     *  
     * @return null
     */
    public function unsetDefault()
    {
        $this->default = null;
    }
    /**
     * Set a callback function to return value, called only if value not set
     * 
     * @param function $callback a function name or method: array('object', 'method')
     * 
     * @return null
     */
    public function setCallback($callback)
    {
        if (is_callable($callback)) {
            $this->callback = $callback;
        }
    }
    /**
     * Remove callback function  
     *
     * @return null
     */
    public function unsetCallback()
    {
        unset($this->callback);
    }
    /**
     * Return the value, or the result of callback if not set or default
     * 
     * @return mixed
     */
    public function getValue()
    {
        if (isset($this->value)) {
            return $this->value;
        } elseif (isset($this->callback)) {
            return call_user_func_array($this->callback, func_get_args());
        } else {
            return $this->default;
        }
    }
    /**
     * Clone this object and move the clone to position $position
     * 
     * @param int $position the position of the clone
     * 
     * @return object a clone of this
     */
    public function cloneTo($position)
    {
        $clone = clone $this;
        $clone->position = (int) $position;
        return $clone;
    }
}