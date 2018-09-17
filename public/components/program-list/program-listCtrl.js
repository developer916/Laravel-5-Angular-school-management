(function () {
    'use strict';

    angular
        .module('app.programList')
        .controller('ProgramListController', programListController);

    function programListController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;
        $scope.perPage = 12;	
		$scope.numOfPage = 0;
		$scope.programs = [];
		activate();
		function activate() {
	    	//Get Programs List....
		    universityService.getPrograms().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.programs = response.data.programs;
				    $scope.numOfPage = $scope.programs.length - 1; 
				}		
		    });
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.programList");
		    }else{
				$state.go("app.map");
		    }
        }
	
		// Load More List content
		$scope.loadMore = function() {
		    $scope.perPage = $scope.perPage + 12;
		};
		
		// Edit Program Detail by click on school User...
		$scope.editProgram = function(program_id) {
		    $state.go("app.program", {'programId':program_id});
		};
		
		// Delete Program by program id...
		$scope.deleteProgram = function(program_id, index) {
			if (confirm("Are you sure you want to delete this program?")) {
		        //$scope.programs = [];
		        universityService.deleteProgram(program_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
						//$scope.programs = response.data.programs;
						$scope.programs.splice(index,1);
				    	$scope.numOfPage = $scope.programs.length - 1; 
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
		    	});
		    }
		};
		
		// Copy Program by program id...
		$scope.copyProgram = function(program_id) {
		    if (confirm("Do you want to copy this program?")) {
		        //$scope.programs = [];
		        universityService.copyProgram(program_id).then(function (response) {
					if (response.status == 200 && response.data.status == true ){
						//$scope.programs = response.data.programs;
						$scope.programs.push(response.data.program);
				    	$scope.numOfPage = $scope.programs.length - 1; 
					    alertService.add("success", response.data.msg);
					}else{
					    alertService.add("danger", response.data.msg);
					}		
		    	});
		    }
		};
    }
})();