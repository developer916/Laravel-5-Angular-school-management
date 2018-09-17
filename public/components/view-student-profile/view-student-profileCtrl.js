(function () {
    'use strict';

    angular
        .module('app.viewStudentProfile')
        .controller('ViewStudentProfileController', viewStudentProfileController);

    function viewStudentProfileController($compile, $scope, $rootScope, $stateParams, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false; 
	$scope.diplomaRequirements = {};
        activate();
        function activate() {
	    
	    if($rootScope.userType == 'student'){
	    	    
	    }else if($rootScope.userType == 'school'){
		$state.go("app.viewStudentProfile");
	    }else{
		$state.go("app.map");
	    }
        }
	
	userService.getApplicationById($stateParams.application).then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.applicationData = response.data.application;
		if($scope.applicationData.required_document){
		    $scope.diplomaRequirements = $scope.applicationData.required_document;
		}else{
		    $scope.diplomaRequirements = {};
		}
	    }		
	});
	
	// Get student Educations Details By Student Id...
	userService.getEducationsByStudentId($stateParams.user).then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.educations = response.data.educations;
	    }		
	});
	
	// Get student Experiences Details BY Student Id...
	userService.getExperiencesByStudentId($stateParams.user).then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.experiences = response.data.experiences;
	    }		
	});
	
	// Get student Languages Details By Student Id....
	userService.getLanguagesByStudentId($stateParams.user).then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.languages = response.data.languages;
	    }		
	});
	
	//$scope.updateApplication = function(data){
	//    userService.updateApplication($scope.applicationData.id, data).then(function (response) {
	//	if (response.status == 200 && response.data.status == true ){
	//	    $scope.applicationData = response.data.application;		    
	//	    $('#updateApplying').modal('hide');
	//	    alertService.add("success", response.data.msg);
	//	}else{
	//	    alertService.add("danger", response.data.msg);
	//	}		
	//    });
	//}
	
	
	// Update Application status by school user into view student profile page
	$scope.updateApplicationStatus = function(value, document) {
	    userService.updateApplicationStatus($scope.applicationData.id, value, 'checkProfile', document).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applicationData = response.data.application;
		    if($scope.applicationData.required_document){
			$scope.diplomaRequirements = $scope.applicationData.required_document;
		    }else{
			$scope.diplomaRequirements = {};
		    }
		    if(value != 'Review' && value != 'Incomplete'){
			$('#trackStatus').modal('hide');
			alertService.add("success", response.data.msg);
		    }
		}else{
		    if(value != 'Review'){
			alertService.add("danger", response.data.msg);
		    }
		}		
	    });
	}
	
    }
})();