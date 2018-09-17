(function () {
    'use strict';

    angular
        .module('app.map.modal')
        .controller('MapModalController', mapModalController);

    function mapModalController(university,ModalService) {

        var vm = this;
        vm.university = university;
        vm.openModal = openModal;
        vm.closeModal = closeModal;

        setTimeout(function() {
            activate();
        }, 300);

        function activate() {
            openModal('custom-modal-1');
        }
        function openModal(id){
            ModalService.Open(id);
        }
        function closeModal(id){
            ModalService.Close(id);
        }
    }
})();