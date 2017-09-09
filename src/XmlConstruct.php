<?php

namespace Sausin\XmlConstruct;

use XMLWriter;
use BadFunctionCallException;

/**
 * Credit goes to the commenters on php.net for the basic structure of this.
 * @see http://php.net/manual/en/ref.xmlwriter.php
 */
class XmlConstruct extends XMLWriter
{
    protected $separator;

    /**
     * Constructor.
     *
     * @param  string $rootElementName
     * @param  string $separator
     */
    public function __construct(string $rootElementName, string $separator = '|')
    {
        $this->openMemory();
        $this->setIndent(true);
        $this->setIndentString('  ');
        $this->startDocument('1.0', 'UTF-8');

        $this->startElement($rootElementName);
    }

    /**
     * Construct elements and texts from an array. The array should contain an
     * attribute's name in index part and a attribute's text in value part.
     *
     * @param  array  $prmArray Contains values
     * @return XmlConstruct
     */
    public function fromArray(array $prmArray)
    {
        foreach ($prmArray as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key)) {
                    // numeric keys aren't allowed so we'll skip the key
                    $this->fromArray($val);
                } else {
                    $this->writeKey($key);

                    $this->fromArray($val);
                    $this->endElement();
                }
            } else {
                $this->setElement($key, $val);
            }
        }

        return $this;
    }

    /**
     * Return the content of a current xml document.
     *
     * @return string XML document
     */
    public function getDocument()
    {
        $this->endElement();
        $this->endDocument();

        return $this->outputMemory();
    }

    /**
     * Write a key.
     *
     * @param  string $key
     * @return void
     */
    protected function writeKey(string $key)
    {
        if (mb_strpos($key, $this->separator)) {
            $pieces = explode($this->separator, $key);

            // begin the element
            $this->startElement(array_shift($pieces));

            if (count($pieces) % 2 === 1) {
                throw new BadFunctionCallException('Invalid attribute pair at '.end($pieces));
            }

            // write the attributes
            foreach (array_chunk($pieces, 2) as list($attr, $val)) {
                $this->writeAttribute($attr, $val);
            }
        } else {
            // info($key);
            $this->startElement($key);
        }
    }

    /**
     * Set an element with a text to a current xml document.
     *
     * @param  string $name An element's name
     * @param  string $text An element's text
     * @return void
     */
    protected function setElement(string $name, string $text)
    {
        $this->writeKey($name);

        $this->text($text);

        $this->endElement();
    }
}
