(function () {
    'use strict';

    angular
        .module('app.map.modal.housing')
        .controller('MapModalHousingController', mapModalHousingController);

    function mapModalHousingController(university) {

        var vm = this;
        vm.university = university;

        activate();

        function activate() {
        }
    }
})();