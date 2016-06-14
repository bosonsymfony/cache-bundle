/**
 * CacheBundle/Resources/public/adminApp/controllers/cacheHomeCtrl.js
 */
angular.module('app')
    .controller('cacheHomeCtrl',
        ['$scope', 'cacheHomeSvc', 'toastr', '$mdDialog',
            function ($scope, cacheHomeSvc, toastr, $mdDialog) {
                $scope.confirmacion = false;

                $scope.alphanumeric = '[a-zA-Z0-9]+';
                $scope.host = '[a-zA-Z0-9.]+';
                $scope.numeric = '[0-9]+';
                $scope.regexURL = '[a-zA-Z0-9.:/_]+';
                $scope.alphanumericMess = "Solo se permiten letras y números.";
                $scope.numericMess= "Solo se permiten números.";
                $scope.regexURLMess = "Solo se permiten letras, números y caracteres especiales . : / _.";
                $scope.hostMess = "Solo se permiten letras, números y caracter especial '.' .";
                var first_access = null;

                $scope.wasmodified = false;
                $scope.modif = function () {
                    $scope.wasmodified = true;
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

                    if(first_access){
                        first_access = false;
                    }else {
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
                                break;
                            case "memcached_cache":
                                $scope.tipo = response.type;
                                $scope.anfitrion = response.host;
                                $scope.puerto = response.port;
                                break;
                            case "redis_cache":
                                $scope.tipo = response.type;
                                $scope.anfitrion = response.host;
                                $scope.puerto = response.port;
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
                                break;
                            case "php_file_cache":
                                $scope.tipo = response.type;
                                $scope.direccion = response.url;
                                break;
                        }
                        first_access = true;
                        $scope.checkVisibility();
                    });


                $scope.guardarClick = function (ev) {

                    var confirm = $mdDialog.confirm()
                        .title('Confirmación de cambios')
                        .textContent('¿Está seguro que desea aplicar los cambios?')
                        .targetEvent(ev)
                        .ok('Si')
                        .cancel('No');
                    $mdDialog.show(confirm).then(function() {
                        //si se selecciona que si:
                        var data = {
                            uci_boson_cachebundle_data: {
                                type: $scope.tipo,
                                host: $scope.anfitrion,
                                port: $scope.puerto,
                                url: $scope.direccion
                            }
                        };
                        cacheHomeSvc.writeYAML(data)
                            .success(function (response) {
                                toastr.success("La caché se ha configurado satisfactoriamente");
                                $scope.wasmodified = false;
                                first_access = null;
                            })
                            .error(function (response) {
                                console.log(response);
                                toastr.error(response);
                            });
                    }, function() {
                        //en caso contrario:
                        toastr.info("Se ha cancelado la operación");
                    });
                };

                $scope.eraseCacheClick = function () {
                    cacheHomeSvc.eraseCache()
                        .success(function (response) {
                            toastr.success("La caché se ha limpiado satisfactoriamente");
                        })
                        .error(function (response) {
                            console.log(response);
                            toastr.error("Error al limpiar la caché. El tipo de caché seleccionado" +
                                " no se encuentra correctamente instalado y/o configurado en el servidor");
                        });
                };


            }
        ]
    );