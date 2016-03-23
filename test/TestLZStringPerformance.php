<?php

use LZCompressor\LZString as LZString;

class TestLZStringPerformance extends PHPUnit_Framework_TestCase
{

    private static $domain = array(
        1 => array(0, 127),
        2 => array(128, 2014),
        3 => array(2048, 65535)
    );

    /**
     * @throws Exception
     */
    public function testCompressPerformance() {
        $str = '';
        for($i=0; $i<100; $i++) {
            $load = $this->byteProvider(rand(1,3), 1, 100);
            $str .= $load[0][0];
        }
        echo 'Length: '.\LZCompressor\LZUtil::utf8_strlen($str).' (UTF-8)'.PHP_EOL;
        $this->assertNotNull(LZString::compress($str));
    }

    public function testChr() {
        $seed = array();
        $max = 1000 * 100;
        for($i=0; $i<$max; $i++) {
            $seed[] = $this->getRandomUTF8();
        }

        foreach($seed as $s) {
            \LZCompressor\LZUtil::utf8_chr($s);
        }

    }


    private static function getRandomUTF8($byteCnt=null) {
        if(null === $byteCnt) {
            $byteCnt = rand(1,3);
        }
        return rand(self::$domain[$byteCnt][0], self::$domain[$byteCnt][1]);
    }

    private function byteProvider($byteCnt, $count, $length) {
        $testCases = [];
        $rands = [];
        for($i=0; $i<$count; $i++) {
            $test = '';
            for($j=0; $j<$length; $j++) {
                $rand = $this->getRandomUTF8($byteCnt);
                $rands[] = $rand;
                $test .= \LZCompressor\LZUtil::utf8_chr($rand);
            }
            $testCases[] = array($test);
        }
        return $testCases;
    }

}
