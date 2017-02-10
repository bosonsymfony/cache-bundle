Componente: CacheBundle
=======================

1. Descripción general
----------------------

    El componente CacheBundle permite a las aplicaciones que se están desarrollando la gestión de la caché,
    permitiendo almacenar y recuperar la información almacenada en la misma de forma rápida y sencilla.
    El componente permite guardar información en caché en cualquiera de los siguientes formatos:

    - Texto.
    - Arreglos o tablas hash.
    - Objetos o instancias de clases.
    - Estructuras de datos complejas como listas, pilas, colas, árboles y otras estructuras de mayor complejidad.

2. Prerequisitos
------------------

    Tener habilitado y configurado cualquiera de los siguientes mecanismos de almacenamiento en caché que se
    listan a continuación:

    - ApcCache
    - ArrayCache
    - FilesystemCache
    - MemcacheCache
    - MemcachedCache
    - PhpFileCache
    - RedisCache
    - WinCacheCache
    - XcacheCache
    - ZendDataCache

3. Instalación
---------------------

    1. Copiar el componente dentro de la carpeta `vendor/boson/cache-bundle/UCI/Boson`.
    2. Registrarlo en el archivo `app/autoload.php` de la siguiente forma:

       .. code-block:: php

           // ...
           $loader = require __DIR__ . '/../vendor/autoload.php';
           $loader->add("UCI\\Boson\\CacheBundle", __DIR__ . '/../vendor/boson/cache-bundle');
           // ...

    3. Activarlo en el kernel de la siguiente manera:

       .. code-block:: php

           // app/AppKernel.php
           public function registerBundles()
           {
               return array(
                   // ...
                   new UCI\Boson\CacheBundle\CacheBundle(),
                   // ...
               );
           }

    4. Añadir en el archivo `app/config/parameters.yml` la siguiente línea:

        **cache {type: tipo de caché, parámetros separados por coma}**

       Los parámetros de cada mecanismo que se vaya a usar varían según el mismo. A continuación se
       detallan las configuraciones para cada tipo de caché:

       * ApcCache:

           .. code-block:: yml

               cache: {type: apc_cache}

       * ArrayCache:

           .. code-block:: yml

               cache: {type: array_cache}

       * FilesystemCache:

           .. code-block:: yml

               cache: {type: php_file_cache, url: /tmp/phpfilecache}

       * MemcacheCache:

           .. code-block:: yml

               cache: {type: memcache_cache, host: localhost, port: 11211}

       * MemcachedCache:

           .. code-block:: yml

               cache: {type: memcached_cache, host: localhost, port: 11211}

       * PhpFileCache:

           .. code-block:: yml

               cache: {type: php_file_cache, url: /tmp/phpfilecache}

       * RedisCache:

           .. code-block:: yml

               cache: {type: redis_cache, host: localhost, port: 6379}

       * WinCacheCache:

           .. code-block:: yml

               cache: {type: win_cache_cache}

       * XcacheCache:

           .. code-block:: yml

               cache: {type: xcache_cache}

       * ZendDataCache:

           .. code-block:: yml

               cache: {type: zend_data_cache}

4. Especificación funcional
---------------------------

4.1. Requisitos funcionales
~~~~~~~~~~~~~~~~~~~~~~~~~~~

4.1.1. Registrar información en la caché
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    Ver en la descripción de las funcionalidades.

4.1.2. Modificar información de la caché
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    Ver en la descripción de las funcionalidades.

4.1.3. Eliminar información de la caché
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    Ver en la descripción de las funcionalidades.

