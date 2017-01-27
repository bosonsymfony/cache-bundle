<?php



namespace UCI\Boson\CacheBundle\Cache;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\WinCacheCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\Common\Cache\ZendDataCache;
use Symfony\Component\DependencyInjection\Container;


/**
 * Clase base para la gestion de la memoria caché.
 *
 * @since  1.0
 * @author Julio Cesar Ocana <jcocana@uci.cu>
 *
 */
class Cache
{

    /**
     * El driver de caché.
     *
     * @var mixed
     */
    private $cacheDriver;

    /**
     * __construct
     * @param array $cache
     */
    function __construct(array $cache)
    {
        $type = $cache['type'];
        switch ($type) {
            case 'apc_cache':
                $this->setCacheDriver(new ApcCache());
                break;
            case 'array_cache':
                $this->setCacheDriver(new ArrayCache());
                break;
            case 'memcache_cache':
                $memcache = new \Memcache();
                $host = $cache['host'];
                $port = $cache['port'];
                $memcache->connect($host,$port);
                $this->setCacheDriver(new MemcacheCache());
                $this->cacheDriver->setMemcache($memcache);
                break;
            case 'memcached_cache':
                $memcached = new \Memcached();
                $host = $cache['host'];
                $port = $cache['port'];
                $memcached->addServer($host,$port);
                $this->setCacheDriver(new MemcachedCache());
                $this->cacheDriver->setMemcached($memcached);
                break;
            case 'redis_cache':
                $redis = new \Redis();
                $host = $cache['host'];
                $port = $cache['port'];
                $redis->pconnect($host,$port);
                $this->setCacheDriver(new RedisCache());
                $this->cacheDriver->setRedis($redis);
                break;
            case 'win_cache_cache':
                $this->setCacheDriver(new WinCacheCache());
                break;
            case 'xcache_cache':
                $this->setCacheDriver(new XcacheCache());
                break;
            case 'zend_data_cache':
                $this->setCacheDriver(new ZendDataCache());
                break;
            case 'file_system_cache':
                $this->setCacheDriver(new FilesystemCache($cache['url']));
                break;
            case 'php_file_cache':
                $this->setCacheDriver(new PhpFileCache($cache['url']));
                break;
        }
    }

    /**
     * Establece el driver de caché.
     *
     * @param mixed $cacheDriver
     *
     * @return void
     */
    private function setCacheDriver($cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * Define el namespace para el prefijo de todos los identificadores de caché.
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->cacheDriver->setNamespace($namespace);
    }

    /**
     * Devuelve el namespace del prefijo de todos los identificadores de caché.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->cacheDriver->getNamespace();
    }

    /**
     * Obtiene una entrada de la memoria caché.
     *
     * @param string $id El id de la entrada de caché a buscar.
     *
     * @return string|boolean devuelve los datos en caché o FALSE, si no existe una entrada de caché para el id dado.
     */
    public function fetch($id)
    {
        return $this->cacheDriver->fetch($id);
    }

    /**
     * Pone los datos en la memoria caché.
     *
     * @param string $id       El identificador de cache.
     * @param string $data     La entrada/datos de caché.
     * @param int    $lifeTime El tiempo de vida. Si $lifeTime != 0, se establece un tiempo de vida específico para esta entrada de caché (0 => infinito de por vida).
     *
     * @return boolean TRUE si la entrada fue almacenada con éxito en la caché, FALSE de lo contrario.
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->cacheDriver->save($id, $data, $lifeTime);
    }

    /**
     * Comprueba si existe una entrada en la caché.
     *
     * @param string $id El identificador de cache de la entrada para comprobar.
     *
     * @return boolean TRUE si existe una entrada de caché para el identificador de cache dado, FALSE de lo contrario.
     */
    public function contains($id)
    {
        return $this->cacheDriver->contains($id);
    }

    /**
     * Elimina una entrada de caché.
     *
     * @param string $id El identificador de cache.
     *
     * @return boolean TRUE si la entrada de caché se ha eliminado correctamente, FALSE de lo contrario.
     */
    public function delete($id)
    {
        return $this->cacheDriver->delete($id);
    }

    /**
     * Vacía todas las entradas de la caché.
     *
     * @return boolean TRUE si las entradas de caché se vacían con éxito, FALSE de lo contrario.
     */
    public function flushAll()
    {
        return $this->cacheDriver->flushAll();
    }

    /**
     * Elimina todas las entradas de la caché.
     *
     * @return boolean TRUE si las entradas de caché se eliminan con éxito, FALSE de lo contrario.
     */
    public function deleteAll()
    {
        return $this->cacheDriver->deleteAll();
    }

    /**
     * Devuelve la información almacenada en caché del almacén de datos.
     *
     * @return array|null Una matriz asociativa con las estadísticas del servidor si está disponible, NULL de otro modo.
     */
    public function getStats()
    {
        return $this->cacheDriver->getStats();
    }
}
