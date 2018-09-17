(function () {
    'use strict';

    angular
        .module('app.map.modal.message')
        .controller('MapModalMessageController', mapModalMessageController);

    function mapModalMessageController(university) {

        var vm = this;
        vm.university = university;

        activate();

        function activate() {
        }
    }
})();