4.2. Descripción de las funcionalidades que brinda el componente
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    Primero se declara el objeto que gestionará nuestra caché a través del servicio **'uci.boson.cache'**  como se muestra a continuación:

    .. code:: text

        $cacheDriver =  $this->get('uci.boson.cache');

    A continuación se detallan los métodos de la clase cache accedida a través del servicio antes mencionado:

    *   .. code:: text

            fetch($id)

        **Descripción:**

        Obtiene una entrada de la memoria caché.

        **Parámetros:**

        * .. code:: text

            string $id: El id de la entrada de caché a buscar.

        **Retorna:**

        * .. code:: text

            string|boolean: Devuelve los datos en caché o FALSE, si no existe una entrada de caché para el id dado.


    *   .. code:: text

            save($id, $data, $lifeTime = 0)

        **Descripción:**

        Pone los datos en la memoria caché.

        **Parámetros:**

        * .. code:: text

            string $id: El identificador de caché.

        * .. code:: text

            mixed $data: La entrada/datos de caché.

        * .. code:: text

            int $lifeTime: El tiempo de vida en segundos. Si $lifeTime != 0, se establece un tiempo de vida específico para esta entrada de caché(0 => infinito de por vida).

        **Retorna:**

        * .. code:: text

            boolean: TRUE si la entrada fue almacenada con éxito en la caché, FALSE de lo contrario.


    *   .. code:: text

            contains($id)

        **Descripción:**

        Comprueba si existe una entrada en la caché.

        **Parámetros:**

        * .. code:: text

            string $id: El identificador de caché de la entrada para comprobar.

        **Retorna:**

        * .. code:: text

            boolean: TRUE si existe una entrada de caché para el identificador de caché dado, FALSE de lo contrario.


    *   .. code:: text

            delete($id)


        **Descripción:**

        Elimina una entrada de caché.

        **Parámetros:**

        * .. code:: text

            string $id: El identificador de caché.

        **Retorna:**

        * .. code:: text

            boolean: TRUE si la entrada de caché se ha eliminado correctamente, FALSE de lo contrario.


    *   .. code:: text

            flushAll()

        **Descripción:**

        Vacía todas las entradas de la caché.

        **Retorna:**

        * .. code:: text

            boolean: TRUE si las entradas de caché se vacían con éxito, FALSE de lo contrario.

    *   .. code:: text

            deleteAll()

        **Descripción:**

        Elimina todas las entradas de la caché.

        **Retorna:**

        * .. code:: text

            boolean: TRUE si las entradas de caché se eliminan con éxito, FALSE de lo contrario.


    *   .. code:: text

            getStats()

        **Descripción:**

        Devuelve la información almacenada en caché del almacén de datos.

        **Retorna:**

        * .. code:: text

            array|null: Una matriz asociativa con las estadísticas del servidor si está disponible, NULL de otro modo.


    *   .. code:: text

            getNamespace()

        **Descripción:**

        Devuelve el namespace del prefijo de todos los identificadores de caché.

        **Retorna** :

        * .. code:: text

            string


    *   .. code:: text

            setNamespace($namespace)

        **Descripción:**

        Define el namespace para el prefijo de todos los identificadores de caché.

        **Parámetros:**

        * .. code:: text

            string $namespace

        **Retorna** :

        * .. code:: text

            void

4.3. Configuración del componente a través de la interfaz gráfica
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Para la configuración de la caché es necesario acceder al módulo dentro del panel de configuración. Una vez en el módulo se presentan las opciones básicas de configuración de caché, cargando la configuración actual.

4.3.1. Configuración de parámetros del componente
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Para establecer nuevos parámetros de configuración es necesario especificar el tipo de caché que se va a utilizar en la aplicación. En dependencia de este tipo serán mostrados nuevos campos para especificar la configuración. Para almacenar los cambios realizados se presiona el botón Configurar, el cual se mostrará deshabilitado hasta el momento en que algunos de los campos presentados sufran algún cambio. Una vez presionado el botón se muestra un mensaje de confirmación para realizar los cambios. Al seleccionar que se desean guardar los cambios, el sistema procesa la petición y muestra un mensaje indicando el éxito de la operación a través del mensaje “La caché se ha configurado satisfactoriamente”.

4.3.2. Limpiar caché
^^^^^^^^^^^^^^^^^^^^
Para limpiar la caché se presenta el botón Limpiar en la esquina superior derecha de la pantalla. Esta opción limpia la caché en dependencia del tipo seleccionado en la configuración. Una vez presionado el botón el sistema procesa la petición y muestra al usuario un mensaje indicando el éxito o fallo de la operación.

---------------------------------------------

:Versión: 1.0 17/7/2015
:Autores: Julio Cesar Ocaña Bermúdez jcocana@uci.cu,
          Daniel Herrera Sánchez dherrera@uci.cu

Contribuidores
--------------

:Entidad: Universidad de las Ciencias Informáticas. Centro de Informatización de Entidades.


Licencia
--------
