(function () {
    'use strict';

    angular
        .module('app.header')
        .controller('HeaderController', headerController)
	.filter('moment', function () {
	  return function (input, momentFn /*, param1, param2, ...param n */) {
	    var args = Array.prototype.slice.call(arguments, 2),
		momentObj = moment(input);
	    return momentObj[momentFn].apply(momentObj, args);
	  };
	});

    function headerController(universityService, $scope, $state, $rootScope, $transitions, ModalService, userService, $cookies, alertService, User, $interval, $location) {

        var vm = this;
        vm.textSearch = null;
        vm.universitiesCountryList = universityService.getUniversitiesCountryList();
        vm.universitiesDiplomaList = universityService.getUniversitiesDiplomaList();
        vm.universitiesSpecialityList = universityService.getUniversitiesSpecialityList();
        vm.submitSearch = submitSearch;
        vm.openSearch = openSearch;
        vm.closeSearch = closeSearch;
		$scope.icon_map_list = false;
		$scope.schoolSubHeader = false;
		$rootScope.universityData = {};
		//$scope.icon_filter = false;
        //vm.openModal_profile = openModal_profile;
	
	
        $transitions.onSuccess({ }, function(trans) {
            if(trans.to().name == "app.profile" || trans.to().name == "app.profile.messages" || trans.to().name == "app.profile.home" || trans.to().name == "app.studentProfile" || trans.to().name == "app.studentSetting" || trans.to().name == "app.studentTrack" || trans.to().name == "app.housingList" || trans.to().name == "app.housing" || trans.to().name == "app.programList" || trans.to().name == "app.program" || trans.to().name == "app.application" || trans.to().name == "app.chat" || trans.to().name == "app.schoolFrontpage" || trans.to().name == "app.viewStudentProfile"
	    ){
                $scope.landingShow = false;
	    	}else{
                $scope.landingShow = true;
            }
	    
		    if(trans.to().name == "app.programList" || trans.to().name == "app.program"){
				$scope.schoolMainMenu = 'programs';
		    }else if(trans.to().name == "app.housingList" || trans.to().name == "app.housing"){
				$scope.schoolMainMenu = 'housing';
		    }else if(trans.to().name == "app.application"){
				$scope.schoolMainMenu = 'application';
		    }else{
				$scope.schoolMainMenu = false;
		    }
	    
		    // Set to Sub header of school user profile page....
		    if(trans.to().name == "app.schoolFrontpage" || trans.to().name == "app.programList" || trans.to().name == "app.program"
		       || trans.to().name == "app.housingList" || trans.to().name == "app.housing"){
				$scope.schoolSubHeader = true;
		    }else{
				$scope.schoolSubHeader = false;
		    }
	    });
	
        // $rootScope.$on('$stateChangeStart', function(e, toState, toParams, fromState, fromParams) {
        //     console.log(toState);
        //     if(toState.name == "app.profile"){
        //         $scope.landingShow = false;
        //     }else{
        //         $scope.landingShow = true;
        //     }
        // });


        $scope.landingShow  = true;

        if($state.current.name =="app.profile" || $state.current.name == "app.profile.messages" || $state.current.name == "app.profile.home"
	   || $state.current.name == "app.studentProfile" || $state.current.name == "app.studentSetting"
	   || $state.current.name == "app.studentTrack" || $state.current.name == "app.housingList" || $state.current.name == "app.housing"
	   || $state.current.name == "app.programList" || $state.current.name == "app.program" || $state.current.name == "app.application" || $state.current.name == "app.chat" || $state.current.name == "app.schoolFrontpage" || $state.current.name == "app.viewStudentProfile" || $state.current.name == "app.viewStudentProfile" 
	){
            $scope.landingShow = false;
		}else{
            $scope.landingShow = true;
        }
	
		if($state.current.name == "app.programList" || $state.current.name == "app.program"){
		    $scope.schoolMainMenu = 'programs';
		}else if($state.current.name == "app.housingList" || $state.current.name == "app.housing"){
		    $scope.schoolMainMenu = 'housing';
		}else if($state.current.name == "app.application" ){
		    $scope.schoolMainMenu = 'application';
		}else{
		    $scope.schoolMainMenu = false;
		}
		
	        // Set to Sub header of school user profile page....
		if($state.current.name == "app.schoolFrontpage" || $state.current.name == "app.housingList" || $state.current.name == "app.housing"
		   || $state.current.name == "app.programList" || $state.current.name == "app.program"
		){
		    $scope.schoolSubHeader = true;
		}else{
	            $scope.schoolSubHeader = false;
	    }
		
		$("#filter_by_price").ionRangeSlider({
		    type: "double",
		    //grid: true,
		    min: 0,
		    max: 30000,
		    from: 0,
		    to: 15000,
		    prefix: "$",
		    onFinish: function (data) {
		        priceByFilter(data);
		    },
		});
		
		
		//$(".filter-btn").click(function(){
		//    $(".filter-main").toggle();
		//});
	
		$(".filter-btn .filter-show").click(function(){
		    $(".filter-btn .filter-hide").show();
		    $(".filter-btn .filter-show").hide();
		    $(".filter-main").slideToggle('fast');
		});
		
		$(".filter-btn .filter-hide").click(function(){
		    $(".filter-btn .filter-show").show();
		    $(".filter-btn .filter-hide").hide();
		    $(".filter-main").slideToggle('fast');
		});
	
        $scope.lastSendUserMessages = [];
        activate();
        function activate() {
		    //get by chat last send user ...
			//User.getByChatLastSendUser().then(function (response) {
			//	$scope.lastSendUserMessages = response;
			//});
		    
		    //alertService.add("warning", "This is a warning.");
		    //alertService.add("danger", "This is an error!");
		    //alertService.add("success", "Successfuly updated password.");
        }	
	
		// Get All Reference of Country list...
		$scope.refCountry = [];
		userService.getRefCountry().then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
				$scope.refCountry = response.data.refCountry;
		    }		
		});
	
		// Get All Programs(Diplomas)...
		$scope.programsData = [];
		universityService.getAllPrograms().then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
				$scope.programsData = response.data.programs;
		    }		
		});
	
		// Get All Speciality of Programs(Diplomas)...
		$scope.specialityData = [];
		universityService.getAllSpeciality().then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
				$scope.specialityData = response.data.speciality;
		    }		
		});
	
		//get by chat last send user ...
		$interval(function(){
			if($rootScope.authenticated){
			    User.getByChatLastSendUser().then(function (response) {
					$scope.lastSendUserMessages = response;
			    });
			}
		},3000);
	
		//Go to chat by user id into chat application page.. 
		$scope.selectChatUser = function(user_id){
		    $state.go("app.chat", {'user':user_id});
	    }
	
		// Get TMAP Notification list by School user Update Status ...
		$scope.lastUpdateStautsTrackNotification = [];
		$interval(function(){
			if($rootScope.authenticated){
			    userService.getUpdateStatusTrackNotification().then(function (response) {
					if (response.status == 200 &&  response.data.status == true){
					    $scope.lastUpdateStautsTrackNotification = response.data.notification;
					}
			    });
			}
		},3000);
	
		// Get Document Type list ...
		$scope.document_type_id = {};
		$scope.documentTypes = {};
		userService.getDocumentTypes().then(function (response) {
		    if (response.status == 200 && response.data.status == true ){
				$scope.documentTypes = response.data.documentTypes;
		    }		
		});
		
		// open Track Modal and get application data by application id...
		$scope.trackModal = function(id) {
		    userService.getApplicationById(id).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    $scope.applicationData = response.data.application;
			    $('#trackModal2').modal('show');
			}		
		    });
		};
	
		// Update Application status By Value...
		$scope.updateApplicationStatus = function(value) {
		    userService.updateApplicationStatus($scope.applicationData.id, value).then(function (response) {
				if (response.status == 200 && response.data.status == true ){
				    $scope.applications = response.data.applications;
				    if(value != 'Review'){
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
	
		// View student profile By application id and student id...
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

		// root binding for alertService
		$rootScope.closeAlert = alertService.closeAlert;
  
        function closeSearch() {
            // $('#overlay').fadeOut(500);
            // $('.searchbox').css('opacity', 1).slideUp(500).animate({opacity: 0}, {queue: false, duration: 500});
            $("#searchModal").modal('hide');
        }

        function openSearch() {
            $('#overlay').fadeIn(500);
            $('.searchbox').css('opacity', 0).slideDown(500).animate({opacity: 1}, {queue: false, duration: 500});
        }

        /**
         * Submit search with filters
         */
        function submitSearch() {
            universityService.setUniversitiesFilters(getDataSearch()).then(function () {
                $scope.$broadcast('loadUniversities');
                closeSearch();
            });
        }

        /**
         * Parse data search
         * @returns object search
         */
        function getDataSearch(){
            var dataSearch = {$: {}};
            if (vm.textSearch) dataSearch.$.$ = vm.textSearch;
            if (vm.countrySearch) dataSearch.$.country = vm.countrySearch;
            if (vm.diplomaSearch) dataSearch.$.name = vm.diplomaSearch;
            if (vm.specialitySearch) dataSearch.$.speciality = vm.specialitySearch;
            return dataSearch;
        }
        
        /**
         * Parse data search
         * Submit search with Universites filters
         * @returns object search Data
         */
        $rootScope.search = false;
        //$rootScope.universities = [];
        $scope.searchUniversities = function(dataSearch){
        	$rootScope.search = true;
        	closeSearch();
        	//$scope.icon_map_list = true;
        	$scope.$broadcast('loadUniversities', dataSearch);
        	
        	var url = "/map";
        	var queryStringObj = [];
        	if(dataSearch.keywords && dataSearch.keywords !== undefined){
				queryStringObj.push("keywords=" + dataSearch.keywords);
			}
			if(dataSearch.ref_country_id && dataSearch.ref_country_id !== undefined){
				queryStringObj.push("location=" + dataSearch.ref_country_id);
			}
			if(dataSearch.diploma && dataSearch.diploma !== undefined){
				queryStringObj.push("diploma=" + dataSearch.diploma);
			}
			if(dataSearch.speciality && dataSearch.speciality !== undefined){
				queryStringObj.push("speciality=" + dataSearch.speciality);
			}
        	var queryString = queryStringObj.join('&');
        	if(queryString){
				url +='?'+queryString;
			}
  			$location.url(url);
  			
            /*universityService.searchUniversities(dataSearch).then(function(response) {
                if (response.status == 200 && response.data.status == true ){
                	closeSearch();
                	$rootScope.universities = response.data.universities;
                	$scope.icon_map_list = true;
                	$state.go("app.map");
                	//$scope.$broadcast('loadUniversities');
                }
            });*/
        };
        
        //University filter Price By semester .. 
        function priceByFilter(data){
			var price = {
						from:data.from,
						to:data.to,
			};
			//$rootScope.search = true;
			//$scope.icon_map_list = true;
			$scope.$broadcast('universitiesFilterByPrice', price);
			
			/*var url = "/map";
        	var queryStringObj = [];
        	if(price.to !== undefined){
				queryStringObj.push("priceRange=" + price.from +","+ price.to);
			}
			var queryString = queryStringObj.join('&');
        	if(queryString){
				url +='?'+queryString;
			}
  			$location.url(url);*/
		}
		
		//University Filter By Icons...
		$scope.selectedIcons = '';
		$scope.iconByFilter = function(val){
			$scope.selectedIcons = val;
			//$scope.icon_map_list = true;
			$scope.$broadcast('universitiesFilterByIcons', val);							
		};
         
        $scope.closeMainModal = function(id){
            ModalService.Close(id);
        };

		//Need to fetch user data if user already logged in
        if ($rootScope.authenticated) {
		    var user_email =  $cookies.get('user_email');
		    userService.getUserData(user_email).then(function (response) {
				if (response.status == 200){
				    $cookies.put('user_data', JSON.stringify(response.data.auth));
				    $rootScope.userData = response.data.auth;
				    //$rootScope.userType = false;
				    $rootScope.userType = response.data.auth.type;
				    //$state.go("app.profile");
				}
		    });
		    
		    if($rootScope.userType == 'school'){
				universityService.getUniversityProfile().then(function (response) {
				    if (response.status == 200 && response.data.auth){
						$rootScope.universityData =  response.data.auth;
						//Set to default Profile Picture...
						if($scope.uploadPicture === undefined){
						    $scope.uploadPicture = $rootScope.universityData.logo_link;
						}
				    }
				});
		    }
		}
		
		//$rootScope.authenticated = false;
        vm.doLogin = function (customer) {
		    userService.DoLogin(customer).then(function (response) {
				if (response.status == 200 && response.data.token){
				    $('#signinModal').modal('toggle');
				    $('.modal-backdrop').remove();
				    $cookies.put('user_token', response.data.token);
				    $cookies.put('user_email', response.data.auth.email);
				    $cookies.put('user_type', response.data.auth.type);
				    $cookies.put('user_data', JSON.stringify(response.data.auth));
				    $rootScope.authenticated = true;
				    $rootScope.userData = response.data.auth;
				    $rootScope.userType = response.data.auth.type;
				    if(response.data.auth.type == 'school'){
						$rootScope.universityData = response.data.university;
						if($scope.uploadPicture === undefined){
						    $scope.uploadPicture = $rootScope.universityData.logo_link;
						}
						$state.go("app.schoolFrontpage");
				    }else{
						$state.go("app.studentProfile");
				    }
				}
		    });
        }
        
        // SignUp User for this function..
        vm.signUp = function (customer) {
		    userService.SignUp(customer).then(function (response) {
				if (response.status == 200 && response.data.token){
				    $('#signupModal').modal('toggle');
				    $('.modal-backdrop').remove();
				    $cookies.put('user_token', response.data.token);
				    $cookies.put('user_email', response.data.auth.email);
				    $cookies.put('user_type', response.data.auth.type);
				    $cookies.put('user_data', JSON.stringify(response.data.auth));
				    $rootScope.authenticated = true;
				    $rootScope.userData = response.data.auth;
				    if(response.data.auth.type == 'school'){
						$rootScope.userType = 'school';
						$state.go("app.profile");
				    }else{
						$rootScope.userType = 'student';
						$state.go("app.studentProfile");
				    }
				}
		    });
        }
      	
        // Click On Logout link it will be logout user...
        vm.logout = function (customer) {
		    if ($rootScope.authenticated) {
				$cookies.remove('user_token');
				$cookies.remove('user_email');
				$cookies.remove('user_type');
				$cookies.remove('user_data');
				$rootScope.authenticated = false;
				$rootScope.userType = false;
				$rootScope.userData = null;
				$scope.uploadPicture = undefined;
		    }
        }
    }
})();