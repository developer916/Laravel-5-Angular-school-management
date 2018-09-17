(function () {
    'use strict';

    angular
        .module('app.studentProfile')
        .directive('uploadPicture', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
					       
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadPicture', formData, scope, function (callback) {
						    if(callback.msg){
								scope.model = callback.data;
						    }
						});
				    });
				},
            };     
        })
        .directive('videoFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
						formData.append('fileType', 'Video');   
					       
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.msg){
								scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        .directive('audioFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
						formData.append('fileType', 'Music');   
					       
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.msg){
								scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        .directive('imageFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
					    formData.append('fileType', 'Picture');   
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.status){
								scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        .directive('certificateFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
					    formData.append('fileType', 'Certificate');   
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.status){
								scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        .directive('educationFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
					    formData.append('fileType', 'Education');   
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.status){
								scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        .directive('bookFile', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    //university: '=',
                    model: '='
                },
                link: function(scope, elem, attrs) {	
              	    elem.bind('change', function() {
						var formData = new FormData();
						formData.append('file', elem[0].files[0]);
					    formData.append('fileType', 'Book');   
						var fileObject = elem[0].files[0];
						//if(fileObject.size > 205770 ){
						//		alert("Please upload file maximum 200 KB.");
						//		return true;
						//}
						httpPostFactory('uploadBasicDocument', formData, scope, function (callback) {
						    if(callback.status){
						    	scope.model = callback.documents;
						    }
						});
				    });
				},
            };     
        })
        
        .factory('httpPostFactory', function($http) {
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