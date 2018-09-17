(function () {
    'use strict';

    angular
        .module('app.profile.profilemodal')
        .controller('ProfileModalController', profileModalController);

    //function profileModalController(university,ProfileModalService) {
	function profileModalController(ProfileModalService) {
		alert('ProfileModalService');
        var vm = this;
        //vm.university = university;
        vm.openModal = openModal;
        vm.closeModal = closeModal;

        setTimeout(function() {
            activate();
        }, 300);

        function activate() {
            openModal('custom-modal-2');
        }
        function openModal(id){
            ProfileModalService.Open(id);
        }
        function closeModal(id){ 	
            ProfileModalService.Close(id);
        }
    }
})();