<?php

namespace UCI\Boson\CacheBundle\Controller;

use UCI\Boson\BackendBundle\Controller\BackendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BackendController
{
    /**
     * @Route(path="/cache/admin/scripts/config.cache.js", name="cache_app_config")
     */
    public function getAppAction()
    {
        return $this->jsResponse('CacheBundle:Scripts:config.js.twig');
    }

    

}
