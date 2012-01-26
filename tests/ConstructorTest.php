<?php
/**
 * File ConstructorTest.php
 *
 * PHP version 5.2
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor.php
 *
 */
require_once str_replace('tests', '', __DIR__).'Constructor.php';
/**
 * Test class for Constructor.
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     ConstructorTest
 *
 */
class ConstructorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Constructor
     */
    protected $object;

    /**
     * Create a 'Constructor' for class 'ArrayObject'
     * 
     * @return null
     */
    protected function setUp()
    {
        $this->object = new Constructor('ArrayObject');
    }
    /**
     * Test Factory with an object instance of 'Constructor'
     * 
     * @return @test
     */
    public function whenParamIsInstanceOfConstructorFromReturnItAsIs()
    {
        $this->assertSame($this->object, Constructor::from($this->object));
    }
    /**
     * Test Factory with a callable function 
     * 
     * @return @test
     */
    public function whenParamIsAFunctionFromReturnACallableConstructor()
    {
        $constructor = Constructor::from('ucfirst');
        $this->assertInstanceOf('Constructor_Callable', $constructor);
    }
    /**
     * Convenient method to test Factory with method
     * 
     * @return null
     */
    public function callableMethod()
    {
        
    }
    /**
     * Test Factory with a callable method
     * 
     * @return @test
     */
    public function whenParamIsAMethodFromReturnACallableConstructor()
    {
        $constructor = Constructor::from(array($this, 'callableMethod'));
        $this->assertInstanceOf('Constructor_Callable', $constructor);
    }
    /**
     * Test Factory with a class
     * 
     * @return @test
     */
    public function whenParamIsAClassFromReturnAClassConstructor()
    {
        $constructor = Constructor::from('ArrayObject');
        $this->assertInstanceOf('Constructor_Class', $constructor);
    }
    /**
     * Test Factory with a lazy constructor
     * 
     * @return @test
     */
    public function whenParamIsNotCallableAndNotAClassFromReturnASelfConstructor()
    {
        $constructor = Constructor::from('null');
        $this->assertInstanceOf('Constructor', $constructor);
    }
    /**
     * Try to get a parameter not set
     * 
     * @return @test
     */
    public function ifAParameterNotSetGetReturnNull()
    {
        $this->assertNull($this->object->getParameter(0));
    } 
    /**
     * Define a parameter from a 'Parameter' object
     * 
     * @return @test
     */
    public function canDefineAParameterFromAConstructorParameterInstance()
    {
        $parameter = new Constructor_Parameter(0);
        $this->object->defParameter($parameter);
        $this->assertSame($parameter, $this->object->getParameter(0));
    }
    /**
     * Define a parameter from a value
     * 
     * @return @test
     */
    public function canDefineAParameterFromAValue()
    {
        $this->object->defParameter('value');
        $parameter = $this->object->getParameter(0);
        $this->assertInstanceOf('Constructor_Parameter', $parameter);
        $this->assertEquals('value', $parameter->getValue());
    }
    /**
     * When a parameter is set by object, position is defined by object value
     * 
     * @return @test
     */
    public function positionAParameterFromAConstructorParameterInstancePosition()
    {
        $parameter = new Constructor_Parameter(10);
        $this->object->defParameter($parameter);
        $this->assertSame(array(10=>$parameter), $this->object->getParameters());
        $this->assertSame($parameter, $this->object->getParameter(10));
    }
    /**
     * in case parameter is set with a value, the optional second argument
     * define it position 
     * 
     * @return @test
     */
    public function positionAParameterFromAValueWithSecondArgument()
    {
        $this->object->defParameter('value1', 10);
        $this->assertEquals('value1', $this->object->getParameter(10)->getValue());
    }
    /**
     * When position not passed as argument and position 0 not used, 
     * parameter position is 0
     * 
     * @return @test
     */
    public function ifThereIsNoParamAtPosition0PositionParamAt0IfNoneGiven()
    {
        $this->object->defParameter('value1', 1);
        $this->object->defParameter('value0');
        $parameter = $this->object->getParameter(0);
        $this->assertEquals('value0', $parameter->getValue());
        $this->assertEquals(0, $parameter->getPosition());
    }
    /**
     * When there is a parameter at position 0 and you set a new parameter without
     * passing it's position, the parameter position is 1
     * 
     * @return @test
     */
    public function ifThereIsParamAtPosition0PositionParamAt1IfNoneGiven()
    {
        $this->object->defParameter('value0', 0);
        $this->object->defParameter('value2', 2);
        $this->object->defParameter('value1');
        $parameter = $this->object->getParameter(1);
        $this->assertEquals('value1', $parameter->getValue());
        $this->assertEquals(1, $parameter->getPosition());
    }
    /**
     * Build param return parameters passed as argument if none pre-defined
     * 
     * @return @test
     */
    public function ifNoParametersIsSetBuildParamsReturnArgumentsAsIs()
    {
        $this->assertEquals(array(), $this->object->buildParameters());
        $expected = array('param1', 'param2');
        $this->assertEquals($expected, $this->object->buildParameters($expected));
    }
    /**
     * Parameter must be a list, with indexes that follow each others.
     * In case that's not the case, build params throw an exception
     * 
     * @return @test
     */
    public function buildParamsThrowAnExceptionWhenAnArgumentIsMissing()
    {
        try {
            $this->object->buildParameters(array(1=>'param'));
        } catch (InvalidArgumentException $invalidExc) {
            $this->assertContains('0', $invalidExc->getMessage());
        }
        $this->assertTrue(isset($invalidExc), 'Exception not Thrown');
    }
    /**
     * If a parameter is set, build params return it's value
     * 
     * @return @test
     */
    public function whenAParameterIsSetBuildParamsReturnItsValue()
    {
        $this->object->defParameter('value');
        $this->assertEquals(array('value'), $this->object->buildParameters());
    }
    /**
     * Here we define a function as first parameter, than when we call build params
     * with 'value' as first parameter, param::getValue call function with it as
     * argument.
     * 
     * @return @test
     */
    public function whenAParamHasSameIndexAsAnArgumentBuildParamsPassItInGetValue()
    {
        $this->object->defParameter('ucfirst');
        $actual   = array('value');
        $expected = array('Value');
        $this->assertEquals($expected, $this->object->buildParameters($actual));
    }
    /**
     * Constructor is a Factory and a base class for 'Constructors' creation.
     * The default behaviour of instanciate is lazy and must be overriden, but 
     * in some case it can be usefull to just having the argument passed on  
     * class constructor to instanciate the object.
     * So we test this default behaviour.
     * 
     * @return @test
     */
    public function buildInstanceReturnFirstArgumentByDefault()
    {
        $this->assertEquals('null', $this->object->buildInstance('null'));
        $this->assertEquals('ArrayObject', $this->object->instanciate());
        $this->assertEquals('ArrayObject', $this->object->instanciateArray());
    }
}
?>
