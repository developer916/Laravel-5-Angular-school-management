(function () {
    'use strict';

    angular
        .module('app')
        .directive('profilemodal', Directive);

    function Directive(ProfileModalService) {
    	alert('ProfileModalService');
        return {
            link: function (scope, element, attrs) {
                // ensure id attribute exists
                if (!attrs.id) {
                    console.error('modal must have an id');
                    return;
                }

                // move element to bottom of page (just before </body>) so it can be displayed above everything else
                element.appendTo('body');

                // close modal on background click
                element.on('click', function (e) {
                    var target = $(e.target);
                    if (!target.closest('.modal-body').length && !target.closest('.modal-header').length  && !target.closest('.modal-footer').length) {
                        scope.$evalAsync(Close);

                    }
                });

                // add self (this modal instance) to the modal service so it's accessible from controllers
                var modal = {
                    id: attrs.id,
                    open: Open,
                    close: Close
                };
                ProfileModalService.Add(modal);

                // remove self from modal service when directive is destroyed
                scope.$on('$destroy', function() {
                    //ProfileModalService.Remove(attrs.id);
                    //element.remove();
                });

                // open modal
                function Open() {
                    element.show();
                    $('body').addClass('modal-open');
                }

                // close modal
                function Close() {
                    element.hide();
                    $('body').removeClass('modal-open');
                }
            }
        };
    }
})();