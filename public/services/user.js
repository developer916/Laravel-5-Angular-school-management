'use strict';
angular.module('app')
    .factory('User', function(ChatService){
        return {
            getAll: function(){
                return ChatService.get('users'); 
            },
            getUsersSearchByName: function(name){
                return ChatService.post('getUsersSearchByName',{name:name}); 
            },
            show: function(id){
                return ChatService.get('user/' + id);    
            },
            create: function(user){
                return ChatService.post('users',user); 
            },
            getByChatLastSendUser: function(){
                return ChatService.get('getByChatLastSendUser'); 
            },
        }    
    });