<?php
/**
 * Description of LZString
 *
 * @author Tobias Neeb <tobias.neeb@gmail.com>
 */
class LZContext {
    public $dictionary = array();
    public $dictionaryToCreate = array();
    public $c = '';
    public $wc = '';
    public $w = '';
    public $enlargeIn = 2;
    public $dictSize = 3;
    public $numBits = 2;
    public $data;
    
    function __construct() {
        $this->data = new LZData;
    }
}

class LZData {
    public $string;
    public $val;
    public $position = 0;
    public $index = 1;
}

class LZString {
    
    private static function charCodeAt($str, $num) { 
        
//        return ord(substr($str, $num, 1));
        
        return self::utf8_ord(self::utf8_charAt($str, $num)); 
    }
    
    private static function utf8_ord($ch) {
        $len = strlen($ch);
        if($len <= 0) return false;
        $h = ord($ch{0});
        if ($h <= 0x7F) return $h;
        if ($h < 0xC2) return false;
        if ($h <= 0xDF && $len>1) return ($h & 0x1F) <<  6 | (ord($ch{1}) & 0x3F);
        if ($h <= 0xEF && $len>2) return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);          
        if ($h <= 0xF4 && $len>3) return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
        return false;
    }
    
    private static function utf8_charAt($str, $num) { 
        return mb_substr($str, $num, 1, 'UTF-8'); 
    }
    
    private static function writeBit($value, LZData $data) {
        $data->val = ($data->val << 1) | $value;
        if($data->position == 15) {
            $data->position = 0;
            $data->str .= chr($data->val);
            $data->val = 0;
        }
        else {
            $data->position++;
        }
    }
    
    private static function writeBits($numbits, $value, LZData $data) {
        if(is_string($value))
            $value = self::charCodeAt($value, 0);
        for($i = 0; $i < $numbits; $i++) {
            self::writeBit($value & 1, $data);
            $value = $value >> 1;
        }
    }
        
    private static function decrementEnlargeIn(LZContext $context) {
        $context->enlargeIn--;
        if($context->enlargeIn === 0) {
            $context->enlargeIn = pow(2, $context->numBits);
            $context->numBits++;
        }
    }
        
    private static function produceW(LZContext $context) {
        if(array_key_exists($context->w, $context->dictionaryToCreate)) {
            if(self::charCodeAt($context->w, 0) < 256) {
                self::writeBits($context->numBits, 0, $context->data);
                self::writeBits(8, $context->w{0}, $context->data);
            }
            else {
                self::writeBits($context->numBits, 1, $context->data);
                self::writeBits(16, $context->w{0}, $context->data);
            }
            self::decrementEnlargeIn($context);
            unset($context->dictionaryToCreate[$context->w]);
        }
        else {
            self::writeBits($context->numBits, $context->dictionary[$context->w], $context->data);
        }   
        self::decrementEnlargeIn($context);
        return $context;    
    }
    
    public static function compressToBase64($uncompressed) {
        return base64_encode(self::compress($uncompressed));
    }
    
    public static function compress($uncompressed) {
        $context = new LZContext();
        for($i = 0; $i < strlen($uncompressed); $i++) {
            $context->c = $uncompressed{$i};
            
            if(!array_key_exists($context->c, $context->dictionary)) {
                $context->dictionary[$context->c] = $context->dictSize++;
                $context->dictionaryToCreate[$context->c] = TRUE;
            };
            
            $context->wc = $context->w . $context->c;
            if(array_key_exists($context->wc, $context->dictionary)) {
                $context->w = $context->wc;
            }
            else {
                self::produceW($context);
                $context->dictionary[$context->wc] = $context->dictSize++;
                $context->w = $context->c;
            }
        }
        if($context->w !== '') {
           self::produceW($context);
        }

        self::writeBits($context->numBits, 2,$context->data);
        

        // Flush the last char
        while($context->data->val>0)
            self::writeBit(0, $context->data);
        
        return $context->data->str;
    }
   
    private static function readBit(LZData $data) {
        $res = $data->val & $data->position;
        $data->position >>= 1;
        if($data->position === 0) {
            $data->position = 32768;
            // This 'if' check doesn't appear in the orginal lz-string javascript code->
            // Added as a check to make sure we don't exceed the length of data->str
            // The javascript charCodeAt will return a NaN if it exceeds the index but will not error out
            if($data->index < strlen($data->str)) {
                $data->val = $data->str[$data->index++]; // data->val = data->string->charCodeAt(data->index++); <---javascript equivilant
            }
        }
        return $res > 0 ? 1 : 0;
    }
    
    private static function readBits($numBits, LZData $data) {
        $res = 0;
        $maxpower = pow(2, $numBits);
        $power = 1;
        while($power !== $maxpower) {
            $res |= self::readBit($data) * $power;
            $power <<= 1;
        }
        return $res;
    }
    
     public static function decompress($compressed) {

        $data = new LZData();
        $dictionary = array("0", "1", "2");
        $next = 0;
        $enlargeIn = 4;
        $numBits = 3;
        $entry = '';
        $result = '';
        $i = 0;
        $w = '';
        $c = '';
        $errorCount = 0;
        $data->string = $compressed;
        $data->val = (int)$compressed{0};
        $data->position = 32768;
        $data->index = 1;

        $next = self::readBits(2, $data);
        switch($next) {
            case 0:
                $c = chr(self::readBits(8, $data));
                break;
            case 1:
                $c = chr(self::readBits(16, data));
                break;
            case 2:
                return '';
        }
        
        $dictionary[] = $c;
        $w = $result = $c;
                
        while(true) {
            $c = self::readBits($numBits, $data);
            $cc = (int)$c;

            switch($cc) {
                case 0:
                    if($errorCount++ > 10000)
                        throw new Exception("To many errors");
                    $c = chr(self::readBits(8, $data));
                    $dictionary[] = $c;
                    $c = count($dictionary) - 1;
                    $enlargeIn--;
                    break;
                case 1:
                    $c = chr(self::readBits(16, $data));
                    $dictionary[] = $c;
                    $c = count($dictionary) - 1;
                    $enlargeIn--;
                    break;
                case 2:
                    return $result;
            }

            if($enlargeIn === 0) {
                $enlargeIn = pow(2, $numBits);
                $numBits++;
            }

            if(array_key_exists($c, $dictionary) && $dictionary[$c] !== NULL) { // if (dictionary[c] ) <------- original Javascript Equivalant
                $entry = $dictionary[$c];
            }
            else {
                if($c === count($dictionary)) {
                    $entry = $w . $w{0};
                }
                else {
                    return NULL;
                }
            }

            $result .= $entry;
            $dictionary[] = $w . $entry{0};
            $enlargeIn--;
            $w = $entry;

            if($enlargeIn === 0) {
                $enlargeIn = pow(2, $numBits);
                    $numBits++;
                }
            }
     }
}