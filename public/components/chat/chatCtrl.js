(function () {
    'use strict';

    angular
        .module('app.chat')
        .controller('ChatController', function($scope, Message, User, $stateParams, $state, $interval, $timeout){
        
		$('.scrollator').each(function () {
		    var $this = $(this);
		    var options = {};
		    $.each($this.data(), function (key, value) {
			if (key.substring(0, 10) == 'scrollator') {
			    options[key.substring(10, 11).toLowerCase() + key.substring(11)] = value;
			}
		    });
		    $this.scrollator(options);
		});
		
		// Search User in user list
		$scope.searchUser = function () {
			//if($scope.searchName.length > 2){
				User.getUsersSearchByName($scope.searchName)
					.then(getAllUsersSearchByName);
			//}
	   	}
	   	
	   	var getAllUsersSearchByName = function(users){
            $scope.users = users;
	    	return users;
        }
		
		$scope.message = { text: ''};
		$scope.stopInterval = null;
        
		var getAllUserLoaded = function(users){
            $scope.users = users;
	    	return users;
        }
	
        var userLoaded = function(user){
            $scope.user = user;
	    	$scope.message.user_receiver_id = user.id;
	    	return user;
        }
        
        var getMessagesIn = function(user){
	    	return Message.getByChat(user);
        }
        
        $scope.messages = {};
        var MessagesLoaded = function(messages){
            $scope.messages = messages;
		    $timeout(function () {
				$('#scrollable_div3-1').animate({scrollTop: $('#scrollable_div3-1').prop("scrollHeight")}, 500);
		    }, 1000);
	    }
        
        var handleErrors = function(errors){
            console.error(errors);            
        }
        
        var getLastMessage = function(){
            return $scope.messages[$scope.messages.length - 1];
        }
        
        var getUpdates = function(){
           return Message.getUpdates($scope.user, getLastMessage()).then(updatesLoaded); 
        }
        
        var updatesLoaded = function(updates){
	        $scope.messages = $scope.messages.concat(updates);
		    if(updates.length > 0){
				$('#scrollable_div3-1').animate({scrollTop: $('#scrollable_div3-1').prop("scrollHeight")}, 500);
		    }
		    
		}
    
        $scope.createMessage = function(message){
		    if($scope.message.text.length > 0){
		    	/*Message.createInChat(message)
	            	.then(getUpdates);*/
	            	
	            Message.createInChat(message)
	            	.then(function (response) {
	            		getUpdates();
	            		if(response.id){
							$scope.message.text = '';
						}
					});	
	           
		    	$('#chat-textarea').val('');
		    	$('#scrollable_div3-1').animate({scrollTop: $('#scrollable_div3-1').prop("scrollHeight")}, 500);
		    }	
        }
        
		//Initialize the getUpdates to run every 3000 milliseconds i.e. three second.
        $scope.stopInterval = $interval(getUpdates,3000);
	
		$scope.selectChatUser = function(user_id){
		    //Cancel the getUpdates.
		    if (angular.isDefined($scope.stopInterval)) {
				$interval.cancel($scope.stopInterval);
		    }
		    $state.go("app.chat", {'user':user_id});
	        //$location.path('chat/' + user_id);
        }
	
		//$scope.$on('$stateChangeSuccess', function() { 
		    //Cancel the getUpdates.
		    //if (angular.isDefined($scope.stopInterval)) {
			//$interval.cancel($scope.stopInterval);
		    //}
		//});
	
		$scope.$on('$destroy', function() {
		    if (angular.isDefined($scope.stopInterval)) {
			$interval.cancel($scope.stopInterval);
		    }
	        });
        
	
		User.getAll()
		    .then(getAllUserLoaded)
		    .catch(handleErrors);
	
        User.show($stateParams.user)
            .then(userLoaded)
            .then(getMessagesIn)
            .then(MessagesLoaded)
            .catch(handleErrors); 
    });
})();