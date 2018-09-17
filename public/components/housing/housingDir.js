(function () {
    'use strict';

    angular
        .module('app.housing')
        .directive('ngFileModel', function (httpPostFactory, $parse) {
            return {
                restrict: 'A',
                link: function(scope, element, attrs) {	
              	  	var model = $parse(attrs.ngFileModel);
				    var isMultiple = attrs.multiple;
				    var modelSetter = model.assign;
		    
				    element.bind('change', function() {
						var values = [];
						var formData = new FormData();
						angular.forEach(element[0].files, function (item, i) {
						    formData.append('file['+ i +']', item);
						});
						    
						//    var fileObject = element[0].files[0];
						//    if(fileObject.size > 205770 ){
						//	alert("Please upload file maximum 200 KB.");
						//	return true;
						//    }
						
						httpPostFactory('uploadImages', formData, scope, function (callback) {
						    if(callback.msg){
								//scope.model = callback.data;
								modelSetter(scope, callback.data)
								//values = callback.data;
								//scope.$apply(function () {
								//    if (isMultiple) {
								//	//modelSetter(scope, values);
								//    } else {
								//	//modelSetter(scope, values[0]);
								//    }
								//});
						    }
						});
						
						
						scope.$apply(function () {
						    if (isMultiple) {
								modelSetter(scope, values);
						    } else {
								modelSetter(scope, values[0]);
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