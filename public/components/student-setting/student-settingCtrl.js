(function () {
    'use strict';

    angular
        .module('app.studentSetting')
        .controller('StudentSettingController', studentSettingController);

    function studentSettingController($compile, $scope, $rootScope, $state, universityService, $transitions, $timeout, ModalService, userService, $cookies, alertService) {
        var vm = this;
        $scope.landingShow = false;
	$scope.setting_tab = 'account';
	$scope.userAccount = null;
        activate();
        function activate() {
	    if($rootScope.userType == 'student'){
	    	
		//Need to fetch user data if user already logged in
		if ($rootScope.authenticated) {
		    //var user_email =  $cookies.get('user_email');
		    userService.getUserAccountData().then(function (response) {
			if (response.status == 200){
			    $cookies.put('user_data', JSON.stringify(response.data.auth));
			    $rootScope.userData = response.data.auth;
			    $scope.userAccount.ref_country_id = $rootScope.userData.ref_country_id;
			    //$rootScope.userType = false;
			    //$rootScope.userType = response.data.auth.type;
			    $state.go("app.studentSetting");
			}
		    });
		}		
		    
	    }else if($rootScope.userType == 'school'){
		$state.go("app.profile");
	    }else{
		$state.go("app.map");
	    }
	    	    
        }	
	
	// Get All Reference of Country list...
	$scope.refCountry = [];
	userService.getRefCountry().then(function (response) {
	    if (response.status == 200 && response.data.status == true ){
			$scope.refCountry = response.data.refCountry;
	    }		
	});
	
	// Get Reference of languages...
    universityService.getRefLanguage().then(function (response) {
		if (response.status == 200 && response.data.status == true ){
		    $scope.refLanguage = response.data.language;
		}		
    });
	
	/**** Update Current user password  ****/
	vm.updatePassword = function (customer) {
	    //customer.user_id = $rootScope.userData.id;
	    userService.updatePassword(customer).then(function (response) {
		if (response.status == 200 && response.data.token ){
		    $cookies.put('user_token', response.data.token);
		    if(response.data.auth.type == 'school'){
			//$rootScope.userType = 'school';
			//$state.go("app.profile");
		    }else{
			
			//$rootScope.userType = 'student';
			//$state.go("app.studentProfile");
		    }
		    alertService.add("success", response.data.msg);
		}else{
		    alertService.add("danger", response.data.msg);
		}		
	    });
        }
	
	/**** Update Current user Account Detail ****/
	vm.updateAccount = function (customer) {
	    userService.updateAccount(customer).then(function (response) {
			if (response.status == 200 && response.data.status == true ){
			    //$cookies.put('user_token', response.data.token);
			    if(response.data.auth.id){
				$cookies.put('user_email', response.data.auth.email);
				$cookies.put('user_data', JSON.stringify(response.data.auth));
				$rootScope.userData = response.data.auth;
				$scope.userAccount.ref_country_id = $rootScope.userData.ref_country_id;
			    }
			    alertService.add("success", response.data.msg);
			}else{
			    alertService.add("danger", response.data.msg);
			}		
		});
    }
    
	$('.datepicker-account').val($rootScope.userData.Bdate);
	$('.datepicker-account').dcalendarpicker({
		format: 'dd-mm-yyyy',
		//default: $rootScope.userData.Bdate,
	}).on('dateselected', function(e){
		$scope.userAccount.Bdate = e.date;
	});
    }
})();