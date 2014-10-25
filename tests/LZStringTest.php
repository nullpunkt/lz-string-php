<?php
require_once '../src/LZString.php';
/**
 * Description of LZStringTest
 *
 * @author Tobias Seipke <tobias.seipke@gmail.com>
 */
class LZStringTest extends PHPUnit_Framework_TestCase {
    
//    public function testWords() {
//        foreach (new DirectoryIterator('assets/words') as $fileInfo) {
//            if($fileInfo->isDot()) {
//                continue;
//            }
//            $contents = file_get_contents('assets/words/'.$fileInfo->getFilename());
//            $encoded = mb_convert_encoding($contents, 'UTF-8', 'ASCII');
//            $words = explode("\n", $encoded);
//            foreach($words as $word) {
//                $compressed = LZString::compress($word);
//                $this->assertEquals($word, LZString::decompress($compressed));
//            }
//        }
//    }
    
    public function testHuge() {
        $str = '';
        foreach (new DirectoryIterator('assets/words') as $fileInfo) {
            if($fileInfo->isDot()) {
                continue;
            }
            $contents = file_get_contents('assets/words/'.$fileInfo->getFilename());
            $encoded = mb_convert_encoding($contents, 'UTF-8', 'ASCII');
            $str = $encoded;
        }
        $compressed = LZString::compress($str);
        $this->assertEquals($str, LZString::decompress($compressed));
    }
    
}
