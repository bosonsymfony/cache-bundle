
/**
 * CacheBundle/Resources/public/adminApp/directives/cacheHomeDirective.js
 */
angular.module('app')
        .directive('cacheHomeDirective',
                function () {
                    return {
                        restrict: 'A',
                        link: function ($scope, element, attrs) {
                            element.bind('mouseenter', function () {
                                element.css('background-color', 'yellow');
                                element.css('color', 'red');
                            });
                            element.bind('mouseleave', function () {
                                element.css('background-color', 'white');
                                element.css('color', 'black');
                            });
                        }
                    }
                }
        );