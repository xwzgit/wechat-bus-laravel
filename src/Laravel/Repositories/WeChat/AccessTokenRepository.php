<?php
/**
 * 公众号第三方平台接口调用服务
 *
 */
namespace WeChatBus\Laravel\Repositories\WeChat;


use WeChatBus\Laravel\Models\WeChat\BasicAccessToken;
use WeChatBus\WeChatTokenServer;

class AccessTokenRepository extends Repository
{

    /**
     * 获取accessToken
     * @param $appId
     * @return bool|mixed
     */
    public static function getBasicAccessTokenDB($appId)
    {
        return BasicAccessToken::where('app_id',$appId)->first();
    }

    /**
     * 获取基础access Token
     *
     * @param $config
     * @param $appId
     * @return bool|mixed
     * @throws \Exception
     */
    public static function getBasicAccessToken($appId,$config)
    {
        $token = static::getBasicAccessTokenDB($appId);
        if($token) {
            //提前5分钟过期
            if($token->expire_int_at < time()) {
                //过期在请求一次
                $response = WeChatTokenServer::getBasicAccessToken($config);

                //更新
                static::updateBasicAccessToken($token,$response);

            }
        } else {
            $response = WeChatTokenServer::getBasicAccessToken($config);
            $token = static::storeBasicAccessToken($appId,$response);
        }
        if($token) {
            return $token->access_token;
        }
        return false;
    }

    /**
     * 存储基础AccessToken
     *
     * @param $response
     * @param $appId
     * @return BasicAccessToken|bool
     */
    public static function storeBasicAccessToken($appId,$response)
    {

        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $token = new BasicAccessToken();
        $token->app_id = $appId;
        $token->access_token = $response['access_token'];
        $token->expire_at = date('Y-m-d H:i:s', $timestamp);
        $token->expire_int_at = $timestamp;
        $token->error_msg = '';
        if ($token->save()) {
            return $token;
        }
        return false;
    }

    /**
     * 更新AccessToken
     *
     * @param $token
     * @param $response
     * @return bool
     */
    public static function updateBasicAccessToken($token,$response)
    {
        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $token->access_token = $response['access_token'];
        $token->expire_at = date('Y-m-d H:i:s',$timestamp);
        $token->expire_int_at = $timestamp;
        $token->error_msg = '';
        if($token->save()) {
            return $token;
        }
        return false;
    }
}