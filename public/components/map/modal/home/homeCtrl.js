(function () {
    'use strict';

    angular
        .module('app.map.modal.home')
        .controller('MapModalHomeController', mapModalHomeController);

    function mapModalHomeController(university) {

        var vm = this;
        vm.university = university;

        activate();

        function activate() {

        }
    }
})();