<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 11/11/14
 * Time: 9:06
 */

namespace UCI\Boson\CacheBundle\Tests\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;
use UCI\Boson\CacheBundle\Cache\Cache;
if(file_exists(__DIR__ . '/../../../../../../app/AppKernel.php')){
    require_once __DIR__ . '/../../../../../../app/AppKernel.php';
}
else{
    require_once __DIR__ . '/../../../../../../../../app/AppKernel.php';
}

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppKernel
     */
    protected $kernel;
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected function setUp()
    {
        $this->kernel = new \AppKernel('dev', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
    }

    public function get($serviceId)
    {
        return $this->kernel->getContainer()->get($serviceId);
    }

    public function testSetNamespace()
    {
        $this->get('uci.boson.cache')->setNamespace('my_namespace');
        $result = $this->get('uci.boson.cache')->getNamespace();
        $this->assertEquals('my_namespace', $result);
    }


    public function testGetNamespace()
    {
        $this->get('uci.boson.cache')->setNamespace('my_namespace');
        $result = $this->get('uci.boson.cache')->getNamespace();
        $this->assertEquals('my_namespace', $result);
    }

    public function testSave()
    {
        $id = 'my_id';
        $data = 'probando test save';
        $this->assertTrue($this->get('uci.boson.cache')->save($id, $data));

    }


    public function testFetch()
    {
        $this->get('uci.boson.cache')->save('id1', 'new entry');
        $result = $this->get('uci.boson.cache')->fetch('id1');
        $this->assertEquals('new entry', $result);
    }


    public function testContains()
    {
        $this->assertTrue($this->get('uci.boson.cache')->contains('my_id'));
    }


    public function testDelete()
    {
        $this->assertTrue($this->get('uci.boson.cache')->delete('my_id'));
    }


    public function testFlushAll()
    {
        $this->assertTrue($this->get('uci.boson.cache')->flushAll());
    }


    public function testDeleteAll()
    {
        $this->assertTrue($this->get('uci.boson.cache')->deleteAll());
    }

    /**
     * @dataProvider datosParametros
     *
     * @param array $cache
     */
    public function testProbarParametros(array $cache)
    {
        $cacheDriver = new Cache($cache);
        $this->assertInstanceOf(Cache::class, $cacheDriver);
    }

    public function datosParametros()
    {
        return array(
            1 => array(
                'cache' => array(
                    'type' => 'apc_cache',
                )),
            2 => array(
                'cache' => array(
                    'type' => 'array_cache',
                )),
            3 => array(
                'cache' => array(
                    'type' => 'memcache_cache',
                    'host' => 'localhost',
                    'port' => 11211,
                )),
            4 => array(
                'cache' => array(
                    'type' => 'memcached_cache',
                    'host' => 'localhost',
                    'port' => 11211,
                )),
            5 => array(
                'cache' => array(
                    'type' => 'redis_cache',
                    'host' => 'localhost',
                    'port' => 6379,
                )),
            6 => array(
                'cache' => array(
                    'type' => 'win_cache_cache',
                )),
            7 => array(
                'cache' => array(
                    'type' => 'xcache_cache',
                )),
            8 => array(
                'cache' => array(
                    'type' => 'zend_data_cache',
                )),
            9 => array(
                'cache' => array(
                    'type' => 'file_system_cache',
                    'url' => '/tmp/file_system_cache'
                )),
            10 => array(
                'cache' => array(
                    'type' => 'php_file_cache',
                    'url' => '/tmp/php_file_cache'
                )),
        );
    }

} 