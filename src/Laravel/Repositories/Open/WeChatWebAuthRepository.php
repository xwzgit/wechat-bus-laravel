<?php
/**
 *
 * 第三方平台代理公众号处理事务
 *
 * User: owner
 * Date: 2018/9/20
 * Time: 17:38
 * Project Name: wechatConsole
 */

namespace WeChatBus\Laravel\Repositories\Open;


use WeChatBus\Laravel\Models\WeChat\WebAccessToken;
use WeChatBus\WeChatAuthServer;

class WeChatWebAuthRepository extends Repository
{
    /**
     * 获取网页授权地址
     *
     *
     * @param $config
     * @return mixed
     * @throws \Exception
     */
    public static function webAuthorizeUrl($config)
    {
        return WeChatAuthServer::getAuthCodeUrl($config);
    }

    /**
     * 更加CODE 获取access token 和openID
     *
     *
     * @param $config
     * @param $appId
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public static function webAccessToken($config,$appId,$code)
    {
        $componentAccessToken = '';
        //第三方平台代理授权
        if($config['third_authorized']) {
            $componentAccessToken = ComponentAccessTokenRepository::getComponentAccessToken();
        }

        $response = WeChatAuthServer::getAccessToken($config,$code,$appId,$componentAccessToken);
        static::storeWebAccessToken($appId,$response);
        return $response;
    }

    /**
     * 存储基础网页授权token
     *
     * @param $appId
     * @param $response
     * @return BasicAccessToken|bool
     */
    public static function storeWebAccessToken($appId,$response)
    {

        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $token = new WebAccessToken();
        $token->app_id = $appId;
        $token->open_id = $response['openid'];
        $token->access_token = $response['access_token'];
        $token->refresh_token = $response['refresh_token'];
        $token->scope = $response['scope'];
        $token->expire_at = date('Y-m-d H:i:s',$timestamp);
        $token->expire_int_at = $timestamp;
        $token->refresh_expire = date('Y-m-d H:i:s',strtotime('29 days'));
        $token->is_third_part = 1;

        if ($token->save()) {
            return $token;
        }
        return false;
    }

    /**
     * 根据open id 获取用户Token
     *
     *
     * @param $config
     * @param $appId
     * @param $openId
     * @return mixed
     * @throws \Exception
     */
    public static function getWebAccessToken($config,$openId,$appId)
    {
        $token = WebAccessToken::where('open_id',$openId)
            ->where('app_id',$appId)
            ->where('refresh_expire','>',date('Y-m-d H:i:s'))
            ->orderBy('created_at','desc')
            ->first();

        $reAuthorize = false;
        if($token) {
            //access token 过期,刷新access token
            if($token->expire_int_at < time()) {

                $componentAccessToken = '';
                //第三方平台代理授权
                if($config['third_authorized']) {
                    $componentAccessToken = ComponentAccessTokenRepository::getComponentAccessToken();
                }

                $response = WeChatAuthServer::refreshAccessToken($config,$token->refresh_token,$appId,$componentAccessToken);
                static::updateWebAccessToken($token,$response);
            }

            if(!$reAuthorize) {
                return $token->access_token;
            }

        } else {
            $reAuthorize = true;
        }

        if($reAuthorize) {
            return static::webAuthorizeUrl($config );
        }

    }

    /**
     * 更新access token
     *
     * @param $token
     * @param $response
     */
    public static function updateWebAccessToken($token,$response)
    {
        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $token->access_token = $response['access_token'];
        $token->refresh_token = $response['refresh_token'];
        $token->expire_at = date('Y-m-d H:i:s',$timestamp);
        $token->expire_int_at = $timestamp;
        $token->save();

    }

}