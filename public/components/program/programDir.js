(function () {
    'use strict';

    angular
        .module('app.program')
        .directive('ngFileProgram', function (httpPostFactory, $parse) {
            return {
                restrict: 'A',
                link: function(scope, element, attrs) {	
              	    var model = $parse(attrs.ngFileProgram);
		    var isMultiple = attrs.multiple;
		    var modelSetter = model.assign;
		    
		    element.bind('change', function() {
			var values = [];
			var formData = new FormData();
			formData.append('file', element[0].files[0]);
			    
			//    var fileObject = element[0].files[0];
			//    if(fileObject.size > 205770 ){
			//	alert("Please upload file maximum 200 KB.");
			//	return true;
			//    }
			
			httpPostFactory('singleUploadImage', formData, scope, function (callback) {
			    if(callback.msg){
				//scope.model = callback.data;
				modelSetter(scope, callback.data)
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