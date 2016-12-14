/**
 * CacheBundle/Resources/public/adminApp/controllers/cacheHomeCtrl.js
 */
angular.module('app')
    .controller('cacheHomeCtrl',
        ['$scope', 'cacheHomeSvc', 'toastr', '$mdDialog',
            function ($scope, cacheHomeSvc, toastr, $mdDialog) {
                cacheHomeSvc.getCSRFtoken()
                    .success(function (response) {
                        $scope.token = response;
                    })
                    .error(function (response) {
                    });

                $scope.alphanumeric = '[a-zA-Z0-9]+';
                $scope.host = '[a-zA-Z0-9.]+';
                $scope.numeric = '[0-9]+';
                $scope.regexURL = '[a-zA-Z0-9.:/_]+';
                $scope.alphanumericMess = "Solo se permiten letras y números.";
                $scope.numericMess = "Solo se permiten números.";
                $scope.regexURLMess = "Solo se permiten letras, números y caracteres especiales . : / _.";
                $scope.hostMess = "Solo se permiten letras, números y caracter especial '.' .";
                var first_access = null;

                var ruta_orig = null;
                var host_orig = null;
                var port_orig = null;

                $scope.wasmodified = false;

                $scope.modif = function () {
                    if (ruta_orig != $scope.direccion ||
                        host_orig != $scope.anfitrion ||
                        port_orig != $scope.puerto){

                        $scope.wasmodified = true;
                    }else{
                        $scope.wasmodified = false;
                    }
                };

                $scope.tiposC = [
                    {label: 'apc_cache'},
                    {label: 'array_cache'},
                    {label: 'memcache_cache'},
                    {label: 'memcached_cache'},
                    {label: 'redis_cache'},
                    {label: 'win_cache_cache'},
                    {label: 'xcache_cache'},
                    {label: 'zend_data_cache'},
                    {label: 'file_system_cache'},
                    {label: 'php_file_cache'}
                ];

                $scope.checkVisibility = function () {
                    $scope.visHost = true;
                    $scope.visPuerto = true;
                    $scope.visUrl = true;

                    if (first_access) {
                        first_access = false;
                    } else {
                        $scope.wasmodified = true;
                    }

                    switch ($scope.tipo) {
                        case "apc_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            $scope.visUrl = false;
                            break;
                        case "array_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            $scope.visUrl = false;
                            break;
                        case "memcache_cache":
                            $scope.visUrl = false;
                            break;
                        case "memcached_cache":
                            $scope.visUrl = false;
                            break;
                        case "redis_cache":
                            $scope.visUrl = false;
                            break;
                        case "win_cache_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            $scope.visUrl = false;
                            break;
                        case "xcache_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            $scope.visUrl = false;
                            break;
                        case "zend_data_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            $scope.visUrl = false;
                            break;
                        case "file_system_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            break;
                        case "php_file_cache":
                            $scope.visHost = false;
                            $scope.visPuerto = false;
                            break;
                    }
                };

                cacheHomeSvc.showCurrentInfo()
                    .success(function (response) {
                        switch (response.type) {
                            case "apc_cache":
                                $scope.tipo = response.type;
                                break;
                            case "array_cache":
                                $scope.tipo = response.type;
                                break;
                            case "memcache_cache":
                                $scope.tipo = response.type;
                                $scope.anfitrion = response.host;
                                $scope.puerto = response.port;
                                host_orig = response.host;
                                port_orig = response.port;
                                break;
                            case "memcached_cache":
                                $scope.tipo = response.type;
                                $scope.anfitrion = response.host;
                                $scope.puerto = response.port;
                                host_orig = response.host;
                                port_orig = response.port;
                                break;
                            case "redis_cache":
                                $scope.tipo = response.type;
                                $scope.anfitrion = response.host;
                                $scope.puerto = response.port;
                                host_orig = response.host;
                                port_orig = response.port;
                                break;
                            case "win_cache_cache":
                                $scope.tipo = response.type;
                                break;
                            case "xcache_cache":
                                $scope.tipo = response.type;
                                break;
                            case "zend_data_cache":
                                $scope.tipo = response.type;
                                break;
                            case "file_system_cache":
                                $scope.tipo = response.type;
                                $scope.direccion = response.url;
                                ruta_orig = response.url;
                                break;
                            case "php_file_cache":
                                $scope.tipo = response.type;
                                $scope.direccion = response.url;
                                ruta_orig = response.url;
                                break;
                        }
                        first_access = true;
                        $scope.checkVisibility();
                    });


                $scope.guardarClick = function (ev) {

                    $mdDialog.show({
                        clickOutsideToClose: true,
                        controller: 'DialogController',
                        focusOnOpen: false,
                        targetEvent: ev,
                        locals: {
                            entities: $scope.selected
                        },
                        templateUrl: $scope.$urlAssets + 'bundles/cache/adminApp/views/confirm-dialog.html'
                    }).then(function (answer) {
                        //console.log(answer);
                        if (answer == 'Aceptar') {
                            var data = {
                                uci_boson_cachebundle_data: {
                                    type: $scope.tipo,
                                    host: $scope.anfitrion,
                                    port: $scope.puerto,
                                    url: $scope.direccion,
                                    _token: $scope.token
                                }
                            };
                            cacheHomeSvc.writeYAML(data)
                                .success(function (response) {
                                    toastr.success(response);
                                    $scope.wasmodified = false;
                                    first_access = null;

                                    cacheHomeSvc.showCurrentInfo()
                                        .success(function (response) {
                                            switch (response.type) {
                                                case "apc_cache":
                                                    $scope.tipo = response.type;
                                                    break;
                                                case "array_cache":
                                                    $scope.tipo = response.type;
                                                    break;
                                                case "memcache_cache":
                                                    $scope.tipo = response.type;
                                                    $scope.anfitrion = response.host;
                                                    $scope.puerto = response.port;
                                                    host_orig = response.host;
                                                    port_orig = response.port;
                                                    break;
                                                case "memcached_cache":
                                                    $scope.tipo = response.type;
                                                    $scope.anfitrion = response.host;
                                                    $scope.puerto = response.port;
                                                    host_orig = response.host;
                                                    port_orig = response.port;
                                                    break;
                                                case "redis_cache":
                                                    $scope.tipo = response.type;
                                                    $scope.anfitrion = response.host;
                                                    $scope.puerto = response.port;
                                                    host_orig = response.host;
                                                    port_orig = response.port;
                                                    break;
                                                case "win_cache_cache":
                                                    $scope.tipo = response.type;
                                                    break;
                                                case "xcache_cache":
                                                    $scope.tipo = response.type;
                                                    break;
                                                case "zend_data_cache":
                                                    $scope.tipo = response.type;
                                                    break;
                                                case "file_system_cache":
                                                    $scope.tipo = response.type;
                                                    $scope.direccion = response.url;
                                                    ruta_orig = response.url;
                                                    break;
                                                case "php_file_cache":
                                                    $scope.tipo = response.type;
                                                    $scope.direccion = response.url;
                                                    ruta_orig = response.url;
                                                    break;
                                            }
                                            first_access = true;
                                            $scope.checkVisibility();
                                        });

                                })
                                .error(function (response) {
                                    console.log(response);
                                    toastr.error(response);
                                });
                        } else {
                            // alert("Cancelar");
                        }
                    });
                };

                $scope.eraseCacheClick = function () {
                    cacheHomeSvc.eraseCache()
                        .success(function (response) {
                            toastr.success(response);
                        })
                        .error(function (response) {
                            toastr.error("Error al limpiar la caché. El tipo de caché seleccionado no se encuentra correctamente instalado y/o configurado en el servidor.");
                        });
                };

                //$scope.cancelar = function () {
                //    $mdDialog.cancel;
                //};
            }
        ]
    )
    .controller('DialogController',
        ['$scope', 'cacheHomeSvc', 'toastr', '$mdDialog',
            function ($scope, cacheHomeSvc, toastr, $mdDialog) {
                $scope.hide = function () {
                    $mdDialog.hide();
                };
                $scope.cancel = function () {
                    $mdDialog.cancel();
                };
                $scope.answer = function (answer) {
                    $mdDialog.hide(answer);
                };

            }
        ]
    );