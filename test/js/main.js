var lzstring = angular.module('lzstring', [])

.controller('BackEndCompareCtrl', function ($scope, $http) {
        
    $scope.compare = {
        compressToBase64: {
            word: 'Fön',
            php: '',
            phpu: '',
            js: ''
        }
    };

    $scope.$watch('compare.compressToBase64.word', function (word) {
        $scope.compare.compressToBase64.js = LZString.compressToBase64(word, false);
        $http.get('/service/?a=b64&w=' + word).success(function (data, status, headers, config) {
            $scope.compare.compressToBase64.php = data.wc;
            $scope.compare.compressToBase64.phpu = data.wu;
        });
    });
    
    $scope.generated = [];
    
    $scope.generateBase64encode = function() {
        var words = generateWords(100, 10, 2);
        $scope.generated.length = 0;
        for(var i=0; i<words.length; i++) {
            $scope.generated.push({
                word: words[i],
                gen: LZString.compressToBase64(words[i], false)
            });
        }
        console.log($scope.generated);
    };
    
    function generateWords(count, length, bytes) {
        var ret = [];
        for(var i=0; i<count; i++) {
            var tmp = '';
            for(var j=0; j<length; j++) {
                var ord = 0;
                for(var k=0; k<bytes; k++) {
                    ord += parseInt(Math.random()*256)
                };
                tmp += String.fromCharCode(ord);
            }
            ret.push(tmp);
        }
        return ret;
    }
})

;