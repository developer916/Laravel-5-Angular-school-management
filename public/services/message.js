'use strict';
angular.module('app')
    .factory('Message', function(ChatService){
        return {
            getByChat: function(user){
                return ChatService.get('messages/'+user.id); 
            },
            createInChat: function(message){
                return ChatService.post('messages', message);  
            },
            getUpdates: function(user, lastMessage){
                var lastMessageId;
                if(! lastMessage){
                    lastMessageId = 0;
                }else{
                    lastMessageId = lastMessage.id;
                }
                return ChatService.get('messages/' + lastMessageId + '/' + user.id);  
            },
        }    
    });