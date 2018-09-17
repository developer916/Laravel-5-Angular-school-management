(function () {
    'use strict';

    angular
        .module('app')
        .config(route)
        .run(runapp);

    function route($stateProvider, $urlRouterProvider) {
        $stateProvider
        .state('app', {
            url: '/',
            abstract: true,
            templateUrl: 'components/header/header.html',
            controller: "HeaderController as hdr",
            resolve: {
                universities: function (universityService) {
                    return universityService.getAll().then(function (response) {
                        //return response.data;
                        return response.universities;
                    });
                },
                // user: function (userService) {
                //     return userService.getUser().then(function (response) {
                //         console.log(response.data);
                //         return response.data;
                //     });
                // }
            }
            
        })
        .state('app.map', {
            url: 'map?keywords&location&diploma&speciality&uid',
            // abstract: true,
            templateUrl: 'components/map/map.html',
            controller: "MapController as map",
            resolve: {
                universities: function (universityService) {
                    return universityService.getAll().then(function (response) {
                        //return response.data;
                        return response.universities;
                    });
                },
                // user: function (userService) {
                //     return userService.getUser().then(function (response) {
                //         console.log(response.data);
                //         return response.data;
                //     });
                // }
            }
        })
        .state('app.studentProfile',{
            url:'studentProfile',
            //abstract: true,
            templateUrl: 'components/student-profile/student-profile.html',
            controller: "StudentProfileController as studentProfileC",
           
        })
        .state('app.studentSetting',{
            url:'studentSetting',
            //abstract: true,
            templateUrl: 'components/student-setting/student-setting.html',
            controller: "StudentSettingController as studentSettingC",
           
        })
        .state('app.studentTrack',{
            url:'studentTrack',
            //abstract: true,
            templateUrl: 'components/student-track/student-track.html',
            controller: "StudentTrackController as studentTrackC",
           
        })
        .state('app.viewStudentProfile',{
            url:'viewStudentProfile/:user/:application',
            //abstract: true,
            templateUrl: 'components/view-student-profile/view-student-profile.html',
            controller: "ViewStudentProfileController as viewStudentProfileC",
           
        })
        .state('app.schoolFrontpage',{
            url:'schoolFrontpage',
            templateUrl: 'components/school-frontpage/school-frontpage.html',
            controller: "SchoolFrontpageController as schoolFrontpageC",
        })
        .state('app.profile',{
            url:'profile',
            //abstract: true,
            templateUrl: 'components/profile/profile.html',
            controller: "ProfileController as profileC",
           
        })
        .state('app.housingList',{
            url:'housingList',
            //abstract: true,
            templateUrl: 'components/housing-list/housing-list.html',
            controller: "HousingListController as housingListC",
        })
        .state('app.housing',{
            url:'housing?campusId',
            //abstract: true,
            templateUrl: 'components/housing/housing.html',
            controller: "HousingController as housingC",
        })
        .state('app.programList',{
            url:'programList',
            //abstract: true,
            templateUrl: 'components/program-list/program-list.html',
            controller: "ProgramListController as programListC",
        })
        .state('app.program',{
            url:'program?programId',
            //abstract: true,
            templateUrl: 'components/program/program.html',
            controller: "ProgramController as programC",
        })
        .state('app.application',{
            url:'application',
            //abstract: true,
            templateUrl: 'components/application/application.html',
            controller: "ApplicationController as applicationC",
        })
        //.state('app.chat-rooms',{
        //    url:'chat-rooms',
        //    templateUrl: 'components/chat-rooms/chat-rooms.html',
        //    controller: "ChatRoomsController as chatRoomsC",
        //})
        //.state('app.chat-room',{
        //    url:'chat-room/:chatRoom/:user',
        //    templateUrl: 'components/chat-room/chat-room.html',
        //    controller: "ChatRoomController as chatRoomC",
        //})
        .state('app.chat',{
            url:'chat/:user',
            templateUrl: 'components/chat/chat.html',
            controller: "ChatController as chatC",
        })
        .state('app.profile.home',{
            url:'/',
            templateUrl: 'components/profile/home/home.html',
            controller: "ProfileHomeController"
        })
        .state('app.profile.messages',{
            url:'/messages',
            templateUrl: 'components/profile/messages/messages.html',
            controller: "ProfileMessagesController"
        });

        $urlRouterProvider.otherwise(function ($injector) {
            var $state = $injector.get("$state");
            $state.go("app.map");
        });
    }
    
    function runapp($rootScope, $location, $cookies) {
        $rootScope.authenticated = false;
        
        if($cookies.get('user_token')){
            $rootScope.authenticated = true;
            $rootScope.userToken = $cookies.get('user_token');
            $rootScope.userEmail = $cookies.get('user_email');
            $rootScope.userType = $cookies.get('user_type');
            $rootScope.userData = JSON.parse($cookies.get('user_data'));
        }
    }
    
})();