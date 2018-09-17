(function () {
    'use strict';

    angular
        .module('app.map.modal.diploma')
        .controller('MapModalDiplomaController', mapModalDiplomaController);

    function mapModalDiplomaController(university) {

        var vm = this;
        vm.university = university;

        activate();

        function activate() {
        	
        }
    }
})();