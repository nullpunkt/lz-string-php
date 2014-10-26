var lzstring = angular.module('lzstring', [])

.controller('BackEndCompareCtrl', function ($scope, $http) {
    
    $scope.word = 'Fön';
    
    $scope.$watch('word', function() {
        $scope.wordlzjs = LZString.compress($scope.word);
        $http.get('/service/?w='+$scope.word).
        success(function(data, status, headers, config) {
            $scope.wordlzphp = data.w;
        });
    });
    
});