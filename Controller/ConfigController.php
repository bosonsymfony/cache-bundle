<?php

namespace UCI\Boson\CacheBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UCI\Boson\BackendBundle\Controller\BackendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use UCI\Boson\CacheBundle\Entity\Data;
use UCI\Boson\CacheBundle\Form\DataType;


class ConfigController extends BackendController
{
    /**
     * Carga la configuración inicial de la caché y se la envía a la vista.
     * @return JsonResponse
     * @Route(path="/cache/currentinfo", name="cache_current_info", options={"expose"=true})
     */
    public function showCurrentInfoAction()
    {
        $values = $this->readYAMLAction();
        $arr = $values['parameters']['cache'];
        $response = new JsonResponse($arr);
        return $response;
    }

    /**
     * Lee la configuración de la caché en el fichero parameters_boson.yml
     * @return mixed
     */
    public function readYAMLAction()
    {
        $yaml = new Parser();
        $url = $this->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'parameters_boson.yml';

        $values = $yaml->parse(file_get_contents($url)); //esto hay q cambiarlo por ruta relativa o por busqueda recursiva
        return $values;
    }

    /**
     * Utiliza los servicios de caché para eliminar el contenido de la misma
     * Responde al RF(78) Limpiar la caché
     * @return Response     *
     * @Route(path="/cache/erasecache", name="cache_erase", options={"expose"=true})
     */
    public function eraseCacheAction()
    {
        $this->get('uci.boson.cache')->deleteAll();
        return new Response();
    }

    /**
     * Escribe en el fichero parameters_boson.yml la nueva configuración de caché
     * Responde al RF(77)Configurar tipo de caché
     * @param Request $request
     * @return Response
     * @Route(path="/cache/saveData", name="cache_save_data", options={"expose"=true})
     * @Method("POST")
     */
    public function writeYAMLAction(Request $request)
    {
        $data = new Data();
        $form = $this->createForm(new DataType(), $data);
        $form->handleRequest($request);

        $yaml = $this->readYAMLAction();
        $dumper = new Dumper();

        if ($form->isValid()) {
            if ($data->getType() == "apc_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "array_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "memcache_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
                $yaml['parameters']['cache']['host'] = $data->getHost();
                $yaml['parameters']['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "memcached_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
                $yaml['parameters']['cache']['host'] = $data->getHost();
                $yaml['parameters']['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "redis_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
                $yaml['parameters']['cache']['host'] = $data->getHost();
                $yaml['parameters']['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "win_cache_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "xcache_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "zend_data_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "file_system_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
                $yaml['parameters']['cache']['url'] = $data->getUrl();
            } elseif ($data->getType() == "php_file_cache") {
                unset($yaml['parameters']['cache']);
                $yaml['parameters']['cache']['type'] = $data->getType();
                $yaml['parameters']['cache']['url'] = $data->getUrl();
            }
            $yaml_dump = $dumper->dump($yaml, 3);
            file_put_contents('/var/www/html/bosonwithsandbox-tesis/app/config/parameters_boson.yml', $yaml_dump);
            return new Response();
        }


    }
}
