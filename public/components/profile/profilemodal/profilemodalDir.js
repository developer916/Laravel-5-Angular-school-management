(function () {
    'use strict';

    angular
        .module('app.profile')
        .directive('profilemodalBasic', function () {
            return {
                restrict: 'E',
                scope: {
                    universityData: '=',
                    schoolProfile: '=',
                },
                //replace: true,
                templateUrl: 'components/profile/profilemodal/basic/basic.html',
                //controller: function ($scope, $rootScope) {
		    //$scope.university = $scope.universityData;
                    /*$scope.next_modal = function(id){
                        $rootScope.$broadcast('back-to-home', {mainPopup: 'home'});
                    	$scope.school_name = $scope.school_name;  
                    };*/
                //}
		link: function(scope, elem, attrs) {
                   //attrs.schoolProfile.school_name = attrs.universityData.name;
		   //scope.schoolProfile.school_name = attrs.universityData.name;
		   //console.log();
		},
		
            };
       	}).directive('profilemodalBasicupload', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/basicupload/basicupload.html',
                controller: function ($scope, $rootScope, $filter) {
                	$scope.uploadImage = '';
                },
            };
        }).directive('profilemodalBasicinfo', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/basicinfo/basicinfo.html',
            };
        }).directive('profilemodalBasicslogan', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/basicslogan/basicslogan.html',
            };
            
        }).directive('profilemodalBasicdetail', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/basicdetail/basicdetail.html',
                controller: function ($scope, $rootScope, $filter, userService) {
                	// Get All Reference of Country list...
				    userService.getRefCountry().then(function (response) {
						if (response.status == 200 && response.data.status == true ){
					    	$scope.refCountry = response.data.refCountry;
						}		
				    });
                },
            };        
             
        }).directive('profilemodalTerms', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/terms/terms.html',
            };   
            
        }).directive('profilemodalTermsview', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/termsview/termsview.html',
                controller: function ($scope, $rootScope, $filter) {
		    //$scope.calendarValue =  $filter('date')(new Date(),'MM/dd/yyyy');
		    $scope.nav_tab = function(n) { 
			$('.tab-content .tab-pane').removeClass('active');
			$('#term-'+n).addClass('active');
		    };
		},
            }; 
        }).directive('profilemodalSuccess', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                },
                templateUrl: 'components/profile/profilemodal/success/success.html',
                controller: function ($scope, $rootScope, $filter, $state) {
				    $scope.closeModal = function(id){
						$("#profileModal").removeClass('in');
						$('#custom-modal-2').fadeOut(300);
						$state.go("app.schoolFrontpage");
				    };
				},
            };     
           
        }).directive('jqCalendar', function () {
            return {
                restrict: 'A',
                scope: {
                    university: '=',
                    schoolProfile: '=',
		    termDate: '=',
		    model: '=',
	        },
                link: function(scope, elem, attrs) {
		    scope.model = attrs.termdate;
		    $(elem).dcalendar().on('selectdate', function(e){
			scope.$apply(function() {
			    scope.model = e.date;
			});
		    });
		},
            }; 
        }).directive('uploadDirective', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    university: '=',
                    schoolProfile: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
			var formData = new FormData();
			formData.append('file', elem[0].files[0]);
		       
			var fileObject = elem[0].files[0];
			if(fileObject.size > 205770 ){
					alert("Please upload file maximum 200 KB.");
					return true;
				}
			httpPostFactory('uploadImage', formData, scope, function (callback) {
			    if(callback.msg){
				scope.model = callback.data;
			    }
			});
		    });
		},
            };     
        }).factory('httpPostFactory', function($http) {
	    return function(file, data, scope, callback) {
		$http({
		    url: file,
		    method: "POST",
		    data: data,
		    headers: {
			'Content-Type': undefined
		    }
		}).then(function(response) {
		    callback(response.data);
		});
	    };			           
        });
})();