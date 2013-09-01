<?php

/**
 * Unit tests for the class MabeEnum_Enum
 *
 * @link http://github.com/marc-mabe/php-enum for the canonical source repository
 * @copyright Copyright (c) 2012 Marc Bennewitz
 * @license http://github.com/marc-mabe/php-enum/blob/master/LICENSE.txt New BSD License
 */
class MabeEnumTest_EnumTest extends PHPUnit_Framework_TestCase
{
    public function testEnumWithDefaultValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithDefaultValue();

        $this->assertSame(
            array(
                'ONE' => 1,
                'TWO' => 2,
            ),
            $enum->getConstants()
        );

        $this->assertSame(1, $enum->getValue());
        $this->assertSame('1', $enum->__toString());

        $this->assertSame('ONE', $enum->getName());
        $this->assertSame(0, $enum->getOrdinal());
    }

    public function testGetNameReturnsConstantNameOfCurrentValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithoutDefaultValue(MabeEnumTest_TestAsset_EnumWithoutDefaultValue::ONE);
        $this->assertSame('ONE', $enum->getName());
    }

    public function testToStringMagicMethodReturnsValueAsString()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithoutDefaultValue(MabeEnumTest_TestAsset_EnumWithoutDefaultValue::ONE);
        $this->assertSame('1', $enum->__toString());
    }

    public function testEnumWithNullAsDefaultValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithNullAsDefaultValue();

        $this->assertSame(array(
            'NONE' => null,
            'ONE'  => 1,
            'TWO'  => 2,
        ), $enum->getConstants());

        $this->assertNull($enum->getValue());
    }

    public function testEnumWithoutDefaultValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        new MabeEnumTest_TestAsset_EnumWithoutDefaultValue();
    }

    public function testEnumInheritance()
    {
        $enum = new MabeEnumTest_TestAsset_EnumInheritance(MabeEnumTest_TestAsset_EnumInheritance::ONE);
        $this->assertSame(array(
            'ONE'         => 1,
            'TWO'         => 2,
            'INHERITANCE' => 'Inheritance'
        ), $enum->getConstants());
        $this->assertSame(MabeEnumTest_TestAsset_EnumInheritance::ONE, $enum->getValue());
        $this->assertSame(0, $enum->getOrdinal());

        $enum = new MabeEnumTest_TestAsset_EnumInheritance(MabeEnumTest_TestAsset_EnumInheritance::INHERITANCE);
        $this->assertSame(MabeEnumTest_TestAsset_EnumInheritance::INHERITANCE, $enum->getValue());
        $this->assertSame(2, $enum->getOrdinal());
    }

    public function testConstructorStrictValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithoutDefaultValue(MabeEnumTest_TestAsset_EnumWithoutDefaultValue::ONE);
        $this->assertSame(1, $enum->getValue());
        $this->assertSame(0, $enum->getOrdinal());
    }

    public function testConstuctorNonStrictValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithoutDefaultValue((string)MabeEnumTest_TestAsset_EnumWithoutDefaultValue::TWO);
        $this->assertSame(2, $enum->getValue());
        $this->assertSame(1, $enum->getOrdinal());
    }

    public function testCallingGetOrdinalTwoTimesWillResultTheSameValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithoutDefaultValue(MabeEnumTest_TestAsset_EnumWithoutDefaultValue::TWO);
        $this->assertSame(1, $enum->getOrdinal());
        $this->assertSame(1, $enum->getOrdinal());
    }

    public function testGetOrdinalThrowsRuntimeExceptionOnUnknwonValue()
    {
        $enum = new MabeEnumTest_TestAsset_EnumWithDefaultValue();

        // change the protected value property to an unknwon value
        $reflectionValue = new ReflectionProperty($enum, 'value');
        $reflectionValue->setAccessible(true);
        $reflectionValue->setValue($enum, 'unknwonValue');

        $this->setExpectedException('RuntimeException');
        $enum->getOrdinal();
    }

    public function testInstantiateUsingMagicMethod()
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            $this->markTestSkipped("Instantiating using magic method doesn't work for PHP < 5.3");
        }

        $enum = MabeEnumTest_TestAsset_EnumInheritance::ONE();
        $this->assertInstanceOf('MabeEnumTest_TestAsset_EnumInheritance', $enum);
        $this->assertSame(MabeEnumTest_TestAsset_EnumInheritance::ONE, $enum->getValue());
    }

    public function testInstantiateUsingMagicMethodThrowsBadMethodCallException()
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            $this->markTestSkipped("Instantiating using magic method doesn't work for PHP < 5.3");
        }

        $this->setExpectedException('BadMethodCallException');
        MabeEnumTest_TestAsset_EnumInheritance::UNKNOWN();
    }
}
