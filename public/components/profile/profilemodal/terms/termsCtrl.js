(function () {
    'use strict';

    angular
        .module('app.profile.profilemodal.terms')
        .controller('ProfileModalTermsController', profileModalTermsController);

    function profileModalTermsController() {
		alert('profileModalTermsController');
        var vm = this;
        //vm.university = university;

        activate();

        function activate() {

        }
    }
})();