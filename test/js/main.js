$(function() {
    $('.fromCharCode tr').each(function(i, ele) {
        var v = htmlDecode($(ele).children('.value').html());
        var php = htmlDecode($(ele).children('.PHP').html());
        var js = String.fromCharCode(v);
        $(ele).children('.JS').html(js);
        $(ele).children('.JSL').html(js.length);
        $(ele).children('.equals').html((php===js) ? 'true' : 'false');
        $(ele).children('.equals').addClass((php===js) ? 'success' : 'danger');
    });
    $('.compress tr').each(function(i, ele) {
        var v = htmlDecode($(ele).children('.value').html());
        var php = htmlDecode($(ele).children('.PHP').html());
        var js = LZString.compress(v);
        $(ele).children('.JS').html(js);
        $(ele).children('.equals').html((php===js) ? 'true' : 'false');
        $(ele).children('.equals').addClass((php===js) ? 'success' : 'danger');
    });
    $('.compressToBase64 tr').each(function(i, ele) {
        var v = htmlDecode($(ele).children('.value').html());
        var php = htmlDecode($(ele).children('.PHP').html());
        var js = LZString.compressToBase64(v);
        $(ele).children('.JS').html(js);
        $(ele).children('.equals').html((php===js) ? 'true' : 'false');
        $(ele).children('.equals').addClass((php===js) ? 'success' : 'danger');
    });
    $('.decompress tr').each(function(i, ele) {
        var v = htmlDecode($(ele).children('.compressed').html());
        var php = htmlDecode($(ele).children('.PHP').html());
        var js = LZString.decompress(v);
        $(ele).children('.JS').html(js);
        $(ele).children('.equals').html((php===js) ? 'true' : 'false');
        $(ele).children('.equals').addClass((php===js) ? 'success' : 'danger');
    });
    $('.decompressFromBase64 tr').each(function(i, ele) {
        var v = htmlDecode($(ele).children('.compressed').html());
        var php = htmlDecode($(ele).children('.PHP').html());
        var js = LZString.decompressFromBase64(v);
        $(ele).children('.JS').html(js);
        $(ele).children('.equals').html((php===js) ? 'true' : 'false');
        $(ele).children('.equals').addClass((php===js) ? 'success' : 'danger');
        
        if(php!==js) {
            console.log(php);
            console.log(js);
        }
    });
});

function htmlDecode(input){
    if(input.length===0)return input;
    var e = document.createElement('div');
    e.innerHTML = input;
    return e.childNodes[0].nodeValue;
}