<?php
/**
 * File Constructor_ClassTest.php
 *
 * PHP version 5.2
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_Class.php
 *
 */
$constBase = str_replace('tests'.DIRECTORY_SEPARATOR, '', __DIR__);
require_once $constBase.'.php';
require_once $constBase.DIRECTORY_SEPARATOR.'Class.php';
/**
 * Test class for Constructor_Class.
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Constructor_ClassTest
 *
 */
class Constructor_ClassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Constructor_Class
     */
    protected $object;

    /**
     * Sets up a Constructor_Class for 'ArrayObject'
     * 
     * @return null
     */
    protected function setUp()
    {
        $this->object = new Constructor_Class('ArrayObject');
    }
    /**
     * Build instance create an object from the first geiven argument with
     * others arguments as parameters.
     * 
     * @return @test
     */
    public function buildAnInstanceByCreatingAnObjectInstanceOfFirstArgument()
    {
        $instance = $this->object->buildInstance('StdClass');
        $this->assertInstanceOf('StdClass', $instance);
        $params   = array('value0', 'value1');
        $expected = new ArrayObject($params);
        $instance = $this->object->buildInstance('ArrayObject', array($params));
        $this->assertEquals($expected, $instance);
    }
    /**
     * Test if 'instanciate' call 'buildInstance' with constructor class
     * and given parameters.
     * 
     * @return @test
     */
    public function instanciateReturnAClassCreatedWithGivenArgs()
    {
        $this->assertEquals(new ArrayObject(), $this->object->instanciate());
        $params = array('arg1', 'arg2');
        $expected = new ArrayObject($params);
        $this->assertEquals($expected, $this->object->instanciate($params));
    }
    /**
     * Test if 'instanciateArray' call 'buildInstance' with constructor class
     * and given parameters as array.
     * 
     * @return @test
     */
    public function instanciateArrayReturnAClassCreatedWithGivenArrayAsArgs()
    {
        $this->assertEquals(new ArrayObject(), $this->object->instanciateArray());
        $params = array('arg1', 'arg2');
        $expected = new ArrayObject($params);
        $this->assertEquals(
            $expected, $this->object->instanciateArray(array($params))
        );
    }
}
?>
