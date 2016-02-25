<?php
namespace LZCompressor;

class LZContext
{
    /**
     * @var array
     */
    public $dictionary = [];

    /**
     * @var array
     */
    public $dictionaryToCreate = [];

    /**
     * @var string
     */
    public $c = '';

    /**
     * @var string
     */
    public $wc = '';

    /**
     * @var string
     */
    public $w = '';

    /**
     * @var int
     */
    public $enlargeIn = 2;

    /**
     * @var int
     */
    public $dictSize = 3;

    /**
     * @var int
     */
    public $numBits = 2;

    /**
     * @var LZData
     */
    public $data;

    function __construct()
    {
        $this->data = new LZData;
    }
}
