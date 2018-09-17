(function () {
    'use strict';

    angular
        .module('app.map')
        .directive('popupActions', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/popup/popupActions.html',
                controller: function ($scope, $rootScope) {
                    var vm = this;
                    vm.saveAsFavorite = function () {
                        /*universityService.saveAsFavorite($scope.university).then(function () {
                         });*/
                    };
                    $scope.showMainSlick = true;
                    $scope.slickSetting = {
                        dots:true,
                        infinite: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    };
                    $scope.mapOpenDialog = function(universityId) {
                        $rootScope.$broadcast('map-open', {universityId: universityId});
                    };
                    $scope.openMainModal = function(type, universityId){
                        $rootScope.$broadcast('main-modal-open', {type: type, universityId: universityId});
                    };
                    $scope.showOverview = false; 
                    $scope.showOverview1 = function(){
                    		if($scope.showOverview){
	                    		$('.popup-2').hide(300, function (f) {
							            $('.popup-carousel').show(300);
							            $scope.showOverview = false;
							    });
						    } else {
						        $('.popup-carousel').hide(300, function (f) {
							        $('.popup-2').show(300);
							        $scope.showOverview = true;
							    });
							}
                    };

                }
            }
        })
        .directive('popupMainInfo', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/popup/popupMainInfo.html',
                controller: function($scope){
                    $scope.showMainSlick = true;
                    $scope.slickSetting = {
                        dots:true,
                        infinite: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    };
                }
            }
        })
        .directive('popupOverview', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/popup/popupOverview.html'
            }
        })
        .directive('popupInfo', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/popup/popupInfo.html',
                controller: function ($scope, $rootScope) {
                    var vm = this;
                    
                    vm.getNextStart = function (terms) {
                    }
                    $scope.mapOpenDialog = function(universityId) {
                        $rootScope.$broadcast('map-open', {universityId: universityId});
                    };
                }
            }
        });
})();