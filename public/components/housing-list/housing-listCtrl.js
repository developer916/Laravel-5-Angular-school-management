(function () {
    'use strict';

    angular
        .module('app.housingList')
        .controller('HousingListController', housingListController);

    function housingListController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;
		$scope.perPage = 12;	
		$scope.numOfPage = 0;
		$scope.campuses = [];
		activate();
		function activate() {
		    
		    //Get All Campuses(Housing) list...
		    universityService.getCampuses().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.campuses = response.data.campuses;
				    $scope.numOfPage = $scope.campuses.length - 1; 
				}		
		    });
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.housingList");
		    }else{
				$state.go("app.map");
		    }
        }
	
		// Load More List content
		$scope.loadMore = function() {
		    $scope.perPage = $scope.perPage + 12;
		};
		
		// Edit Housing Detail by click on school User...
		$scope.editHousing = function(campus_id) {
		    $state.go("app.housing", {'campusId':campus_id});
		};
		
		// Delete Housing Detail by campus id...
		$scope.deleteHousing = function(campus_id, index) {
		    if (confirm("Are you sure you want to delete this housing?")) {
		        //$scope.campuses = [];
		        universityService.deleteHousing(campus_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
						//$scope.campuses = response.data.campuses;
						$scope.campuses.splice(index,1);
				    	$scope.numOfPage = $scope.campuses.length - 1; 
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
		    	});
		    }
		};
	
    }
})();