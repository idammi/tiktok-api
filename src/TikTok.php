<?php
namespace TikTokAPI;

use \TikTokAPI\Exception\TikTokException;

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
class TikTok
{

    /**
     * Username.
     *
     * @var string
     */
    public $username = '';

    /**
     * Debug.
     * @var bool
     */
    public $debug = false;

    /**
     * Access key for authentication in REST API.
     * @var string
     */
    public $accessKey = '';

    /**
     * Proxy.
     *
     * @var string
     */
    public $proxy = '';

    /**
     * TikTok API constructor.
     *
     * @param bool  $debug         Enables debug mode.
     * 
     * @throws \TikTokAPI\Exception\TikTokException
     */
    public function __construct(
        $debug = false) 
    {
        $this->debug = $debug;
    }

    /**
     * Sets proxy.
     *
     * @param string $proxy Proxy.
     */
    public function setProxy(
        $proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * Sets the access key.
     *
     * @param string $accessKey Private API service access key.
     */
    public function setAccessKey(
        $accessKey)
    {
        $this->accessKey = $accessKey;
    }

    /**
     * Sets the active user.
     *
     * @param string $username   Username.
     */
    protected function setUser(
        $username)
    {
        $this->username = $username;
    }

    /**
     * Login.
     * 
     * It takes a username and password, sets the username, and then sends a request to the server with
     * the username and password encrypted.
     * 
     * @param username Your username
     * @param password The password of the account you want to login to.
     * 
     * @return The response from the server.
     */
    public function login(
        $username,
        $password)
    {
        $this->setUser($username);

        return $this->request('account/login/')
                    ->addPost('username', Signatures::xorEncrypt($username))
                    ->addPost('password', Signatures::xorEncrypt($password))
                    ->getResponse();
    }

    /**
     * Like a video.
     * 
     * @param awemeId The ID of the video you want to like.
     * 
     * @return The response from the server.
     */
    public function like(
        $awemeId)
    {
        return $this->request('media/like/')
                    ->addParam('aweme_id', $awemeId)
                    ->getResponse();
    }

    /**
     * Unlike a video.
     * 
     * @param awemeId The ID of the video you want to like.
     * 
     * @return The response from the server.
     */
    public function unlike(
        $awemeId)
    {
        return $this->request('media/unlike/')
                    ->addParam('aweme_id', $awemeId)
                    ->getResponse();
    }

    /**
     * Comment on a video.
     * 
     * @param awemeId The ID of the video you want to comment on.
     * @param text The text of the comment
     * 
     * @return The response from the server.
     */
    public function comment(
        $awemeId,
        $text)
    {
        return $this->request('media/comment/')
                    ->addPost('aweme_id', $awemeId)
                    ->addPost('text', $text)
                    ->getResponse();
    }

    /**
     * Follow a user.
     * 
     * @param secUserId The user id of the user you want to follow
     * @param channelId 3 is for the "for you" tab (TO-DO)
     * 
     * @return The response from the server.
     */
    public function follow(
        $secUserId,
        $channelId = 3)
    {
        return $this->request('user/follow/')
                    ->addParam('from', 0)
                    ->addParam('from_pre', -1)
                    ->addParam('type', 1)
                    ->addParam('channel_id', $channelId)
                    ->addParam('sec_user_id', $secUserId)
                    ->getResponse();
    }

    /**
     * Unfollow a user.
     * 
     * @param secUserId The user id of the user you want to follow
     * @param channelId 3 is for the "for you" tab (TO-DO)
     * 
     * @return The response from the server.
     */
    public function unfollow(
        $secUserId,
        $channelId = 3)
    {
        return $this->request('user/unfollow/')
                    ->addParam('from', 0)
                    ->addParam('from_pre', -1)
                    ->addParam('type', 1)
                    ->addParam('channel_id', $channelId)
                    ->addParam('sec_user_id', $secUserId)
                    ->getResponse();
    }

    /**
     * This function returns the user information of the user with the given sec_user_id.
     * 
     * @param secUserId The user's ID.
     * 
     * @return The response from the server.
     */
    public function getUserInfoById(
        $secUserId)
    {
        return $this->request('user/info/')
                    ->addParam('sec_user_id', $secUserId)
                    ->getResponse();
    }

    /**
     * This function searches for a keyword and returns the results.
     * 
     * @param query The search query
     * @param offset The offset of the first result to return.
     * @param count The number of results to return.
     * 
     * @return The response from the server.
     */
    public function search(
        $query,
        $offset = 0,
        $count = 10)
    {
        return $this->request('search/single/')
                    ->addPost('keyword', $query)
                    ->addPost('offset', $offset)
                    ->addPost('count', $count)
                    ->getResponse();
    }

    /**
     * The request function returns a new instance of the Request class, passing in the current
     * instance of the Client class and the endpoint.
     * 
     * @param endpoint The endpoint you want to call.
     * 
     * @return A new instance of the Request class.
     */
    public function request(
        $endpoint = '')
    {
        return new Request($this, $endpoint);
    }
}