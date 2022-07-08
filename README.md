<img src="https://github.com/idammi/tiktok-api/blob/main/assets/tiktok.png" width=75 align=left> <h1>TikTok Private API</h1>
API Wrapper for TikTok private API access
<br>


## How does this work?
Monthly subscription of my private API service is required for this to function.


## API Access Key

You can use ```Authorization: Bearer <Access key>``` in the request header or use ```?key=<Access key>``` in the request parameter. 


## Usage
```php

/////////// API  //////////
$debug = false;
$accessKey = 'YOUR_ACCESS_KEY';
$proxy = 'http://user:pass@proxy:port';
///////////////////////////
$tiktok = new \TikTokAPI\TikTok($debug);
$tiktok->setAccessKey($accessKey);
$tiktok->setProxy($proxy);
```


## Available methods
- `login` - Login user/resume logged in session `login($username, $password)`
- `like` - Like a post `like($awemeId)`
- `unlike` - Unlike a post `unlike($awemeId)`
- `stats` - View a post `stats($awemeId)`
- `comment` - Comment on a post `comment($awemeId, $commentText)`
- `getComments` - Get comments on a post `getComments($awemeId, $cursor)`
- `likeComment` - Like a comment `likeComment($cid, $awemeId)`
- `unlikeComment` - Unlike a comment `unlikeComment($cid, $awemeId)`
- `follow` - Follow a user `follow($secUserId)`
- `unfollow` - Unfollow a user `unfollow($secUserId)`
- `getUserFeed` - Get user posts `getUserFeed($secUserId, $cursor)`
- `getUserFollowers` - Get user followers `getUserFollowers($secUserId, $max_time)`
- `getUserFollowing` - Get user following `getUserFollowing($secUserId, $max_time)`
- `getChallenge` - Get challenge feed `getChallenge($ch_id, $cursor)`
- `getMusic` - Get Music feed `getMusic($music_id, $cursor)`
- `getUserInfo` - Get user info `getUserInfo($secUserId)`
- `search` - General search `search($keyword)`

## Example Login Response
```json
{
    "data": {
        "app_id": 1233,
        "avatar_url": "",
        "connects": [],
        "country_code": 234,
        "device_id": 0,
        "email": "",
        "has_password": 1,
        "is_kids_mode": 0,
        "mobile": "+234****3724",
        "name": "user5180781450262",
        "screen_name": "user5180781450262",
        "sec_user_id": "MS4wLjABAAAAdsnqxZXSEeRzp4ppOc8Zndo14IaYeNxiABwTPfbGkxYCWr4OCzQF90JgJPC33jD6",
        "session_key": "7f6f103535941dc2054baa0446a5cf3a",
        "user_id": 7058506727294240000,
        "user_id_str": "7058506727294239749",
        "user_verified": false
    },
    "message": "success"
}
```

## TikTok Private API service subscription pricing

| Package | Cost(per month) | Account Limit | Quota (requests/day) | Quota (requests/month) |
| ------- | :---------------: | ----------: | --------------: | -----------------: |
| **Starter** | 50 USD | 5 | 5,000 | ~150,000 |
| **Pro** *(popular)* | 100 USD | 10 | 12,000 | ~360,000 |
| **Business** | 200 USD | 20 | 25,000 | ~720,000 |
| **Custom** | custom pricing | ? | ? | ? |

- These quota counts doesn't include Account Login requests unless it's a new login which requires device registration.


## Private API backend functionalities

- Device registration for registering `device_id`, `install_id` also known as `iid` and `did`.
    - Proper `trace-id` header generation.
    - Proper `X-Gorgon` and `X-Khronos` header generation.
    - Proper `TTEncrypt`ing of data (v05).
    - Proper `XLog`ing of registered device_id (v02).

- Account Login
    - Complete device registration when logging in for the first time.
    - Account Login with username/password.
    - Automated captcha solver.


## Terms and conditions

- You will NOT use this API for marketing purposes (spam, botting, harassment, massive bulk messaging...).
- We do NOT give support to anyone who wants to use this API to send spam or commit other crimes.
- We reserve the right to block any user of this repository that does not meet these conditions.

## Legal

This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by TikTok or any of its affiliates or subsidiaries. This is an independent and unofficial API. Use at your own risk.

##  Disclaimer
TikTok is always updating their API endpoints but I will keep updating this library. I take no responsibility if your IP or your acccount gets banned using this API. It's recommended that you use proxy.

If you want, you can reach me on Telegram: https://t.me/dologbonjaye
