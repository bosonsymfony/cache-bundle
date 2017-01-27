<?php

namespace UCI\Boson\CacheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Data
 *
 */
class Data
{

    /**
     * @var string
     *
     */
    private $type;

    /**
     * @var string
     *
     */
    private $host;

    /**
     * @var string
     *
     */
    private $port;

    /**
     * @var string
     *
     */
    private $url;


    /**
     * Set type
     *
     * @param string $type
     *
     * @return Data
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return Data
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set port
     *
     * @param string $port
     *
     * @return Data
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Data
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}

