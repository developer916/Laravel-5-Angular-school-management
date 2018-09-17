(function () {
    'use strict';

    angular
        .module('app.profile.profilemodal.success')
        .controller('ProfileModalSuccessController', profileModalSuccessController);

    function profileModalSuccessController() {
		alert('profileModalSuccessController');
        var vm = this;
        //vm.university = university;

        activate();

        function activate() {

        }
    }
})();