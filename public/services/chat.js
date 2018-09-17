'use strict';
angular.module('app')
    .factory('Chat', function(ChatService){
        return {
            getAll: function(){
                return ChatService.get('chat'); 
            },
            show: function(user_id){
                return ChatService.get('chat/' + user_id);    
            },
            create: function(chat){
                return ChatService.post('chat',chat); 
            },
        }    
    });