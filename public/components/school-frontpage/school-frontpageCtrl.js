(function () {
    'use strict';

    angular
        .module('app.schoolFrontpage')
        .controller('SchoolFrontpageController', schoolFrontpageController);

    function schoolFrontpageController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;
		$scope.university = {};
		activate();
        function activate() {
	   		// Get University Profile Details...
		    universityService.getUniversityProfile().then(function (response) {
			    if (response.status == 200 && response.data.auth){
					$scope.university =  response.data.auth;
					$rootScope.universityData = response.data.auth;
					//Set to default Profile Picture...
					if($scope.uploadPicture === undefined){
					    $scope.uploadPicture = $rootScope.universityData.logo_link;
					}
			    }
		    });
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.schoolFrontpage");
		    }else{
				$state.go("app.map");
	    	}
        }
	
		/**** Save Housing Detail ****/
		vm.saveFrontpage = function (university) {
		    universityService.saveFrontpage(university).then(function (response) {
				if(response.status == 200 && response.data.status == true ){
				    $scope.university = response.data.university;
				    $scope.ufiles = {};
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}		
		    });
	    }
	
		// Delete Images of University With folder and Databse...
		$scope.deleteUniversityImages = function(index, type, pictureId) {
		    if(type == 'ufile'){
				var fileName = $scope.ufiles[index];
				$scope.universityFrom.pictures = [];
				$scope.ufiles.splice(index,1);
		    }else{
				var fileName = $scope.university.pictures[index].link;
				index = pictureId;
			}
	    
		    universityService.deleteUniversityImages(fileName, index, type).then(function(response){
				if(response.status == 200 && response.data.status == true ){
				    if(response.data.type == 'picture'){
					$scope.university = response.data.university;
				    }
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}        
		    });
		};
    }
})();