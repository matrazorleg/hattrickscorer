<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 *          Telesphore wrote this file.  As long as you retain this notice you
 *          can do whatever you want with this stuff. If we meet some day, and you think
 *          this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Xml;

class Base
{
    /**
     * @var \DOMDocument
     */
    protected $xml;
    protected $xmlText;

    /**
     * Return XML data
     *
     * @param boolean $asObject
     * @param string $onlyNode only used if $asObject is false
     * @return \DOMDocument|string
     */
    public function getXml($asObject = true, $onlyNode = null)
    {
        if ($this->xml === null) {
            $this->xml = new \DOMDocument('1.0', 'UTF-8');
            $this->xml->loadXML($this->xmlText);
        }
        if ($asObject == true) {
            return $this->xml;
        }
        return $this->xml->saveXML($onlyNode);
    }
}
