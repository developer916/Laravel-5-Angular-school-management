(function () {
    'use strict';

    angular
        .module('app.profile')
        .controller('ProfileController', profileController);

    function profileController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies) {
       
        var vm = this;
        $scope.landingShow = false;
       	// vm.universities = universities;
        vm.openModal = openModal;
        $scope.step = 'step-1';
		$scope.school_profile = {};
     
        activate();

        function activate() {
	    /*var user_email =  $cookies.get('user_email');
	    if(angular.isUndefined(user_email)){
		$state.go("app.map");
	    }*/
		    
	    if($rootScope.userType == 'school'){
		universityService.getUniversityProfile().then(function (response) {
		    if (response.status == 200 && response.data.auth){
			$rootScope.universityData =  response.data.auth;
			$scope.school_profile.school_name = $rootScope.universityData.name;
			$scope.school_profile.logo_link = $rootScope.universityData.logo_link;
			$scope.school_profile.info = $rootScope.universityData.description;
			$scope.school_profile.slogan = $rootScope.universityData.slogan;
			$scope.school_profile.address = $rootScope.universityData.address.address_name;
			$scope.school_profile.ref_country_id = $rootScope.universityData.refCountry.id;
			$scope.school_profile.phone = $rootScope.universityData.phone;
			$scope.school_profile.terms = $rootScope.universityData.terms;
			$scope.school_profile.termCount = $rootScope.universityData.terms;
			$scope.school_profile.termsObj = $rootScope.universityData.termsObj;
		    }
		});
		
		$scope.next_btn = true;  
		$scope.modalPopupType = 'basic';
		openModal('custom-modal-2');
		$scope.step = 'step-1';	
	    }else{
		//alert($rootScope.userType);
		//$scope.next_btn = true;  
		//$scope.modalPopupType = 'basic';
		//openModal('custom-modal-2');
		//$scope.step = 'step-1';
	    }
        }


	$scope.closeModal = function(id){
            $("#profileModal").removeClass('in');
            $('#custom-modal-2').fadeOut(300);
        };
        
        
        function openModal(id){
            $("#profileModal").addClass('in');
            $('#custom-modal-2').fadeIn(300);
        };
        
        $scope.comebacklater = function(id){
	    if($scope.step == 'step-2'){
		$scope.step = 'step-1';
		$scope.modalPopupType = 'basic';
	    }else if($scope.step == 'step-3'){
		$scope.step = 'step-2';
		$scope.modalPopupType = 'basicupload';
	    }else if($scope.step == 'step-4'){
		$scope.step = 'step-3';
		$scope.modalPopupType = 'basicinfo';
	    }else if($scope.step == 'step-5'){
		$scope.step = 'step-4';
		$scope.modalPopupType = 'basicslogan';
	    }else if($scope.step == 'step-6'){
		$scope.step = 'step-5';
		$scope.modalPopupType = 'basicdetail';		
	    }else if($scope.step == 'step-7'){
		$scope.step = 'step-6';
		$scope.modalPopupType = 'terms';	
	    }else if($scope.step == 'step-8'){
		$scope.step = 'step-7';
		$scope.modalPopupType = 'termsview';
	    }
	
	};
        
        $scope.next_modal = function(id){
	    $scope.next_btn = true;
	    if($rootScope.authenticated){
		if($scope.step == 'step-1'){
		    $scope.step = 'step-2';
		    $scope.modalPopupType = 'basicupload';
		}else if($scope.step == 'step-2'){
		    $scope.step = 'step-3';
		    $scope.modalPopupType = 'basicinfo';
		}else if($scope.step == 'step-3'){
		    $scope.step = 'step-4';
		    $scope.modalPopupType = 'basicslogan';	
		}else if($scope.step == 'step-4'){
		    $scope.step = 'step-5';
		    $scope.modalPopupType = 'basicdetail';	
		}else if($scope.step == 'step-5'){
		    $scope.step = 'step-6';
		    $scope.modalPopupType = 'terms';		
		}else if($scope.step == 'step-6'){
		    if(!$scope.school_profile.terms){
			alert('Please select terms.');
		    }else{
			$scope.step = 'step-7';
			$scope.modalPopupType = 'termsview';
		    }				    
		}else if($scope.step == 'step-7'){
		    //var Sprofile = $scope.school_profile;
		    //userService.saveProfile(user_email,Sprofile).then(function (response) {
			//if (response.status == 200 && response.data.auth){
			    $scope.next_btn = false;
			    $scope.step = 'step-8';
			    $scope.modalPopupType = 'success';	
			//}
		    //});	
		}
		
		var Sprofile = $scope.school_profile;
		universityService.saveProfile(Sprofile,$scope.step).then(function (response) {
		    if (response.status == 200 && response.data.auth){
			$rootScope.universityData =  response.data.auth;
		    }
		});
	    }
			
        };
    }
})();