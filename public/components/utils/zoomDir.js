(function () {
    'use strict';
    angular
        .module('app')
        .directive('photoSwipe', function () {
            return {
                restrict: 'E',
                templateUrl: 'components/utils/zoomTpl.html'
            }
        }).directive('zoomable', function () {
        return {
            scope: {
                pictures: '='
            },
            link: function (scope, element, attrs) {
                $(element).on('click', function (e) {
                    var pswpElement = document.querySelectorAll('.pswp')[0];
                    var items = [];

                    for (var i = 0; i < scope.pictures.length; i++) {
                        var item = {};
                        if (scope.pictures[i].title) item.title = scope.pictures[i].title;
                        item.src = "images/universities/" + scope.pictures[i].url;
                        item.w = 1480;
                        item.h = 1024;
                        items.push(item);
                    }

                    var options = {
                        history: false,
                        focus: false,
                        shareEl: false
                    };

                    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                    gallery.init();
                });
            }
        };
    });
})();
