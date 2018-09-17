(function () {
    'use strict';

    angular
        .module('app.map')
        .directive('modalHome', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/home/home.html',
            };
        }).directive('modalDiploma', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/diploma/diploma.html',
                controller: function ($scope, $rootScope, universityService) {
                	$scope.program = { diploma_study_id: '', level_of_study: {} };
                    $scope.backToHome = function(){
                      $rootScope.$broadcast('back-to-home', {mainPopup: 'home'});
                    };
                    
                    // Get diploma study list...
				    universityService.getDiplomaStudy().then(function (response) {
						if (response.status == 200 && response.data.status == true ){
						    $scope.diplomaStudy = response.data.diplomaStudy;
						}		
				    });
				    
				    // Get diploma level of study list...
				    universityService.getDiplomaLevelStudy().then(function (response) {
						if (response.status == 200 && response.data.status == true ){
						    $scope.diplomaLevelStudy = response.data.diplomaLevelStudy;
						    angular.forEach($scope.diplomaLevelStudy, function(val){ val.checked = true; });
						}		
				    });
                    
                    /*$('#radio-all').change(function(e){
                        $('#radio-ug').prop("checked",this.checked);
                        $('#radio-pg').prop("checked",this.checked);
                        $('#radio-phd').prop("checked",this.checked);
                        $('#radio-ot').prop("checked",this.checked);
                        $('label[for="radio-ug"]').toggleClass('disabled-x');
                        $('label[for="radio-pg"]').toggleClass('disabled-x');
                        $('label[for="radio-phd"]').toggleClass('disabled-x');
                        $('label[for="radio-ot"]').toggleClass('disabled-x');
                    });
                    $('#radio-all').prop("checked",'true');
                    $('#radio-all').change();*/
                    
                    // Checked to all checkbox of filter...
                    $scope.isAllSelected = true;
					$scope.toggleAllLevel = function() {
					    var toggleLevel = $scope.isAllSelected;
					    angular.forEach($scope.diplomaLevelStudy, function(val){ val.checked = toggleLevel; });
					    $('.level-of-study label').toggleClass('disabled-x');
					};
					
                    $scope.panelscroll = function(e){
                    	var topPos = e.pageY;
					    $('.modal-body').animate({
					      	scrollTop: topPos  + 250
					    },1000);
					};
                }
            };
        }).directive('modalMessage', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/message/message.html',
            };
        }).directive('modalApplication', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/application/application.html',
                controller: function ($scope, $rootScope, userService, ModalService, alertService) {
                    
                    // Get Document Type list ...
                    $scope.documentTypes = {};
                    userService.getDocumentTypes().then(function (response) {
                        if (response.status == 200 && response.data.status == true ){
                            $scope.documentTypes = response.data.documentTypes;
                        }		
                    });
        
                    $scope.applyForm = {};
                    $scope.applyApplication = function(data){
                        userService.applyApplication(data).then(function (response) {
                            ModalService.Close('custom-modal-1');
                            if (response.status == 200 && response.data.status == true ){
                                alertService.add("success", response.data.msg);
                            }else{
                                alertService.add("danger", response.data.msg);
                            }
                            $scope.applyForm = {};
                        });
                    };
                },
            };
        }).directive('uploadDocumentSend', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    model: '=',
		    document_type_id: '=',
                },
                link: function(scope, elem, attrs) {
		    elem.bind('change', function() {
			if(attrs.documentTypeId === undefined || attrs.documentTypeId == ''){
			    alert("Please choose any document type.");
			    return false;
			}
			var formData = new FormData();
			formData.append('file', elem[0].files[0]);
			
			var fileObject = elem[0].files[0];
			//if(fileObject.size > 205770 ){
			//		alert("Please upload file maximum 200 KB.");
			//		return true;
			//}
			httpPostFactory('uploadDocumentSend', formData, scope, function (callback) {
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
        }).directive('modalSubHousing', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/housing/subHousing.html',
                controller: function ($scope, $rootScope, $timeout) {
                    $scope.showSubHousingSlick = false;
                    $scope.$on('go-to-sub-housing' , function(args, value){
                        $scope.modalPopupType = 'subHome';
                        $scope.template = value.content;
                        $scope.housing = value.content;
                        $scope.index = value.index;
                        $rootScope.$broadcast('sub-housing-show-slider', {content : value.content, index : value.index});
                        var mydir='<my-directive></my-directive>';
						var compiled=$compile(mydir)($scope);
						$timeout(function(){
						      $scope.slickSubHousingSetting.method.slickAdd(compiled);
						})
                    });
                    $scope.itemImages = [
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        },
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        },
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        },
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        },
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        },
                        {
                            imgSrc : 'images/home/carousel-2.jpg'
                        }
                    ];
                    // $("a.subHousingImage").fancyboxPlus();
                    $scope.slickSubHousingSetting = {
                        infinite: true,
                        slidesToShow: 3,
                        draggable: true,
                        responsive: [
                            {
                                breakpoint: 568,
                                settings: {
                                    arrows: false,
                                    centerMode: true,
                                    centerPadding: '40px',
                                    slidesToShow: 1
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    arrows: false,
                                    centerMode: true,
                                    centerPadding: '40px',
                                    slidesToShow: 1
                                }
                            }
                        ],
                        slidesToScroll: 1,
                    };
                    

                    //$("a.subHousingImage").fancyboxPlus();
                    $scope.$on('sub-housing-show-slider', function(args, value){
                        $scope.showSubHousingSlick = true;
                       	//$('#sliderShow').slick('unslick'); //terminates (only run if slick is initialized)
						//update view now
						//$('#sliderShow').slick(); //start
					   
                    });
                    $scope.backToHousing = function(){
                        $rootScope.$broadcast('back-to-housing', {mainPopup: 'housing'});
                    };
                }
            };
        }).directive('modalHousing', function () {
            return {
                restrict: 'E',
                scope: {
                    university: '='
                },
                templateUrl: 'components/map/modal/housing/housing.html',
                controller: function($scope, $rootScope,$timeout){
                    $scope.showSlick = false;
                    $scope.messItUp = function() {
                        $scope.dataLoaded = false;
                        $scope.items = [
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM A',
                                meters : '500',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'

                            },
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM B',
                                meters : '500',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'
                            },
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM C',
                                meters : '500 ',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'
                            },
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM D',
                                meters : '500 ',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'
                            },
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM E',
                                meters : '500 ',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'
                            },
                            {
                                imgSrc: 'images/home/carousel-2.jpg',
                                domName: 'DOM F',
                                meters : '500 ',
                                singleRoom: '9',
                                doubleRoom : '18',
                                location : '16, Chelsea Avenue, Kingston 5, Jamaica, W.I.'
                            }
                        ];
                        $timeout(function(){
                            $scope.dataLoaded = true;
                        },2000);
                    };
                    $scope.messItUp();

                    $scope.$on('show-slider', function() {
                        $scope.showSlick = true;
                    });

                    $scope.breakpoints = [
                        {
                            breakpoint: 968,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }, {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ];
                    $scope.currentIndex =0;
                    $scope.slickPanels = {
                        method: {},
                        infinite: true,
                        speed: 300,
                        slidesToShow: 3,
                        lazyLoad: 'ondemand',
                        slidesToScroll: 1,
                        autoPlay: false,
                        adaptiveHeight: true,
                        centerMode: true,
                        event: {
                            beforeChange: function() {
                            },
                            afterChange: function(event, slick, currentSlide) {
                               $scope.currentIndex = currentSlide;
                            }
                        }
                    };
                    /*$scope.goToSubHousing = function(){
                        $rootScope.$broadcast('go-to-sub-housing', {content : $scope.items[$scope.currentIndex] , index : $scope.currentIndex});
                    };*/
                    
                    $scope.goToSubHousing = function(currentIndex){
                        $rootScope.$broadcast('go-to-sub-housing', {content : $scope.university.housing[currentIndex] , index : currentIndex});
                    };
                }
            };
        });
})();