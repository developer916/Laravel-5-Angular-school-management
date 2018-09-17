(function () {
    'use strict';

    angular
        .module('app.map')
        .controller('MapController', mapController);

    function mapController($compile, $scope, universities, universityService, $transitions, ModalService, $rootScope, $timeout, $stateParams, alertService) {
    	
        var vm = this;
        vm.universities = universities;
        vm.openModal = openModal;
        // vm.closeModal = closeModal;
        //$rootScope.university_data = {};

        $('.filter-close').find('img').on('click', function (e) {
            $('#filters').toggleClass('collapsed');
        });

        $scope.minSlider = {
            value: 100,
            options: {
                floor: 0,
                ceil: 100,
                step: 1
            }
        };

        var randomIcons = [];
        var local_icons = {
            pinIcon: {
                iconUrl: 'images/map/pin.png',
                shadowUrl: 'images/map/pin-shadow.png',
                iconSize:     [35.2, 44],
                shadowSize:   [42.4, 28],
                iconAnchor:   [18, 44],
                shadowAnchor: [2, 23],
                popupAnchor:  [0, -50]
            },

            favIcon: {
                iconUrl: 'images/map/pin-fav.png',
                shadowUrl: 'images/map/pin-shadow.png',
                iconSize:     [35.2, 44],
                shadowSize:   [42.4, 28],
                iconAnchor:   [18, 44],
                shadowAnchor: [2, 23],
                popupAnchor:  [0, -50]
            },

            ideaIcon: {
                iconUrl: 'images/map/pin-idea.png',
                shadowUrl: 'images/map/pin-shadow.png',
                iconSize:     [40, 44],
                shadowSize:   [42.4, 28],
                iconAnchor:   [18, 44],
                shadowAnchor: [2, 23],
                popupAnchor:  [0, -50]
            },

            timeIcon: {
                iconUrl: 'images/map/pin-time.png',
                shadowUrl: 'images/map/pin-shadow.png',
                iconSize:     [35.2, 44],
                shadowSize:   [42.4, 28],
                iconAnchor:   [18, 44],
                shadowAnchor: [2, 23],
                popupAnchor:  [0, -50]
            },
            engIcon: {
                iconUrl: 'images/map/pin-eng.png',
                shadowUrl: 'images/map/pin-shadow.png',
                iconSize:     [35.2, 44],
                shadowSize:   [42.4, 28],
                iconAnchor:   [18, 44],
                shadowAnchor: [2, 23],
                popupAnchor:  [0, -50]
            }
        };
        randomIcons = Object.keys(local_icons);
        //console.log(local_icons);
        //console.log(randomIcons);

		$scope.perPage = 12;	
		$scope.numOfPage = 0;
		$scope.universities = [];
        activate();
        function activate() {
            loadMarkers();
	    	// Get Universities on map University list page with markers...
	    	if($rootScope.search != true){
			    universityService.getUniversities().then(function (response) {
					if (response.status == 200 && response.data.status == true ){
					    $scope.universities = response.data.universities;
					    $scope.numOfPage = $scope.universities.length - 1; 
					}		
			    });
		    }
	    	
	    	// Search Universities By Filter On University list page with markers...
	    	if($stateParams.keywords !== undefined || $stateParams.location !== undefined || $stateParams.diploma !== undefined || $stateParams.speciality !== undefined){
	    		var searchData = {
	    							keywords : $stateParams.keywords,	
	    							ref_country_id : $stateParams.location,
	    							diploma : $stateParams.diploma,
	    							speciality : $stateParams.speciality,
	    						};
				universityService.searchUniversities(searchData).then(function(response) {
	                if (response.status == 200 && response.data.status == true ){
	                	$scope.universities = response.data.universities;
	                	vm.universities = response.data.universities;
	                	$scope.numOfPage = $scope.universities.length - 1; 
		                loadMarkers();
	               	}
	            });	
			}
	    	
	    	/*$scope.$on('loadUniversities', function(event, data) {
	    		vm.universities = universityService.getFilteredUniversities();
                loadMarkers();
            });*/
            
            // Search Universities Filter On University list page with markers...
            if($rootScope.search == true){
			    $scope.$on('loadUniversities', function(event, data) {
			        universityService.searchUniversities(data).then(function(response) {
		                if (response.status == 200 && response.data.status == true ){
		                	$scope.universities = response.data.universities;
		                	vm.universities = response.data.universities;
		                	$scope.numOfPage = $scope.universities.length - 1; 
		                	loadMarkers();
			           	}
		            });
		        });
	        }
	        
	        /*// Filter Universities price by semester On University list page with markers...
	    	if($stateParams.price !== undefined){
	    		var price =  $stateParams.price.split(',');
	    		var priceData = {
	    							from : price[0],	
	    							to : price[1],
	    						};
				universityService.filterUniversitiesByPrice(priceData).then(function(response) {
	                if (response.status == 200 && response.data.status == true ){
	                	$scope.universities = response.data.universities;
	                	vm.universities = response.data.universities;
	                	$scope.numOfPage = $scope.universities.length - 1; 
	                	loadMarkers();
		           	}
	            });
			}*/
	        
	        // Filter Universities price by semester On University list page with markers...
	        $scope.$on('universitiesFilterByPrice', function(event, data) {
	            universityService.filterUniversitiesByPrice(data).then(function(response) {
	                if (response.status == 200 && response.data.status == true ){
	                	$scope.universities = response.data.universities;
	                	vm.universities = response.data.universities;
	                	$scope.numOfPage = $scope.universities.length - 1; 
	                	loadMarkers();
		           	}
	            });
	        });
	        
	        //Filter Universities By Icons On University list page with markers...
	        $scope.$on('universitiesFilterByIcons', function(event, data) {
	        	if(data == 'English'){
					universityService.filterUniversitiesByIcons(data).then(function(response) {
		                if (response.status == 200 && response.data.status == true ){
		                	$scope.universities = response.data.universities;
		                	vm.universities = response.data.universities;
		                	$scope.numOfPage = $scope.universities.length - 1; 
		                	loadMarkers();
			           	}
	            	});	
				}else if(data == 'All'){
					universityService.getUniversities().then(function (response) {
						if (response.status == 200 && response.data.status == true ){
						    $scope.universities = response.data.universities;
						    vm.universities = response.data.universities;
						    $scope.numOfPage = $scope.universities.length - 1; 
						}
						loadMarkers();		
				    });
				}
	            
	        });
        }
	
		// Load More List content
		$scope.loadMore = function() {
		    $scope.perPage = $scope.perPage + 12;
		};

        function loadMarkers() {
          	var markers = {};
	    	for (var i = 0; i < vm.universities.length; i++) {
                var randomIconNumber;
                randomIconNumber = Math.floor(Math.random() * (randomIcons.length -1));
                var engIconflag = false;
                for(var j = 0; j < vm.universities[i].diplomas.length; j++){
					if(vm.universities[i].diplomas[j].language == 'English'){
						engIconflag = true;	
					}
				}
                /*var randomMarker = Math.floor((Math.random() * 10000) + 1);
                markers['marker' + randomMarker] = {*/
                markers['marker' + i + 1] = {
                    //lat: vm.universities[i].lat,
                    //lng: vm.universities[i].lng,
		    		lat: parseFloat(vm.universities[i].address.lat),
		    		lng: parseFloat(vm.universities[i].address.lng),
                    name: vm.universities[i].name,
                    message: "<popup-actions class='ng-cloak' university='university'></popup-actions>",
                    getMessageScope: (function (university) {
                        return {university: university};
                    })(vm.universities[i]),
                    focus: false,
                    draggable: false,
                    popupOptions: {
                        closeButton: false
                    },
                    icon:(engIconflag == true)? local_icons['engIcon'] : local_icons[randomIcons[randomIconNumber]],
                }
            }
	    
	  
	    angular.extend($scope, {
                center: {
                    zoom: 3,
                    maxZoom: 18,
                    minZoom: 7
                },
                maxbounds: {
                    northEast: {
                        // lat: 80.58972691,
                        // lng: 176.30859375
                        lat :84.39852,
                        lng: 190.35438
                    },
                    southWest: {
                        // lat: -52.05249048,
                        // lng: -167.51953125
                        lat: -84.93504,
                        lng: -167.47375
                    }
                },
                defaults: {
                    tileLayer: 'https://api.mapbox.com/styles/v1/flosej/cj04e1bwb00b42rl55cqmhywk/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZmxvc2VqIiwiYSI6IjViOTMwZWJmZmE1ZjRkM2U2ZTMxNWVjMDM4MmE3ZmE0In0.LVKTNtrY4NTV41q-DnfldQ',
                    maxZoom: 12,
                    minZoom: 3,
                },
                markers: markers
            });
        }
        $scope.$on('leafletDirectiveMarker.popupopen', function (e, args) {
            //$scope.university = args.model.getMessageScope.university;
            $scope.university = e.targetScope.markers[args.modelName].getMessageScope.university;
            var $container = $(args.leafletEvent.target._popup._container).find('.leaflet-popup-content-wrapper');
            var linkFunction = $compile(angular.element(args.leafletEvent.target._popup._content));
            $container.empty(); // remove all child nodes (like div .. )
            $container.append(linkFunction($scope));
            $("a.grouped_elements").fancyboxPlus();
        });

        $scope.$on('map-open', function(event,args) {
            var university_id = args.universityId;
            vm.university =  universityService.getUniversity(university_id).then(function (response) {
                return response.data;
            });
            $scope.modalPopupType = 'home';
            openModal('custom-modal-1');
        });

        $scope.$on('main-modal-open', function(event,args) {
            var university_id = args.universityId;
            vm.university = universityService.getUniversity(university_id).then(function (response) {
                return response.data;
            });
            $scope.modalPopupType = args.type;
            openModal('custom-modal-1');
        });

        $scope.$on('back-to-home', function(event, args){
            $scope.modalPopupType = 'home';
        });
        $scope.$on('back-to-housing', function(event, args){
            $scope.modalPopupType = 'housing';
        });
        $scope.$on('go-to-sub-housing' , function(args, value){
            $scope.modalPopupType = 'subHousing';
            $rootScope.$broadcast('sub-housing-show-slider');
        });

		// Open University Modal popup by click on student track application page..
		if($stateParams.uid !== undefined){
	    	var university_id = $stateParams.uid;
            var university_data = universityService.getUniversityById(university_id).then(function (response) {
		    	if (response.status == 200 && response.data.status == true){
		    		return response.data.university;    
		    	}
		    });
		    university_data.then(function(data) {
			   $scope.university = data;
			});
		    $scope.modalPopupType = 'home';
            openModal('custom-modal-1');
        }

        function openModal(id){
            $("#homeModal").addClass('in');
            // ModalService.Open(id);
            $('#custom-modal-1').fadeIn(300);
            $('.home-slider-image').fancyboxPlus();
        };
        $scope.closeModal = function(id){
            $("#homeModal").removeClass('in');
            // ModalService.Close(id);
            $('#custom-modal-1').fadeOut(300);
        };

        $scope.showSlider = function() {
            $rootScope.$broadcast('show-slider');
        };
    }
})();