(function () {
    'use strict';

    angular
        .module('app')
        .config(config);

    function config($translateProvider, $logProvider) {
        $logProvider.debugEnabled(false);
        $translateProvider.useStaticFilesLoader({
            'prefix': 'globalization/',
            'suffix': '.json'
        });
        var lang = localStorage.getItem("langPref") || navigator.language || "en";
        lang = lang.toLowerCase().substring(0, 2);
        if (lang != "fr" && lang != "en") {
            lang = "en";
        }
        $translateProvider.preferredLanguage(lang);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }
})();