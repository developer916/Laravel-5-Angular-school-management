(function () {
    'use strict';

    angular
        .module('app.map.modal.application')
        .controller('MapModalApplicationController', mapModalApplicationController);

    function mapModalApplicationController(university) {

        var vm = this;
        vm.university = university;

        activate();
        function activate() {
        }
    }
})();