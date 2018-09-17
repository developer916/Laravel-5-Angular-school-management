(function () {
    'use strict';
    angular
        .module('app')
        .filter('myDateFormat', function myDateFormat($filter) {
            return function (text, format) {
                return $filter('date')(new Date(text), format);
            }
        }).filter('capitalizeFirstLetter', function () {
        return function (input) {
            if (input != null)
                input = input.toLowerCase();
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        }
    });
})();