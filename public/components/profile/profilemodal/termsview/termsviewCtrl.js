(function () {
    'use strict';

    angular
        .module('app.profile.profilemodal.termsview')
        .controller('ProfileModalTermsviewController', profileModalTermsviewController);

    function profileModalTermsviewController($scope) {
		alert('profileModalTermsviewController');
        var vm = this;
        //vm.university = university;

        activate();

        function activate() {

        }
    }
})();