(function () {
    'use strict';

    angular
        .module('app.profile.messages')
        .controller('ProfileMessagesController', profileMessagesController);

    function profileMessagesController($compile, $scope,$rootScope) {
        var vm = this;
        $scope.landingShow = false;
    }
})();