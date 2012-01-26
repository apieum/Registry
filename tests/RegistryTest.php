<?php
/**
 * File RegistryTest.php
 *
 * PHP version 5.2
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     Registry.php
 *
 */
require_once str_replace('tests', '', __DIR__).'Registry.php';
/**
 * Test class for Registry.
 *
 * @category Tests
 * @package  Registry
 * @author   Gregory Salvan <gregory.salvan@apieum.com>
 * @license  GPL v.2
 * @link     RegistryTest
 *
 */
class RegistryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set and Get vars
     * 
     * @return @test
     */
    public function canSetAndGetProperties()
    {
        Registry::set('property1', 'value');
        $this->assertEquals('value', Registry::get('property1', false));
    }
    /**
     * Get can return default property (passed as second argument)
     * 
     * @return @test
     */
    public function getReturnDefaultIfPropertyNotExists()
    {
        $this->assertFalse(Registry::get('property2', false));
    }
    /**
     * Set and Get uses reference. 
     * This test verify if it can be used to modify strored vars.
     * 
     * @return @test
     */
    public function whenSettingTheReturnOfGetValueCanGloballyChange()
    {
        $value = 'value';
        Registry::set('property1', &$value);
        $value1 =& Registry::get('property1');
        $value1 = 'value1';
        $this->assertEquals('value1', $value);
        $this->assertEquals('value1', Registry::get('property1'));
        
    }
    /**
     * Tests whether properties exists in registry
     * 
     * @return @test
     */
    public function canKnowIfRegistryHasAProperty()
    {
        $this->assertTrue(Registry::has('property1'));
        $this->assertFalse(Registry::has('property2'));
    }
    /**
     * Test if we can remove a property
     * 
     * @return @test
     */
    public function canRemoveAProperty()
    {
        $this->assertTrue(Registry::has('property1'));
        Registry::del('property1');
        $this->assertFalse(Registry::has('property1'));
        // delete a property that not exists to be sure it doesn't make errors
        Registry::del('property1');
        $this->assertFalse(Registry::has('property1'));
    }
    /**
     * Try to use a constructor that not exists
     * 
     * @return @test
     */
    public function constructReturnNullIfNoConstructorIsSet()
    {
        $this->assertNull(Registry::construct('Registry test'));
        $this->assertNull(Registry::constructArray('Registry test'));
    }
    /**
     * Set a constructor of type 'class' and instanciate it without arguments
     * 
     * @return @test
     */
    public function canSetAConstructorWithAClassNameAndCreateObjectWithoutArguments()
    {
        Registry::setConstructor('Registry test', 'ArrayObject');
        $this->assertInstanceOf('ArrayObject', Registry::construct('Registry test'));
    }
    /**
     * Verify if we can test the presence of a constructor
     * 
     * @return @test
     */
    public function canKnowIfAConstructorIsSet()
    {
        $this->assertTrue(Registry::hasConstructor('Registry test'));
        $this->assertFalse(Registry::hasConstructor('constructor'));
    }
    /**
     * Test if we can unset a constructor
     * 
     * @return @test
     */
    public function canUnsetAConstructor()
    {
        $this->assertTrue(Registry::hasConstructor('Registry test'));
        Registry::delConstructor('Registry test');
        $this->assertFalse(Registry::hasConstructor('Registry test'));
    }
    /**
     * Set a constructor that require arguments at instanciation
     * 
     * @return @test
     */
    public function canSetAConstructorWithAClassNameAndCreateObjectWithArguments()
    {
        Registry::setConstructor('Registry test', 'ArrayObject');
        $args   = array(array('prop'=>'val'), ArrayObject::ARRAY_AS_PROPS);
        $object = Registry::constructArray('Registry test', $args);
        $this->assertInstanceOf('ArrayObject', $object);
        $this->assertEquals('val', $object->prop);
    }
    /**
     * Test if we can get a constructor if set otherwise return must be null
     * 
     * @return @test
     */
    public function canGetAConstructorIfSetOtherwiseGetNull()
    {
        $constructor = Registry::getConstructor('Registry test');
        $this->assertInstanceOf('Constructor', $constructor);
        $this->assertEquals(new ArrayObject(), $constructor->instanciate());
        Registry::delConstructor('Registry test');
        $this->assertNull(Registry::getConstructor('Registry test'));
    }
    /**
     * If no constructor is defined but a property is set,
     *  instance return the property value
     * 
     * @return @test
     */
    public function instanceReturnRegistryPropertyIfSet()
    {
        Registry::set('property1', 'value');
        $this->assertEquals('value', Registry::instance('property1'));
    }
    /**
     * If property and constructor not exists, instance must return null
     * 
     * @return @test
     */
    public function instanceReturnNullIfRegistryPropertyAndConstructorNotSet()
    {
        Registry::del('property1');
        $this->assertNull(Registry::instance('property1'));
    }
    /**
     * Test if can really have one instance, by calling 'instance'
     * of same property 2 times
     * 
     * @return @test
     */
    public function instanceStoreAndReturnConstructorInstanciation()
    {
        $name = 'Registry test';
        $arg1 = array('prop'=>'val0');
        Registry::setConstructor($name, 'ArrayObject');
        $inst = Registry::instance($name, $arg1, ArrayObject::ARRAY_AS_PROPS);
        $this->assertSame(Registry::get($name), $inst);
        $this->assertEquals('val0', $inst->prop);
        $arg1['prop'] = 'val1';
        $inst = Registry::instance($name, $arg1, ArrayObject::ARRAY_AS_PROPS);
        $this->assertEquals('val0', $inst->prop);
    }
}
?>
