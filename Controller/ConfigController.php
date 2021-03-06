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
     *  Devuelve la ruta del fichero de configuración de IUX dado una ruta inicial de configuración
     *  y un arreglo con el nombre del componente a buscar
     *  @param string $route * @param mixed $params
     *  @return bool|null|string
     */
    private function findFileConfig($route = null, $params)
    {
        $yaml = new Parser();
        $recursos = $yaml->parse(file_get_contents($route));
        if (array_key_exists($params['bundle'], $fileParsed = $recursos)) {
            return $route;
        } else {
            if (!array_key_exists('imports', $recursos)) {
                return false;
            }
            foreach ($recursos['imports'] as $recurso) {
                $arrayParts = explode(DIRECTORY_SEPARATOR, $recurso['resource']);
                $urlResource = $arrayParts[0];
                if (substr($urlResource, 0, 1) == "@") {
                    $dirBundle = substr($urlResource, 1);
                    unset($arrayParts[0]);
                    $urlResource = $this->container->get("kernel")->getBundle($dirBundle)->getPath() . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $arrayParts);
                } else {
                    $arrayRouteParts = explode(DIRECTORY_SEPARATOR, $route);
                    $urlResource = explode($arrayRouteParts[count($arrayRouteParts) - 1], $route)[0] . $urlResource;
                }
                if ($rutaResp = $this->findFileConfig($urlResource, $params)) {
                    return $rutaResp;
                } else {
                    continue;
                }
            }
        }
    }

    /**
     * Lee el contenido del fichero de configuración de Cache
     *
     * @return mixed
     */
    public function readYAMLAction()
    {
        $yaml = new Parser();
        $params = array('bundle' => 'cache');
        $dirInicial = $this->container->get("kernel")->getRootDir() . '/config/config.yml';
        $dirCache = $this->findFileConfig($dirInicial, $params);
        $values = $yaml->parse(file_get_contents($dirCache));
        return $values;
    }

    /**
     * Carga la configuración inicial de la caché y se la envía a la vista.
     * @return JsonResponse
     * @Route(path="/cache/currentinfo", name="cache_current_info", options={"expose"=true})
     */
    public function showCurrentInfoAction()
    {
        $values = $this->readYAMLAction();
        $arr = $values['cache'];
        $response = new JsonResponse($arr);
        return $response;
    }

    /**
     * Obtiene el token para que los formularios de angular trabajen.
     *
     * @Route("/cache/csrf_token", name="cache_csrf_form", options={"expose"=true})
     * @Method("POST")
     */
    public function getCsrfTokenAction(Request $request)
    {
        $tokenId = $request->request->get('id_form');
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken($tokenId);
        return new Response($token);
    }
    
    /**
     * Utiliza los servicios de caché para eliminar el contenido de la misma
     * Responde al RF(78) Limpiar la caché
     * @return Response     *
     * @Route(path="/cache/erasecache", name="cache_erase", options={"expose"=true})
     */
    public function eraseCacheAction()
    {
        $cache = $this->get('uci.boson.cache')->deleteAll();

        if ($cache) {
            return new Response("La caché se ha limpiado satisfactoriamente.", 200);
        } else {
            return new Response("Error al limpiar la caché. El tipo de caché seleccionado no se encuentra correctamente
            instalado y/o configurado en el servidor.", 500);
        }
    }

    /**
     * Escribe en el fichero yml la nueva configuración de caché
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
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "array_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "memcache_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
                $yaml['cache']['host'] = $data->getHost();
                $yaml['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "memcached_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
                $yaml['cache']['host'] = $data->getHost();
                $yaml['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "redis_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
                $yaml['cache']['host'] = $data->getHost();
                $yaml['cache']['port'] = $data->getPort();
            } elseif ($data->getType() == "win_cache_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "xcache_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "zend_data_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
            } elseif ($data->getType() == "file_system_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
                $yaml['cache']['url'] = $data->getUrl();
            } elseif ($data->getType() == "php_file_cache") {
                unset($yaml['cache']);
                $yaml['cache']['type'] = $data->getType();
                $yaml['cache']['url'] = $data->getUrl();
            }
            $yaml_dump = $dumper->dump($yaml, 3);
//            $url = $this->getParameter('kernel.root_dir') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters_boson.yml';
            $params = array('bundle' => 'cache');
            $dirInicial = $this->container->get("kernel")->getRootDir() . '/config/config.yml';
            $dirCache = $this->findFileConfig($dirInicial, $params);
            file_put_contents($dirCache, $yaml_dump);
            return new Response("La caché se ha configurado satisfactoriamente.", 200);
        }
    }
}
