<?php
/**
 * Description of LZString
 *
 * @author Tobias Seipke <tobias.seipke@gmail.com>
 */
class LZString {
    
    private static $enc = 'UNICODE';
    private static $encLength = 4;
    private static $keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    
    private $bytes;
    private $length;
    
    private function __construct($str) {
        $this->bytes = unpack("C*", mb_convert_encoding($str, self::$enc, 'UTF-8'));
        $this->length = count($this->bytes)/self::$encLength;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function getBytes($pos) {
        return array_slice($this->bytes, ($pos*self::$encLength)+1, self::$encLength);
    }
    
    public function ord($pos) {
        $ret = 0;
        for($i=($pos*self::$encLength)+1;$i<($pos*self::$encLength)+self::$encLength+1;$i++) {
            $ret += $this->bytes[$i];
        }
        return $ret;
    }
    
    public static function compressToBase64($input) {
        
        $string = new LZString($input);
        
        $output = '';
        $chr = array('NaN', 'NaN', 'NaN');
        $enc = array('NaN', 'NaN', 'NaN', 'NaN');
        $i=0;
        $strlen = $string->getLength();
        
        while($i < ($strlen*2)) {
            if($i%2===0) {
                $chr[0] = $string->ord($i/2) >> 8;
                $chr[1] = $string->ord($i/2) & 255;
                if(($i/2)+1 < $strlen) {
                    $chr[2] = $string->ord(($i/2)+1) >> 8;
                }
                else {
                    $chr[2] = 'NaN';
                }
            } 
            else {
                $chr[0] = $string->ord(($i-1)/2) & 255;
                if(($i+1)/2 < $strlen) {
                    $chr[1] = $string->ord(($i+1)/2) >> 8;
                    $chr[2] = $string->ord(($i+1)/2) & 255;
                } else  {
                    $chr[1] = 'NaN';
                    $chr[2] = 'NaN';
                }
            }
            $i+=3;
            $enc[0] = $chr[0] >> 2;
            $enc[1] = (($chr[0] & 3) << 4) | ($chr[1] >> 4);
            $enc[2] = (($chr[1] & 15) << 2) | ($chr[2] >> 6);
            $enc[3] = $chr[2] & 63;
            if($chr[1]==='NaN') {
                $enc[2] = 64;
                $enc[3] = 64;
            } else if ($chr[2]==='NaN') {
                $enc[3] = 64;
            }
            $output = $output 
            .self::$keyStr{$enc[0]} 
            .self::$keyStr{$enc[1]} 
            .self::$keyStr{$enc[2]} 
            .self::$keyStr{$enc[3]}
            ;
        }
        return $output;
    }
    
    public static function uncompress($input) {
        $output = ''; $ol = 0; $output_ = NULL; $i=0;
        $input = preg_replace('/[^A-Za-z0-9\+\/\=]/', '', $input);
        while($i < strlen($input)) {
            
            $enc = array(
                strpos(self::$keyStr, $input{$i++}),
                strpos(self::$keyStr, $input{$i++}),
                strpos(self::$keyStr, $input{$i++}),
                strpos(self::$keyStr, $input{$i++})
            );
            $chr = array(
                ($enc[0] << 2) | ($enc[1] >> 4),
                (($enc[1] & 15) << 4) | ($enc[2] >> 2),
                (($enc[2] & 3) << 6) | $enc[3]
            );
            
            if($ol%2==0) {
                $output_ = $chr[0] << 8;
                if($enc[2] != 64) {
                    $output .= LZUTF8::chr($output_ | $chr[1]);
                }
                if($enc[3] != 64) {
                    $output_ = $chr[2] << 8;
                }
            } 
            else {
                $output = $output . LZUTF8::chr($output_ | $chr[0]);
                if($enc[2] != 64) {
                    $output_ = $chr[1] << 8;
                }
                if($enc[3] != 64) {
                    $output .= LZUTF8::chr($output_ | $chr[2]);
                }
            }
            $ol+=3;
        }
        return $output;
    }
    
}
