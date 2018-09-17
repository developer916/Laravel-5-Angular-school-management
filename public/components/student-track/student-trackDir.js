(function () {
    'use strict';

    angular
        .module('app.studentTrack')
        .directive('uploadDocument', function (httpPostFactory) {
            return {
                restrict: 'A',
                scope: {
                    model: '=',
		    application_id: '=',
		    diploma_requirement_id: '=',
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
			formData.append('application_id', attrs.applicationId);
			formData.append('diploma_requirement_id', attrs.diplomaRequirementId);
			formData.append('document_type_id', attrs.documentTypeId);
			
			var fileObject = elem[0].files[0];
			//if(fileObject.size > 205770 ){
			//		alert("Please upload file maximum 200 KB.");
			//		return true;
			//}
			httpPostFactory('uploadDocument', formData, scope, function (callback) {
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