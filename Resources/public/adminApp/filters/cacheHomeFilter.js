
/**
 * CacheBundle/Resources/public/adminApp/filters/cacheHomeFilter.js
 */
angular.module('app')
        .filter('cacheHomeFilter',
                function () {
                    return function (input) {
                        input = input || '';
                        var out = "";
                        for (var i = 0; i < input.length; i++) {
                            out = input.charAt(i) + out;
                        }
                        return out;
                    }
                }
        );