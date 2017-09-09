# Xml generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sausin/xml-construct.svg?style=flat-square)](https://packagist.org/packages/sausin/xml-construct)
[![Build Status](https://img.shields.io/travis/sausin/xml-construct/master.svg?style=flat-square)](https://travis-ci.org/sausin/xml-construct)
[![Quality Score](https://img.shields.io/scrutinizer/g/sausin/xml-construct.svg?style=flat-square)](https://scrutinizer-ci.com/g/sausin/xml-construct)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/sausin/xml-construct.svg?style=flat-square)](https://scrutinizer-ci.com/g/sausin/xml-construct)
[![StyleCI](https://styleci.io/repos/102949349/shield?branch=master)](https://styleci.io/repos/102949349)
[![Total Downloads](https://img.shields.io/packagist/dt/sausin/xml-construct.svg?style=flat-square)](https://packagist.org/packages/sausin/xml-construct)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)

A useful class to generate valid XML from a PHP array.

## Installation

Run the following command in your project to get the class:

```
composer require sausin/xml-construct
```

## Usage with normal arrays

Usage is simple

### Verbose way:
```php
$xmlGen = new XmlConstruct('ROOT')

$string = $xmlGen->fromArray($array)->getDocument();
```
where `$array` is the PHP array from which you need the XML to be generated.

### Quick:
```php
(new XmlConstruct('ROOT'))->fromArray($f)->getDocument();
```
returns the XML string.

In both the above examples, `ROOT` is the root of the XML (i.e. the first element).

## Usage with arrays when attributes are needed in XML

If used like this:
```
$array = ['KEY|ATTR|VAL' => 'VALUE'];

return (new XmlConstruct('ROOT'))->fromArray($f)->getDocument();
```

It will result in the following XML
```xml
<?xml version="1.0" encoding="UTF-8"?>
<ROOT>
  <KEY ATTR="VAL">VALUE</KEY>
<ROOT>
```

You can add as many attributes as you like and they will all be added to the element. Neat!

## Credits

Initial inputs to the class were taken from [php user contributed notes](http://php.net/manual/en/ref.xmlwriter.php)