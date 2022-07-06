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

        return $this->request('account/login')
                    ->addPost('username', $username)
                    ->addPost('password', $password)
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
        return $this->request('media/like')
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
        return $this->request('media/unlike')
                    ->addParam('aweme_id', $awemeId)
                    ->getResponse();
    }

    /**
     * View a video.
     * 
     * @param awemeId The ID of the video you want to like.
     * 
     * @return The response from the server.
     */
    public function stats(
        $awemeId)
    {
        return $this->request('media/stats')
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
        return $this->request('media/comment')
                    ->addPost('aweme_id', $awemeId)
                    ->addPost('text', $text)
                    ->getResponse();
    }

   /**
    * It returns the comments of a video.
    * 
    * @param awemeId The ID of the video you want to get comments from.
    * @param cursor The cursor is a string that is used to paginate through the comments.
    * 
    * @return The response from the server.
    */
    public function getComments(
        $awemeId,
        $cursor = 0)
    {
        return $this->request('media/getComments')
                    ->addPost('aweme_id', $awemeId)
                    ->addPost('cursor', $cursor)
                    ->getResponse();
    }

    /**
     * Like a comment.
     * 
     * @param cid The ID of the comment you want to like.
     * @param awemeId The ID of the video.
     * 
     * @return The response from the server.
     */
    public function likeComment(
        $cid,
        $awemeId)
    {
        return $this->request('media/likeComment')
                    ->addParam('cid', $awemeId)
                    ->addParam('aweme_id', $awemeId)
                    ->getResponse();
    }

    /**
     * Unlike a comment.
     * 
     * @param cid The ID of the comment you want to unlike.
     * @param awemeId The ID of the video.
     * 
     * @return The response from the server.
     */
    public function unlikeComment(
        $cid,
        $awemeId)
    {
        return $this->request('media/unlikeComment')
                    ->addParam('cid', $awemeId)
                    ->addParam('aweme_id', $awemeId)
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
        return $this->request('user/follow')
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
        return $this->request('user/unfollow')
                    ->addParam('from', 0)
                    ->addParam('from_pre', -1)
                    ->addParam('type', 1)
                    ->addParam('channel_id', $channelId)
                    ->addParam('sec_user_id', $secUserId)
                    ->getResponse();
    }

   /**
    * This function gets the user feed for a given user id and cursor.
    * 
    * @param sec_user_id The user's ID.
    * @param cursor The cursor is a value that is used to paginate through the results. The first time
    * you make a request, you will not have a cursor, so you will pass 0. The response will contain a
    * new cursor value, which you will use in your next request.
    * 
    * @return An array of objects.
    */
    public function getUserFeed(
        $sec_user_id,
        $cursor = 0)
    {
        return $this->request('user/feed')
                    ->addParam('sec_user_id', $sec_user_id)
                    ->addParam('max_cursor', $cursor)
                    ->getResponse();
    }

    /**
     * This function gets the followers for a given user id.
     * 
     * @param sec_user_id The user's ID.
     * @param max_time The cursor is a value that is used to paginate through the results.
     * 
     * @return An array of objects.
     */
     public function getUserFollowers(
         $sec_user_id,
         $max_time = 0)
     {
         return $this->request('user/followers')
                     ->addParam('sec_user_id', $sec_user_id)
                     ->addParam('max_time', $max_time)
                     ->getResponse();
     }

    /**
     * This function gets the following for a given user id.
     * 
     * @param sec_user_id The user's ID.
     * @param cursor The cursor is a value that is used to paginate through the results.
     * 
     * @return An array of objects.
     */
     public function getUserFollowing(
         $sec_user_id,
         $max_time = 0)
     {
         return $this->request('user/following')
                     ->addParam('sec_user_id', $sec_user_id)
                     ->addParam('max_time', $max_time)
                     ->getResponse();
     }

    /**
    * This function returns the feed for a given challenge ID.
    * 
    * @param ch_id The challenge ID
    * @param cursor The cursor value is a string that is used to keep track of where you are in the
    * pagination.
    * 
    * @return An array of data.
    */
    public function getChallenge(
        $ch_id,
        $cursor = 0)
    {
        return $this->request('media/getChallenge')
                    ->addParam('ch_id', $ch_id)
                    ->addParam('cursor', $cursor)
                    ->getResponse();
    }

    /**
    * This function returns the feed for a given music ID.
    * 
    * @param music_id The music ID
    * @param cursor The cursor value is a string that is used to keep track of where you are in the
    * pagination.
    * 
    * @return An array of data.
    */
    public function getMusic(
        $music_id,
        $cursor = 0)
    {
        return $this->request('media/getMusic')
                    ->addParam('music_id', $music_id)
                    ->addParam('cursor', $cursor)
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
        return $this->request('user/info')
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
        return $this->request('search/general')
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
