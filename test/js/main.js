$(function() {
    $('.compress tr').each(function(i, ele) {
        $(ele).children('.JS').html(LZString.compress($(ele).children('.value').html()));
//        $(ele).children('.JSMD5').html(md5(LZString.compress($(ele).children('.value').html())).substring(0,4));
    });
    $('.compressToBase64 tr').each(function(i, ele) {
        $(ele).children('.JS').html(LZString.compressToBase64($(ele).children('.value').html()));
        $(ele).children('.JSMD5').html(md5(LZString.compressToBase64($(ele).children('.value').html())).substring(0,4));
    });
});