(function () {
    'use strict';

    angular
        .module('app.housing')
        .controller('HousingController', housingController);

    function housingController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, alertService, $cookies, $stateParams) {
        var vm = this;
        $scope.landingShow = false;               
		$scope.data ={
		    accomodation: null,
		    room_type: 1,
		}; 
		$scope.rfiles = {};
		$scope.dfiles = {};
		$scope.amenities = [];	   
		activate();
		function activate() {
			//Get Amenities List...
		    universityService.getAmenities().then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.amenities = response.data.amenities;
				}else{
				    $scope.amenities = [
					{id: 1, libelle: 'Television', img_link: 'image/television.png'},
					{id: 2, libelle: 'Microwave', img_link: 'image/microwave.png'},
					{id: 3, libelle: 'Stove', img_link: 'image/stove.png'},
					{id: 4, libelle: 'Refrigerator', img_link: 'image/refrigerator.png'},
				    ]; 
				}		
		    });
		    
		    // Get Campus Details By campus id...
		    if($stateParams.campusId !== undefined){
				universityService.getCampusById($stateParams.campusId).then(function (response) {
					if(response.status == 200 && response.data.status == true ){
						$scope.housing.id = response.data.campus.id;
					    $scope.housing.name = response.data.campus.name;
					    $scope.housing.address = response.data.campus.address.address_name;
					    $scope.housing.accomodation = response.data.campus.accomodation;
					    //$scope.data.accomodation = response.data.campus.accomodation;
					    $scope.housing.room_type = response.data.campus.roomObj.length;
					    //$scope.data.room_type = response.data.campus.roomObj.length;
		    			$scope.housing.roomObj = response.data.campus.roomObj;
		    			//$scope.housing.dromAmenitiesObj = response.data.campus.dromAmenitiesObj;
		    			$scope.housing.drom_amenities = response.data.campus.drom_amenities;
		    			$scope.housing.campus_dorm_pictures = response.data.campus.campusDormPictures;
		    			if(response.data.campus.campusDormItems.length > 0){
							$scope.items = response.data.campus.campusDormItems.length;
		    				$scope.housing.dormItems = response.data.campus.campusDormItems;
		    			}
		    			
		    			$scope.housing.campus_pictures = response.data.campus.campusPictures;
		    			$scope.housing.description = response.data.campus.description;
		    		}		
			    });
			}
		    
		    if($rootScope.userType == 'student'){
		    	    
		    }else if($rootScope.userType == 'school'){
				$state.go("app.housing");
		    }else{
				$state.go("app.map");
		    }
		}
		
	
		/**** Save Housing Detail ****/
		vm.saveHousing = function (housing) {
		    universityService.saveHousing(housing).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $state.go("app.housingList");
			    alertService.add("success", response.data.msg);
			}else{
			    alertService.add("danger", response.data.msg);
			}		
		    });
        }
        
        // Go to Housing List Page...
        vm.cancelHousing = function (housing) {
        	$state.go("app.housingList");
		}
        
        // Delete Images of Campus Room With folder and Databse...
		$scope.deleteCampusRoomImages = function(index, type, roomIndex, pictureId, campusRoomTypeId) {
		    if(type == 'rfile'){
		    	var fileName = $scope.rfiles[roomIndex][index];
				//var fileName = $scope.housing.roomObj[roomIndex].campusRoomTypePictures[index];
				$scope.housing.roomObj[roomIndex].campusRoomTypePictures[index] = [];
				$scope.rfiles[roomIndex].splice(index,1);
				campusRoomTypeId = 0;
		    }else{
				var fileName = $scope.housing.roomObj[roomIndex].campusRoomType_pictures[index].link;
				index = pictureId;
			}
		 	universityService.deleteCampusRoomImages(fileName, index, type, campusRoomTypeId).then(function(response){
				if(response.status == 200 && response.data.status == true ){
				    if(response.data.type == 'picture'){
						$scope.housing.roomObj[roomIndex].campusRoomType_pictures = response.data.campusRoomTypePictures;
				    }
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}        
		    });
	    };
	    
	    // Delete Images of Campus Dorm With folder and Databse...
		$scope.deleteCampusDormImages = function(index, type, pictureId, campusId){
		    if(type == 'file'){
		    	var fileName = $scope.dfiles[index];
				//var fileName = $scope.housing.campusDormPictures[index];
				$scope.housing.campusDormPictures[index] = [];
				$scope.dfiles.splice(index,1);
				campusId = 0;
		    }else{
				var fileName = $scope.housing.campus_dorm_pictures[index].link;
				index = pictureId;
			}
		 	universityService.deleteCampusDormImages(fileName, index, type, campusId).then(function(response){
				if(response.status == 200 && response.data.status == true ){
				    if(response.data.type == 'picture'){
						$scope.housing.campus_dorm_pictures = response.data.campusDormPictures;
				    }
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}        
		    });
	    };
	    
	    // Delete Images of Campus With folder and Databse...
		$scope.deleteCampusImages = function(index, type, pictureId, campusId) {
		    if(type == 'file'){
		    	var fileName = $scope.files[index];
				//var fileName = $scope.housing.campusPictures[index];
				$scope.housing.campusPictures[index] = [];
				$scope.files.splice(index,1);
				campusId = 0;
		    }else{
				var fileName = $scope.housing.campus_pictures[index].link;
				index = pictureId;
			}
		 	universityService.deleteCampusImages(fileName, index, type, campusId).then(function(response){
				if(response.status == 200 && response.data.status == true ){
				    if(response.data.type == 'picture'){
						$scope.housing.campus_pictures = response.data.campusPictures;
				    }
				    alertService.add("success", response.data.msg);
				}else{
				    alertService.add("danger", response.data.msg);
				}        
		    });
	    };
	    
	    
	    
	    //Add new text box of What to bring...
		$scope.items = 1;
		$scope.addItems = function() {
		    $scope.items++;
		}	
    }
})();