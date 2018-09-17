(function () {
    'use strict';

    angular
        .module('app.application')
        .controller('ApplicationController', applicationController);

    function applicationController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;	
        $scope.diplomaRequirements = {};
	activate();
	function activate() {
	    if($rootScope.userType == 'student'){
	    	    
	    }else if($rootScope.userType == 'school'){
		$state.go("app.application");
	    }else{
		$state.go("app.map");
	    }
        }
	
	$scope.perPage = 10;	
	$scope.numOfPage = 0;
	// Get submit Application by current user login .....
	$scope.applications = [];
	userService.getApplications().then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.applications = response.data.applications;
		$scope.numOfPage = $scope.applications.length - 1; 
	    }		
	});
	
	// Load More List content
	$scope.loadMore = function() {
	    $scope.perPage = $scope.perPage + 10;
	};
	
	//Sort By Per Page Records..
	$scope.sortByPerPage = function(records) {
		if(records === undefined || records == ''){
			records = $scope.applications.length + 1;
		}
		$scope.perPage = records;
	};
	
	//Check All Application For Remove all application.. 
	$scope.selectedApp = {};
	$scope.checkAll = function() {
	    angular.forEach($scope.applications, function(application) {
			//application.select = $scope.selectAll;
			$scope.selectedApp[application.id] = $scope.selectAll;		
		});
	};
	
	//Delete application By click on delete icon..
	$scope.deleteApplications = function() {
		userService.deleteApplications($scope.selectedApp).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.applications = response.data.applications;
			    alertService.add("success", response.data.msg);
			}else{
				alertService.add("danger", response.data.msg);
			}	
	    });  	
	}
	
	
	// Get application sort by update status...
	$scope.sortByUpdateStatus = function(status) {
	    // Get submit Application by current user login with order by update status .....
	    userService.getApplicationsSortByStatus(status).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applications = response.data.applications;
		}else{
		    $scope.applications = [];
		}		
	    });    
	};
	
	// get applications search by keyword....
	$scope.searchTrackKeyword= function(keyword) {
		userService.getApplicationsSearchByKeyword(keyword).then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
				$scope.applications = response.data.applications;
				//$scope.numOfPage = $scope.applications.length - 1; 
		    }		
		});
	};
	
	// View student profile of Track application status...
	$scope.trackStatus = function(id, status, student_id) {
	    if(status == 'Send'){
		//$scope.updateApplicationStatus('Review');
		//$state.go("app.viewStudentProfile", {'user':student_id});
		$scope.viewStudentProfile(id, student_id);
	    }else{
		userService.getApplicationById(id).then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
			$scope.applicationData = response.data.application;
			if($scope.applicationData.required_document){
			    $scope.diplomaRequirements = $scope.applicationData.required_document;
			}else{
			    $scope.diplomaRequirements = {};
			}
			$('#trackStatus').modal('show');
		    }		
		});
	    }
	};
	
	// Update application status by status value...
	$scope.updateApplicationStatus = function(value, document) {
	    
	    userService.updateApplicationStatus($scope.applicationData.id, value, false, document).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applications = response.data.applications;
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
	
	// View student profile by application id...
	$scope.viewStudentProfile = function(application_id, student_id){
	    
	    userService.getApplicationById(application_id).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applicationData = response.data.application;
		
		    if($scope.applicationData.application_statuses.name == 'Send')
			$scope.updateApplicationStatus('Review');
		}		
	    });
	    
	    $state.go("app.viewStudentProfile", {'user':student_id, 'application':application_id });
	}
    }
})();