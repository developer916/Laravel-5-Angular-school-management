(function () {
    'use strict';

    var RemoteApi = location.origin+"/api";
    angular
        .module('app')
        .constant("RemoteApi",RemoteApi);

    angular
        .module('app')
        .run(run);

    function run(amMoment, $rootScope) {
        /*
        Change timeZone of Moment.js
          */
        amMoment.changeLocale('fr');

        /*
        Interval of popup/modal slides
         */
        $rootScope.slideInterval = 5000;
    }
})();