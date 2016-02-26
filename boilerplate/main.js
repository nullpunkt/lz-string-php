angular.module('lzapp', []);

angular.module('lzapp').controller('LZStringCtrl', LZStringCtrl);


LZStringCtrl.$inject = ['$http'];

function LZStringCtrl($http) {
    var vm = this;

    vm.source = '';
    vm.results = [];

    vm.encode = encode;

    function encode() {
        vm.results.push(generate(vm.source));
        vm.source = '';
    }

    function generate(str) {
        var com = LZString.compress(str), com64 = LZString.compressToBase64(str);
        var result = {
            input: str,
            compressed: com,
            compressed64: com64,
            decompressed: LZString.decompress(com),
            decompressed64: LZString.decompressFromBase64(com64)
        };

        $http.post('service.php', {str: str, com64: com64}).then(function(res) {
            result.compressed64php = res.data.compressed64php;
            result.decompressed64php = res.data.decompressed64php;
        });

        return result;

    }
}