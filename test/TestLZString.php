<?php

use LZCompressor\LZString as LZString;

class TestLZString extends PHPUnit_Framework_TestCase
{
//    /**
//     * @dataProvider oneByteProvider
//     * @param $test
//     * @throws Exception
//     */
//    public function testUTF8_1Byte($test) {
//        $this->assertEquals($test, LZString::decompress(LZString::compress($test)));
//    }
//
//    /**
//     * @dataProvider twoBytesProvider
//     * @param $test
//     * @throws Exception
//     */
//    public function testUTF8_2Bytes($test) {
//        $this->assertEquals($test, LZString::decompress(LZString::compress($test)));
//    }

    /**
     * @dataProvider oneByteProvider
     * @param $test
     * @throws Exception
     */
    public function testBase64_1Byte($test) {
        $this->assertEquals($test, LZString::decompressFromBase64(LZString::compressToBase64($test)));
    }

    /**
     * @dataProvider twoBytesProvider
     * @param $test
     * @throws Exception
     */
    public function testBase64_2Bytes($test) {
        $this->assertEquals($test, LZString::decompressFromBase64(LZString::compressToBase64($test)));
    }

    public function oneByteProvider() {
        return $this->byteProvider(1, 1000, 1000);
//        return $this->byteProvider(1, 1, 100);
    }

    public function twoBytesProvider() {
        return $this->byteProvider(2, 1000, 500);
//        return $this->byteProvider(2, 1, 500);
    }

    private function byteProvider($byteCnt, $count, $length) {
        $start = pow(2, (($byteCnt-1)*8))-1;
        $end = pow(2, ($byteCnt*8)-1);
        $testCases = [];
        for($i=0; $i<$count; $i++) {
            $test = '';
            for($j=0; $j<$length; $j++) {
                $test .= LZString::utf8_chr(rand($start, $end));
            }
            $testCases[] = array($test);
        }
        return $testCases;
    }

}
