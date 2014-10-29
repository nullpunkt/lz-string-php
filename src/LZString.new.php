<?php

class LZContext {
    public $dictionary = array();
    public $dictionaryToCreate = array();
    public $c = '';
    public $wc = '';
    public $w = '';
    public $enlargeIn = 2;
    public $dictSize = 3;
    public $numBits = 2;
    public $data = NULL;
    
    function __construct() {
        $this->data = new LZData;
    }
}

class LZData {
    public $string = '';
    public $val = 0;
    public $position = 0;
    
    function writeBit($value=NULL) {
        if($value===NULL) {
            $this->val = ($this->val << 1);
        } else {
            $this->val = ($this->val << 1) | $value;
        }
        if($this->position===15) {
            $this->position = 0;
            $this->string .= pack("L", $this->val);
            $this->val = 0;
        } else {
            $this->position++;
        }
    }
    
    function flush() {
        while(TRUE) {
            $this->val = $this->val<<1;
            if($this->position>=15) {
                $this->string .= pack("L", $this->val);
                return;
            } else {
                $this->position++;
            }
        }
    }
}


class LZString {
    
    public static function decrementEnlargeIn(LZContext $context) {
        $context->enlargeIn--;
        if($context->enlargeIn===0) {
            $context->enlargeIn = pow(2, $context->numBits);
            $context->numBits++;
        }
    }
    
    static function produceW(LZContext $context) {
        $ord = array_reduce(unpack('C*', mb_substr($context->w, 0, 1)), function($carry, $item) {
            $carry += $item;
            return $carry;
        });
        if(array_key_exists($context->w, $context->dictionaryToCreate)) {
            if($ord<256) {
                for($i=0; $i<$context->numBits; $i++) {
                    $context->data->writeBit();
                }
                $value = $ord;
                for($i=0; $i<8; $i++) {
                    $context->data->writeBit($value&1);
                    $value = $value >> 1;
                }
            } else {
                $value = 1;
                for($i=0; $i<$context->numBits; $i++) {
                    $context->data->writeBit($value);
                    $value = 0;
                }
                $value = $ord;
                for($i=0; $i<16; $i++) {
                    $context->data->writeBit($value&1);
                    $value = $value >> 1;
                }
            }
            self::decrementEnlargeIn($context);
            unset($context->dictionaryToCreate[$context->w]);
        } else {
            $value = $context->dictionary[$context->w];
            for($i=0; $i<$context->numBits; $i++) {
                $context->data->writeBit($value&1);
                $value = $value >> 1;
            }
        }
    }
    
    static function compress($uncompressed) {
        $context = new LZContext;
        for($i=0; $i<mb_strlen($uncompressed); $i++) {
            $context->c = mb_substr($uncompressed, $i, 1);
            if(!array_key_exists($context->c, $context->dictionary)) {
                $context->dictionary[$context->c] = $context->dictSize++;
                $context->dictionaryToCreate[$context->c] = TRUE;
            }
            $context->wc = $context->w . $context->c;
            if(array_key_exists($context->wc, $context->dictionary)) {
                $context->w = $context->wc;
            } else {
                self::produceW($context);
                self::decrementEnlargeIn($context);
                $context->dictionary[$context->wc] = $context->dictSize++;
                $context->w = $context->c;
            }
        }
        $ord = array_reduce(unpack('C*', mb_substr($context->w, 0, 1)), function($carry, $item) {
            $carry += $item;
            return $carry;
        });
        if($context->w!=='') {
            self::produceW($context, $ord);
        }
        $value = 2;
        for($i=0; $i<$context->numBits; $i++) {
            $context->data->writeBit($value&1);
            $value = $value >> 1;
        }
        $context->data->flush();
        return $context->data->string;
    }
    
    public static function compressToBase64($input) {
        return LZBase64::compress(self::compress($input));
    }
    
    public static function decompressFromBase64($input) {
        $output = '';
        $ol = 0;
        $output_ = NULL;
        $chr1 = NULL;
        $chr2 = NULL;
        $chr3 = NULL;
        $enc1 = NULL;
        $enc2 = NULL;
        $enc3 = NULL;
        $enc4 = NULL;
        $input = preg_replace('/[^A-Za-z0-9\+\/\=]/', '', $input);
        $i=0;
        while($i < mb_strlen($input)) {
            
            $enc1 = strpos(self::$keyStr, $input{$i++});
            $enc2 = strpos(self::$keyStr, $input{$i++});
            $enc3 = strpos(self::$keyStr, $input{$i++});
            $enc4 = strpos(self::$keyStr, $input{$i++});
            
            $chr1 = ($enc1 << 2) | ($enc2 >> 4);
            $chr2 = (($enc2 & 15) << 4) | ($enc3 >> 2);
            $chr3 = (($enc3 & 3) << 6) | $enc4;
            
            if($ol%2==0) {
                $output_ = $chr1 << 8;
                if($enc3 != 64) {
                    $output .= self::chr($output_ | $chr2);
                }
                if($enc4 != 64) {
                    $output_ = $chr3 << 8;
                }
            } 
            else {
                $output = $output . self::chr($output_ | $chr1);
                if($enc3 != 64) {
                    $output_ = $chr2 << 8;
                }
                if($enc4 != 64) {
                    $output .= self::chr($output_ | $chr3);
                }
            }
            $ol+=3;
        }
        return self::decompress($output);
    }
}

class LZByteString {
    
}

class LZUTF8 {
    public static function charCodeAt($str, $i) {
        list(, $ord) = unpack('N', mb_convert_encoding(self::charAt($str, $i), 'UCS-4BE', 'UTF-8'));
        return $ord;
    }
    
    public static function charAt($str, $i) {
        return mb_substr($str, $i, 1, 'UTF-8');
    }
    
    public static function chr($ord, $encoding='UTF-8') {
        if($encoding==='UCS-4BE') {
            return pack("N", $ord);
        }else{
            return mb_convert_encoding(self::chr($ord, 'UCS-4BE'), $encoding, 'UCS-4BE');
        }
    }
    
}

class LZBase64 {
    
    private static $keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    
    static function compress($input) {
        $output = '';
        $chr = array('NaN', 'NaN', 'NaN');
        $enc = array('NaN', 'NaN', 'NaN', 'NaN');
        $i=0;
        $strlen = mb_strlen($input, 'UTF-8');
        while($i < ($strlen*2)) {
            if($i%2===0) {
                $chr[0] = LZUTF8::charCodeAt($input, $i/2) >> 8;
                $chr[1] = LZUTF8::charCodeAt($input, $i/2) & 255;
                if(($i/2)+1 < $strlen) {
                    $chr[2] = LZUTF8::charCodeAt($input, ($i/2)+1) >> 8;
                }
                else {
                    $chr[2] = 'NaN';
                }
            } 
            else {
                $chr[0] = LZUTF8::charCodeAt($input, ($i-1)/2) & 255;
                if(($i+1)/2 < $strlen) {
                    $chr[1] = LZUTF8::charCodeAt($input, ($i+1)/2) >> 8;
                    $chr[2] = LZUTF8::charCodeAt($input, ($i+1)/2) & 255;
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