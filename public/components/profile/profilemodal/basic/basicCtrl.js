(function () {
    'use strict';

    angular
        .module('app.profile.profilemodal.basic')
        .controller('ProfileModalBasicController', profileModalBasicController);

    function profileModalBasicController() {
        alert('ProfileModalBasicController');
        var vm = this;
        //vm.university = university;

        activate();

        function activate() {

        }
    }
})();