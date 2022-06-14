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
class HttpInterface
{
    /**
     * The TikTok class instance we belong to.
     *
     * @var TikTokAPI\TikTok
     */
    protected $_parent;

    /**
     * Constructor.
     * 
     * @param parent The parent object.
     */
    public function __construct(
        $parent)
    {
        $this->_parent = $parent;
    }

    /**
     * It takes a request object, and sends it to the server.
     * 
     * @param request The request object
     * 
     * @return The response from the server.
     */
    public function sendRequest(
        $request)
    {
        $ch = curl_init();
        if (!empty($request->getParams())) {
            curl_setopt($ch, CURLOPT_URL, $request->getUrl().'?'.urldecode(http_build_query($request->getParams())));
        } else {
            curl_setopt($ch, CURLOPT_URL, $request->getUrl());
        }

        if (!empty($request->getPosts())) {
            if ($request->getEncoding() === 'json') {
                $request->addHeader('Content-Type', 'application/json');
            } else {
                $request->addHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());
        }

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);

        if ($this->_parent->debug === true) {
            $this->debug($request, $body);
        }

        return $this->api_body_decode($body, false);
    }

    /**
     * Print out the request and response in a readable format.
     * 
     * @param request The request object
     * @param response The response from the API
     */
    public function debug(
        $request,
        $response)
    {
        $method = $request->getPosts() == [] ? 'GET' : 'POST';
        if (!empty($request->getParams())) {
            echo "\033[1;33;m".strtoupper($method).": \033[0m".$request->getUrl().'?'.urldecode(http_build_query($request->getParams()))."\n";
        } else {
            echo "\033[1;33;m".strtoupper($method).": \033[0m".$request->getUrl()."\n";
        }

        if (!empty($request->getPosts())) {
            echo "\033[1;35;mDATA: \033[0m".$request->getBody()."\n";
        }
        echo "\033[1;32;mRESPONSE: \033[0m".$response."\n\n";
    }

    /**
     * Decode a JSON reply from TikTok's API.
     *
     * WARNING: EXTREMELY IMPORTANT! NEVER, *EVER* USE THE BASIC "json_decode"
     * ON API REPLIES! ALWAYS USE THIS METHOD INSTEAD, TO ENSURE PROPER DECODING
     * OF BIG NUMBERS! OTHERWISE YOU'LL TRUNCATE VARIOUS INSTAGRAM API FIELDS!
     *
     * @param string $json  The body (JSON string) of the API response.
     * @param bool   $assoc When FALSE, decode to object instead of associative array.
     *
     * @return object|array|null Object if assoc false, Array if assoc true,
     *                           or NULL if unable to decode JSON.
     */
    public static function api_body_decode(
        $json,
        $assoc = true)
    {
        return @json_decode($json, $assoc, 512, JSON_BIGINT_AS_STRING);
    }
}