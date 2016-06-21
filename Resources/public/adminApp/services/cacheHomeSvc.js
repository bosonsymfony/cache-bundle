/**
 * CacheBundle/Resources/public/adminApp/services/cacheHomeSvc.js
 */
angular.module('app')
    .service('cacheHomeSvc', ['$http',
            function ($http) {
                var message = '';

                function setMessage(newMessage) {
                    message = newMessage;
                }

                function getMessage() {
                    return message;
                }

                function writeYAML(data) {
                    return $http.post(Routing.generate('cache_save_data', {}, true), data);
                }

                function showCurrentInfo() {
                    return $http.get(Routing.generate('cache_current_info', {}, true));
                }

                function eraseCache() {
                    return $http.get(Routing.generate('cache_erase', {}, true));
                }

                function getCSRFtoken() {
                    return $http.post(Routing.generate('cache_csrf_form', {}, true), {id_form: 'uci_boson_cachebundle_data'});
                }

                return {
                    setMessage: setMessage,
                    getMessage: getMessage,
                    writeYAML: writeYAML,
                    showCurrentInfo: showCurrentInfo,
                    eraseCache: eraseCache,
                    getCSRFtoken: getCSRFtoken,
                    $get: function () {
                    }
                }
            }
        ]
    );