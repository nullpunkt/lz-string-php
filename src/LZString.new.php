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
    public $result = '';
    
    function __construct() {
        $this->data = new LZData;
    }
}

class LZData {
    public $string;
    public $val;
    public $position = 0;
}


class LZString {
    
    public static function chr($ord, $encoding='UTF-8') {
        if($encoding==='UCS-4BE') {
            return pack("N", $ord);
        }else{
            return mb_convert_encoding(self::chr($ord, 'UCS-4BE'), $encoding, 'UCS-4BE');
        }
    }
    
    public static function ord($char, $encoding='UTF-8') {
        if($encoding==='UCS-4BE') {
            list(, $ord) = (strlen($char) === 4) ? @unpack('N', $char) : @unpack('n', $char);
            return $ord;
        } else {
            return self::ord(mb_convert_encoding($char, 'UCS-4BE', $encoding), 'UCS-4BE');
        }
    }
    
    public static function charCodeAt($str, $i) {
        return self::ord(mb_substr($str, $i, 1, 'UTF-8'));
    }
    
//    private static function ord($ch) {
//        list(, $ord) = unpack('N', mb_convert_encoding($ch, 'UTF-16', 'UTF-8'));
//        return $ord;
//        
//        $len = strlen($ch);
//        if($len <= 0) return false;
//        $h = ord($ch{0});
//        if ($h <= 0x7F) return $h;
//        if ($h < 0xC2) return false;
//        if ($h <= 0xDF && $len>1) return ($h & 0x1F) <<  6 | (ord($ch{1}) & 0x3F);
//        if ($h <= 0xEF && $len>2) return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);          
//        if ($h <= 0xF4 && $len>3) return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
//        return false;
//    }
    
    static function produceW(LZContext $context) {
        if(array_key_exists($context->w, $context->dictionaryToCreate)) {
//            if(self::charCodeAt($context->w, 0)<256) {
//                self::writeBits()
//            }
        }
//        
//         if (Object.prototype.hasOwnProperty.call(context.dictionaryToCreate,context.w)) {
//            if (context.w.charCodeAt(0)<256) {
//            this.writeBits(context.numBits, 0, context.data);
//            this.writeBits(8, context.w, context.data);
//            } else {
//            this.writeBits(context.numBits, 1, context.data);
//            this.writeBits(16, context.w, context.data);
//            }
//            this.decrementEnlargeIn(context);
//            delete context.dictionaryToCreate[context.w];
//        } else {
//        this.writeBits(context.numBits, context.dictionary[context.w], context.data);
//        }
//        this.decrementEnlargeIn(context);
    }
    
    static function compress($uncompressed) {
        $context = new LZContext;
//        echo $uncompressed;
//        $unc = unpack("C*", $uncompressed);
        for($i=0;$i<mb_strlen($uncompressed, 'UTF-8');$i++) {
            $context->c = charCodeAt($uncompressed, $i, 1);
            if(!array_key_exists($context->c, $context->dictionary)) {
                $context->dictionary[$context->c] = $context->dictSize++;
                $context->dictionaryToCreate[$context->c] = true;
            }
            $context->wc = $context->w.$context->c;
            if(array_key_exists($context->wc, $context->dictionary)) {
                $context->w = $context->wc;
            } else {
                self::produceW($context);
            }
            
        }
        
//        $context = new LZContext;
//        for($i=0; $i<count($unc); $i++) {
////            var_dump(array_merge(array('C*'), array_slice($unc, $i, 2)));
//            $context->c = pack('C*', array_slice($unc, $i, 1));
//            if(!array_key_exists($context->c, $context->dictionary)) {
//                $context->dictionary[$context->c] = $context->dictSize++;
//                $context->dictionaryToCreate[$context->c] = true;
//            }
//            $context->wc = $context->w.$context->c;
//            if(array_key_exists($context->wc, $context->dictionary)) {
//                $context->w = $context->wc;
//            } else {
//                self::produceW($context);
//            }
//        echo '--->'.$context->c."\n";
//        }
        
//        
//        context.wc = context.w + context.c;
//        if (Object.prototype.hasOwnProperty.call(context.dictionary,context.wc)) {
//        context.w = context.wc;
//        } else {
//        this.produceW(context);
//        // Add wc to the dictionary.
//        context.dictionary[context.wc] = context.dictSize++;
//        context.w = String(context.c);
//        }
//        }
//        // Output the code for w.
//        if (context.w !== "") {
//        this.produceW(context);
//        }
//        // Mark the end of the stream
//        this.writeBits(context.numBits, 2, context.data);
//        // Flush the last char
//        while (context.data.val>0) this.writeBit(0,context.data)
//        return context.data.string;
//        },
//        readBit : function(data) {
//        var res = data.val & data.position;
//        data.position >>= 1;
//        if (data.position == 0) {
//        data.position = 32768;
//        data.val = data.string.charCodeAt(data.index++);
//        }
//        //data.val = (data.val << 1);
//        return res>0 ? 1 : 0;
//        },
//        readBits : function(numBits, data) {
//        var res = 0;
//        var maxpower = Math.pow(2,numBits);
//        var power=1;
//        while (power!=maxpower) {
//        res |= this.readBit(data) * power;
//        power <<= 1;
//        }
//        return res;
//        },
        
    }
}
