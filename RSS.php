<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Martin Jansen <mj@php.net>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$
//

require_once 'XML_Parser/Parser.php';

/**
 * RSS parser class.
 *
 * This class is a parser for Resource Description Framework (RDF) Site
 * Summary (RSS) documents. For more information on RSS see the
 * website of the RSS working group (http://www.purl.org/rss/).
 *
 * @author Martin Jansen <mj@php.net>
 * @version $Revision$
 * @access  public
 */
class XML_RSS extends XML_Parser
{
    // {{{ properties

    /**
     * @var string
     */
    var $insideTag = '';

    /**
     * @var string
     */
    var $activeTag = '';

    /**
     * @var array
     */
    var $channel = array();

    /**
     * @var array
     */
    var $items = array();

    /**
     * @var array
     */
    var $item = array();

    /**
     * @var array
     */
    var $image = array();

    /**
     * @var array
     */
    var $textinput = array();
    
    /**
     * @var array
     */
    var $textinputs = array();

    // }}}
    // {{{ Constructor

    /**
     * Constructor
     *
     * @access public
     * @param mixed File pointer or name of the RDF file.
     * @return void
     */
    function XML_RSS($handle = '')
    {
        $this->XML_Parser();

        if (@is_resource($handle)) {
            $this->setInput($handle);
        } elseif ($handle != '') {
            $this->setInputFile($handle);
        } else {
            $this->raiseError('No filename passed.');
        }
    }

    // }}}
    // {{{ startHandler()

    /**
     * Start element handler for XML parser
     *
     * @access private
     * @param  object XML parser object
     * @param  string XML element
     * @param  array  Attributes of XML tag
     * @return void
     */
    function startHandler($parser, $element, $attribs)
    {
        switch ($element) {
            case 'CHANNEL':
            case 'ITEM':
            case 'IMAGE':
            case 'TEXTINPUT':
                $this->insideTag = $element;
                break;

            default:
                $this->activeTag = $element;
        }
    }

    // }}}
    // {{{ endHandler()

    /**
     * End element handler for XML parser
     *
     * If the end of <item>, <channel>, <image> or <textinput>
     * is reached, this function updates the structure array
     * $this->struct[] and adds the field "type" to this array,
     * that defines the type of the current field.
     *
     * @access private
     * @param  object XML parser object
     * @param  string
     * @return void
     */
    function endHandler($parser, $element)
    {
        if ($element == $this->insideTag) {
            $this->insideTag = '';
            $this->struct[] = array_merge(array('type' => strtolower($element)),
                                          $this->last);
        }

        if ($element == 'ITEM') {
            $this->items[] = $this->item;
            $this->item = '';
        }

        if ($element == 'IMAGE') {
            $this->images[] = $this->image;
            $this->image = '';
        }

        if ($element == 'TEXTINPUT') {
            $this->textinputs = $this->textinput;
            $this->textinput = '';
        }

        $this->activeTag = '';
    }

    // }}}
    // {{{ cdataHandler()

    /**
     * Handler for character data
     *
     * @access private
     * @param  object XML parser object
     * @param  string CDATA
     * @return void
     */
    function cdataHandler($parser, $cdata)
    {
        switch ($this->insideTag) {

            // Grab general channel information
            case 'CHANNEL':
                switch ($this->activeTag) {
                    case 'TITLE':
                    case 'LINK':
                    case 'DESCRIPTION':
                    case 'IMAGE':
                    case 'ITEMS':
                    case 'TEXTINPUT':
                        $this->_add('channel', strtolower($this->activeTag),
                                    $cdata);
                        break;
                }
                break;

            // Grab item information
            case 'ITEM':
                switch ($this->activeTag) {
                    case 'TITLE':
                    case 'LINK':
                    case 'DESCRIPTION':
                        $this->_add('item', strtolower($this->activeTag),
                                    $cdata);
                        break;
                }
                break;

            // Grab image information
            case 'IMAGE':
                switch ($this->activeTag) {
                    case 'TITLE':
                    case 'URL':
                    case 'LINK':
                        $this->_add('image', strtolower($this->activeTag),
                                    $cdata);
                        break;
                }
                break;

            // Grab image information
            case 'TEXTINPUT':
                switch ($this->activeTag) {
                    case 'TITLE':
                    case 'DESCRIPTION':
                    case 'NAME':
                    case 'LINK':
                        $this->_add('textinput', strtolower($this->activeTag),
                                    $cdata);
                        break;
                }
                break;
        }      
    }

    // }}}
    // {{{ defaultHandler()

    /**
     * Default handler for XML parser
     *
     * @access private
     * @param  object XML parser object
     * @param  string CDATA
     * @return void
     */
    function defaultHandler($parser, $cdata)
    {
        return;
    }

    // }}}
    // {{{ _add()

    /**
     * Add element to internal result sets
     *
     * @access private
     * @param  string Name of the result set
     * @param  string Fieldname
     * @param  string Value
     * @return void
     * @see    cdataHandler
     */
    function _add($type, $field, $value)
    {
        if (empty($this->{$type}) || empty($this->{$type}[$field])) {
            $this->{$type}[$field] = $value;
        } else {
            $this->{$type}[$field] .= $value;
        }

        $this->last = $this->{$type};
    }

    // }}}
    // {{{ getStructure()

    /**
     * Get complete structure of RSS file
     *
     * @access public
     * @return array
     */
    function getStructure()
    {
        return (array)$this->struct;
    }

    // }}}
    // {{{ getchannelInfo()

    /**
     * Get general information about current channel
     *
     * This function returns an array containing the information
     * that has been extracted from the <channel>-tag while parsing
     * the RSS file.
     *
     * @access public
     * @return array
     */
    function getChannelInfo()
    {
        return (array)$this->channel;
    }

    // }}}
    // {{{ getItems()

    /**
     * Get items from RSS file
     *
     * This function returns an array containing the set of items
     * that are provided by the RSS file.
     *
     * @access public
     * @return array
     */
    function getItems()
    {
        return (array)$this->items;
    }

    // }}}
    // {{{ getImages()

    /**
     * Get images from RSS file
     *
     * This function returns an array containing the set of images
     * that are provided by the RSS file.
     *
     * @access public
     * @return array
     */
    function getImages()
    {
        return (array)$this->images;
    }

    // }}}
    // {{{ getTextinputs()

    /**
     * Get text input fields from RSS file
     *
     * @access public
     * @return array
     */
    function getTextinputs()
    {
        return (array)$this->textinputs;
    }

    // }}}

}
?>
