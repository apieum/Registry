<?php
/**
 * File Constructor_CallableTest.php
 *
 * PHP version 5.2
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Callable.php
 *
 */
$constBase = str_replace('tests'.DIRECTORY_SEPARATOR, '', __DIR__);
require_once $constBase.'.php';
require_once $constBase.DIRECTORY_SEPARATOR.'Callable.php';
/**
 * Test class for Constructor_Callable.
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_CallableTest
 *
 */
class Constructor_CallableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Constructor_Callable
     */
    protected $object;
    protected $called;

    /**
     * Sets up a Constructor_Callable object with method 'methodConstructor'
     * as argument
     * 
     * @return null
     */
    protected function setUp()
    {
        $this->object = new Constructor_Callable(array($this, 'methodConstructor'));
        $this->called = false;
    }
    /**
     * Convenient mehtod used in fixture construction
     * 
     * @return array arguement passed to method
     */
    public function methodConstructor()
    {
        $this->called = true;
        return func_get_args();
    }
    /**
     * Convenient method used to make tests on build instance
     * 
     * @return string always 'value'
     */
    public function callableMethod()
    {
        return 'value';
    }
    /**
     * Callable 'buildInstance' call the first argument with the secon as parameters
     * 
     * @return @test
     */
    public function buildAnInstanceByCallingFirstArgumentWithSecondAsArrayParams()
    {
        $instance = $this->object->buildInstance('ucfirst', array('value'));
        $this->assertEquals('Value', $instance);
        $instance = $this->object->buildInstance(array($this, 'callableMethod'));
        $this->assertEquals('value', $instance);
    }
    /**
     * Test if method 'instanciate' call the appropriate method
     * 
     * @return @test
     */
    public function instanciateReturnCallWithGivenArgsResult()
    {
        $this->assertEquals(array(), $this->object->instanciate());
        $this->assertTrue($this->called, '"methodConstructor" not called.');
        $this->called = false;
        $expected = array('arg1', 'arg2');
        $this->assertEquals($expected, $this->object->instanciate('arg1', 'arg2'));
        $this->assertTrue($this->called, '"methodConstructor" not called.');
    }
    /**
     * Test if method 'instanciateArray' call the appropriate method
     * 
     * @return @test
     */
    public function instanciateArrayReturnCallResultWithGivenArrayAsArgs()
    {
        $this->assertEquals(array(), $this->object->instanciateArray());
        $this->assertTrue($this->called, '"methodConstructor" not called.');
        $this->called = false;
        $expected = array('arg1', 'arg2');
        $this->assertEquals($expected, $this->object->instanciateArray($expected));
        $this->assertTrue($this->called, '"methodConstructor" not called.');
    }
}
?>
