<?php
/**
 * File Constructor_ParameterTest.php
 *
 * PHP version 5.2
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Parameter.php
 *
 */
require_once str_replace('tests', '', __DIR__).DIRECTORY_SEPARATOR.'Parameter.php';
/**
 * Test class for Constructor_Parameter.
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Parameter
 *
 */
class Constructor_ParameterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Constructor_Parameter
     */
    protected $object;

    /**
     * Sets up Constructor_Parameter with 10 as argument
     * 
     * @return null
     */
    protected function setUp()
    {
        $this->object = new Constructor_Parameter(10);
    }
    /**
     * Verify if position is well given at construction
     * 
     * @return @test
     */
    public function aParameterHasAPositionDefinedAtConstruction()
    {
        $this->assertEquals(10, $this->object->getPosition());
    }
    /**
     * Test first if param value is null (because not set in construction)
     * then set value and get it
     * 
     * @return @test
     */
    public function aParameterHasAValue()
    {
        $this->assertNull($this->object->getValue());
        $this->object->setValue('value');
        $this->assertEquals('value', $this->object->getValue());
    }
    /**
     * Test if parameter value can be unset
     * 
     * @return @test
     */
    public function canUnsetAParameterValue()
    {
        $this->object->setValue('value');
        $this->assertEquals('value', $this->object->getValue());
        $this->object->unsetValue();
        $this->assertNull($this->object->getValue());        
        $this->object->setValue('value');
        $this->assertEquals('value', $this->object->getValue());
    }
    /**
     * Test parameter default value setter and getter
     * 
     * @return @test
     */
    public function aParameterCanHaveADefaultValue()
    {
        $this->object->setDefault('value');
        $this->assertEquals('value', $this->object->getValue());
    }
    /**
     * Try to unset parameter default value
     * 
     * @return @test
     */
    public function canUnsetParameterDefaultValue()
    {
        $this->object->setDefault('value');
        $this->assertEquals('value', $this->object->getValue());
        $this->object->unsetDefault();
        $this->assertNull($this->object->getValue());
    }
    /**
     * Test whether parameter value can be a callback function without arguments
     * 
     * @return @test
     */
    public function aParameterValueCanBeSetByACallbackFunctionWithoutParams()
    {
        $function = create_function('', 'return "value";');
        $this->object->setCallback($function);
        $this->assertEquals('value', $this->object->getValue());
    }
    /**
     * Test wheter paramater value can be given by calling a callback with arguments
     * 
     * @return @test
     */
    public function aParameterValueCanBeSetByACallbackFunctionWithParams()
    {
        $function = create_function('$param', 'return ucfirst($param);');
        $this->object->setCallback($function);
        $this->assertEquals('Value', $this->object->getValue('value'));
    }
    /**
     * Unset callback function that get the value of a parameter
     * 
     * @return @test
     */
    public function canUnsetCallbackFunction()
    {
        $function = create_function('', 'return "value";');
        $this->object->setCallback($function);
        $this->assertEquals('value', $this->object->getValue());
        $this->object->unsetCallback();
        $this->assertNull($this->object->getValue());
    }
    /**
     * Verify that callback is called only if value not set
     * 
     * @return @test
     */
    public function callbackIsCalledIfValueNotAlreadySet()
    {
        $function = create_function('', 'return "value";');
        $this->object->setCallback($function);
        $this->assertEquals('value', $this->object->getValue());
        $this->object->setValue('value1');
        $this->assertEquals('value1', $this->object->getValue());
    }
    /**
     * Verify if default value is returned only if callback and value not set
     * 
     * @return @test
     */
    public function defaultValueIsReturnedIfValueAndCallbackNotSet()
    {
        $this->object->setDefault('value');
        $this->assertEquals('value', $this->object->getValue());
        $function = create_function('', 'return "value1";');
        $this->object->setCallback($function);
        $this->assertEquals('value1', $this->object->getValue());
        $this->object->unsetCallback();
        $this->object->setValue('value2');
        $this->assertEquals('value2', $this->object->getValue());
    }
    
    /**
     * Paramaeter position is readonly.
     * To move a parameter you must clone it with a new position.
     * 
     * @return @test
     */
    public function canCloneParameterToOtherPosition()
    {
        $this->assertEquals(10, $this->object->getPosition());
        $clone = $this->object->cloneTo(1);
        $this->assertEquals(1, $clone->getPosition());
    }
    /**
     * Test that if we can pass value and default value to constructor
     * 
     * @return @test
     */
    public function canConstructParameterWithValueOrDefault()
    {
        $param = new Constructor_Parameter(1, 'value', 'default');
        $this->assertEquals('value', $param->getValue());
        $param = new Constructor_Parameter(1, null, 'default');
        $this->assertEquals('default', $param->getValue());
    }
    /**
     * Test if we can set a callback as value at construction
     * 
     * @return @test
     */
    public function valuePassedToConstructorCanBeACallback()
    {
        $function = create_function('', 'return "value";');
        $param = new Constructor_Parameter(1, $function);
        $this->assertEquals('value', $param->getValue());
    }

}
?>
