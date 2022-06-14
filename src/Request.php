<?php

namespace TikTokAPI;

/**
 * TikTok REST API.
 *
 * TERMS OF USE:
 * - This code is in no way affiliated with, authorized, maintained, sponsored
 *   or endorsed by TikTok or any of its affiliates or subsidiaries. This is
 *   an independent and unofficial API. Use at your own risk.
 *
 * - We do NOT support or tolerate anyone who wants to use this API to send spam
 *   or commit other online crimes.
 *
 * - You will NOT use this API for marketing or other abusive purposes (spam,
 *   botting, harassment, massive bulk messaging...).
 *
 */
class Request
{
    /**
     * API Base url
     * @var string
     */
    const API_BASE = 'https://api.socialgenius.net/v1/';
    
    /**
     * The TikTok class instance we belong to.
     *
     * @var TikTokAPI\TikTok
     */
    protected $_parent;
    
    /**
     * An instance of TikTokAPI\HttpInterface.
     *
     * @var TikTokAPI\HttpInterface
     */
    protected $_http;

    /**
     * Endpoint URL (absolute or relative) for this request.
     *
     * @var string
     */
    protected $_endpoint;

    /**
     * An array of POST params.
     *
     * @var array
     */
    protected $_posts = [];

    /**
     * An array of query params.
     *
     * @var array
     */
    protected $_params = [];

    /**
     * An array of HTTP headers to add to the request.
     *
     * @var string[]
     */
    protected $_headers = [];

    /**
     * Default encoding of the request.
     * 
     * @var string
     */
    protected $_encoding = 'urlencode';

    /**
     * DefFlag to add default parameters to the request.
     * 
     * @var string
     */
    protected $_disableDefaultParams = false;

    /**
     * Constructor.
     * 
     * @param parent The parent object.
     * @param endpoint The endpoint of the API you're trying to access.
     */
    public function __construct(
        $parent,
        $endpoint)
    {
        $this->_parent = $parent;
        $this->_http = new HttpInterface($this->_parent);
        $this->_endpoint = $endpoint;
        $this->addBasicHeaders();
    }

    /**
     * Add query param to request, overwriting any previous value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function addParam(
        $key,
        $value)
    {
        if ($value === true) {
            $value = 'true';
        } elseif ($value === false) {
            $value = 'false';
        }
        $this->_params[$key] = $value;

        return $this;
    }

    /**
     * Get query param of request.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Add POST param to request, overwriting any previous value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function addPost(
        $key,
        $value)
    {
        if ($value === true) {
            $value = 'true';
        } elseif ($value === false) {
            $value = 'false';
        }
        $this->_posts[$key] = $value;

        return $this;
    }

    /**
     * Get POST param of request.
     *
     * @return array
     */
    public function getPosts()
    {
        return $this->_posts;
    }

    /**
     * Add default param to request.
     *
     * @return array
     */
    public function addDefaultParams()
    {
        if ($this->_parent->username === '') {
            throw new TikTokException('You need to set a User');
        }

        if ($this->_parent->proxy === '') {
            throw new ProxyException('You need to set a proxy');
        }

        $this->addParam('username', $this->_parent->username)
             ->addParam('proxy', $this->_parent->proxy);
    }

    /**
     * Add custom header to request, overwriting any previous or default value.
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addHeader(
        $key,
        $value)
    {
        $this->_headers[$key] = $value;

        return $this;
    }

    /**
     * Add default header to request.
     *
     * @return array
     */
    public function addBasicHeaders()
    {
        if ($this->_parent->accessKey === '') {
            throw new AccessKeyException('You need an access key to use this API');
        }

        $this->addHeader('Authorization', 'Bearer ' . $this->_parent->accessKey);
    }

    /**
     * Get header of request.
     * 
     * @param string $key
     *
     * @return mixed
     */
    public function getHeaders(
        $keyValueArray = false)
    {
        if ($keyValueArray === false) {
            $headers = [];
            foreach ($this->_headers as $key => $value) {
                $headers[] = sprintf('%s: %s', $key, $value);
            }

            return $headers;
        } else {
            return $this->_headers;
        }
    }

    /**
     * Get request URL.
     * 
     * @return string
     */
    public function getUrl()
    {
        return API_BASE.$this->_endpoint;
    }

    /**
     * Set encoding of the request.
     * 
     * @param string $encoding Request encoding type.
     * 
     * @return self
     */
    public function setEncoding(
        $encoding)
    {
        $this->_encoding = $encoding;

        return $this;
    }

    /**
     * Get encoding of the request.
     * 
     * @return $this
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Set flag to disable default param.
     * 
     * @return string
     */
    public function setDisableDefaultParams(
        $bool)
    {
        $this->_disableDefaultParams = $bool;

        return $this;
    }

    /**
     * Get flag to disable default param.
     * 
     * @return string
     */
    public function getDisableDefaultParams()
    {
        return $this->_disableDefaultParams;
    }

    /**
     * Get request body
     * 
     * @return mixed
     */
    public function getBody()
    {
        if ($this->getEncoding() === 'json') {
            return json_encode($this->getPosts());
        } elseif ($this->getEncoding() === 'urlencode') {
            return http_build_query($this->getPosts());
        } elseif ($this->getEncoding() === 'raw') {
            return null;
        }
    }

    /**
     * Return safely JSON-decoded HTTP response.
     * 
     * @throws \Exception
     * 
     * @return mixed
     */
    public function getResponse()
    {
        if ($this->getDisableDefaultParams() === false) {
            $this->addDefaultParams();
        }

        return $this->_http->sendRequest($this);
    }
}