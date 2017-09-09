<?php

namespace Sausin\XmlConstruct\Tests;

use JsonSerializable;
use Sausin\XmlConstruct\XmlConstruct;

// backward compatibility for phpunit
if (! class_exists('\PHPUnit\Framework\TestCase') &&
    class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class XmlConstructTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_can_generate_xml_from_a_plain_key_value_array()
    {
        $array = ['KEY1' => 'VALUE1', 'KEY2' => 'VALUE2'];

        $xmlGen = new XmlConstruct('ROOT');
        $output = $xmlGen->fromArray($array)->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1>VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }

    /** @test */
    public function it_can_generate_xml_from_an_array_with_numeric_indices()
    {
        $array = [['KEY1' => 'VALUE1', 'KEY2' => 'VALUE2']];

        $xmlGen = new XmlConstruct('ROOT');
        $output = $xmlGen->fromArray($array)->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1>VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }

    /** @test */
    public function it_can_generate_xml_with_attributes_from_an_array()
    {
        $array = [['KEY1|ATTR1|VAL1|ATTR2|VAL2' => 'VALUE1', 'KEY2' => 'VALUE2']];

        $xmlGen = new XmlConstruct('ROOT');
        $output = $xmlGen->fromArray($array)->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1 ATTR1="VAL1" ATTR2="VAL2">VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }

    /** @test */
    public function it_can_generate_xml_with_attributes_from_an_array_with_custom_separator()
    {
        $array = [['KEY1:ATTR1:VAL1:ATTR2:VAL2' => 'VALUE1', 'KEY2' => 'VALUE2']];

        $xmlGen = new XmlConstruct('ROOT', ':');
        $output = $xmlGen->fromArray($array)->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1 ATTR1="VAL1" ATTR2="VAL2">VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }

    /** @test */
    public function it_throws_an_error_if_incorrect_attribute_value_setup()
    {
        // array with missing value
        $array = [['KEY1:ATTR1:VAL1:ATTR2' => 'VALUE1', 'KEY2' => 'VALUE2']];

        $xmlGen = new XmlConstruct('ROOT', ':');

        $this->expectException('BadFunctionCallException');
        $output = $xmlGen->fromArray($array)->getDocument();
    }

    /** @test */
    public function it_throws_an_error_if_value_associated_is_incorrect()
    {
        // array with missing value
        $array = [['KEY1' => 'VALUE1', 'KEY2' => null]];

        $xmlGen = new XmlConstruct('ROOT', ':');

        $this->expectException('TypeError');
        $output = $xmlGen->fromArray($array)->getDocument();
    }

    /** @test */
    public function it_can_convert_a_json_string()
    {
        $str = '[{"KEY1:ATTR1:VAL1:ATTR2:VAL2":"VALUE1","KEY2":"VALUE2"}]';

        $xmlGen = new XmlConstruct('ROOT', ':');

        $output = $xmlGen->fromJson($str)->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1 ATTR1="VAL1" ATTR2="VAL2">VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }

    /** @test */
    public function it_can_convert_a_json_serializable_object()
    {
        $array = [['KEY1:ATTR1:VAL1:ATTR2:VAL2' => 'VALUE1', 'KEY2' => 'VALUE2']];

        $xmlGen = new XmlConstruct('ROOT', ':');

        $output = $xmlGen->fromJsonSerializable(new class($array) implements JsonSerializable {
            public function __construct(array $array)
            {
                $this->array = $array;
            }

            public function jsonSerialize()
            {
                return $this->array;
            }
        }
        )->getDocument();

        $compare = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
                    '<ROOT>'.PHP_EOL.
                    '  <KEY1 ATTR1="VAL1" ATTR2="VAL2">VALUE1</KEY1>'.PHP_EOL.
                    '  <KEY2>VALUE2</KEY2>'.PHP_EOL.
                    '</ROOT>'.PHP_EOL;

        $this->assertEquals($compare, $output);
    }
}
