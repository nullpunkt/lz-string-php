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
        
})

;