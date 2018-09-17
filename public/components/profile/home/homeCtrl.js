(function () {
    'use strict';

    angular
        .module('app.profile.home')
        .controller('ProfileHomeController', profileHomeController);

    function profileHomeController($compile, $scope,$rootScope) {
        var vm = this;
        $scope.landingShow = false;
    }
})();