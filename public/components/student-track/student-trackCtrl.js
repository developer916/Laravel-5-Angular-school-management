(function () {
    'use strict';

    angular
        .module('app.studentTrack')
        .controller('StudentTrackController', studentTrackController);

    function studentTrackController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, $location) {
        var vm = this;
        $scope.landingShow = false;
	$scope.track_tab = 'send';
       // vm.universities = universities;
	$scope.document_type_id = {};
        activate();

        function activate() {
	    
	    if($rootScope.userType == 'student'){
	    	    
	    }else if($rootScope.userType == 'school'){
		$state.go("app.profile");
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
	
	// Get Document Type list ...
	$scope.documentTypes = {};
	userService.getDocumentTypes().then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
		$scope.documentTypes = response.data.documentTypes;
	    }		
	});
		    	
	//Sort By Per Page Records..
	$scope.sortByPerPage = function(records) {
		if(records === undefined || records == ''){
			records = $scope.applications.length + 1;
		}
		$scope.perPage = records;
	};
	
	/*// Sort By update status of application list Like, Open, Pending, Accepted, Rejected... 
	$scope.sortByUpdateStatus = function(status) {
	    // Get submit Application by current user login with order by update status .....
	    userService.getApplicationsSortByStatus(status).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applications = response.data.applications;
		}else{
		    $scope.applications = [];
		}		
	    });    
	};*/
	
	// Set to statuses Object
	$scope.statuses = [
		    {value:'Opened', checked:false},
		    {value:'Pending', checked:false}, 
		    {value:'Accepted', checked:false}, 
		    {value:'Rejected', checked:false},
		  ];
	
	// Checked to all checkbox of filter...
	$scope.toggleAllStatus = function() {
	    var toggleStatus = $scope.isAllSelected;
	    angular.forEach($scope.statuses, function(val){ val.checked = toggleStatus; });
	    $scope.filterByUpdateStatus();
	}
	
	// Filter By update status of application list Like, All,  Open, Pending, Accepted, Rejected... 
	$scope.filterByUpdateStatus = function() {
		$scope.isAllSelected = $scope.statuses.every(function(val){ return val.checked; });
		
	    // Get submit Application by current user login with order by update status .....
	    userService.getApplicationsFilterByStatus($scope.statuses, $scope.isAllSelected).then(function (response) {
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
	
	$scope.trackModal = function(id) {
	    
	    //    universityService.getUniversityById(id).then(function (response) {
	    //	if (response.status == 200 && response.data.status == true){
	    //	    $scope.universityData =  response.data.university;
	    //	    $('#trackModal1').modal('show');
	    //	}
	    //    });
	    
	    userService.getApplicationById(id).then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.applicationData = response.data.application;
		    $('#trackModal1').modal('show');
		}		
	    });
	};
	
	/*$scope.universityModal = function(id){
		//var url = "/map?uid="+id;
       	//$location.url(url);
       	$state.go("app.map", {uid:id}, {inherit:false})
	};*/
	
	$('.datepicker-account').dcalendarpicker({
	    // default: mm/dd/yyyy
	    format: 'mm/dd/yyyy'
	});
    }
})();