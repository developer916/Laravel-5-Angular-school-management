app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
       
    $scope.login = {};
    $scope.signup = {};
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
            	/*$('.dropdown-menu li').hide();
            	$('.dropdown-menu li#logout').show();*/
            	console.log($location);
                //$('#myModal').modal('toggle');
                //$location.path('dashboard');
                window.location.href = "localhost/blog1/public/messages/";
                
            }
        });
    };
    $scope.signup = {email:'',password:'',name:'',phone:'',address:''};
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
            	$('.bs-example-modal-lg').modal('toggle');
                $location.path('dashboard');
            }
        });
    };
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
    
    /*$scope.$on("userLoggedIn",function(event,args) {
    
	     //$scope.menuUrl=null;
	     //$scope.menuUrl;
	     //$scope.menuUrl="partials/nav1.html";
	      $scope.menuUrl = getMenu2();
	});*/
});