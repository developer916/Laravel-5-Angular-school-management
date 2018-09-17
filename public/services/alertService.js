(function () {
    'use strict';

    angular
        .module('app')
        .factory('alertService', alertService);

    function alertService($rootScope,$timeout) {
    
	var alertService = {};
	$rootScope.alerts = [];
	alertService.add = function(type, msg, timeout) {
	    $rootScope.alerts.push({
		'type': type,
		'msg': msg,
		close: function() {
		    return alertService.closeAlertTimeout(this);
		}
	    });
	    //timeout
	    if (timeout) {
		$timeout(function(){
		    alertService.closeAlertTimeout(this);
		}, timeout);
	    }else{
		$timeout(function(){
		    alertService.closeAlertTimeout(this);
		}, 5000);
	    }
	};
	alertService.closeAlertTimeout = function(alert) {
	    return this.closeAlertIdx($rootScope.alerts.indexOf(alert));
	};
	alertService.closeAlertIdx = function(index) {
	    return $rootScope.alerts.splice(index, 1);
	};
	alertService.closeAlert = function(index) {
	  $rootScope.alerts.splice(index, 1);
	};
	return alertService;
    
    }
})();