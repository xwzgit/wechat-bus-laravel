<?php
/**
 * 公众号第三方平台接口调用服务
 *
 */
namespace WeChatBus\Laravel\Repositories\WeChat;


use WeChatBus\Laravel\Models\WeChat\AuthorizeUserInfo;
use WeChatBus\WeChatAuthServer;

class AuthorizeUserInfoRepository extends Repository
{
    /**
     * 授权用户信息
     *
     *
     * @param $params
     * @return mixed
     */
    public static function storeAuthorizeInfo($params)
    {
        return AuthorizeUserInfo::create($params);
    }

    /**
     * 根据条件获取授权用户信息
     *
     * @param string $openId
     * @param string $unionId
     * @return bool
     */
    public static function getAuthorizeInfo($openId = '',$unionId = '')
    {
        if($openId || $unionId) {
            $query = AuthorizeUserInfo::orderBy('created_at','desc');
            if($openId) {
                $query->where('open_id',$openId);
            }

            if($unionId) {
                $query->where('unionid',$unionId);
            }

            return $query->first();

        }
        return false;
    }

    /**
     * 更新授权用户新
     *
     * @param AuthorizeUserInfo $auth
     * @param $params
     * @return bool
     */
    public static function updateAuthorizeInfo(AuthorizeUserInfo $auth,$params)
    {

        foreach($params as $key => $val) {
            if(isset($auth->$key)) {
                $auth->$key = $val;
            }
        }
        return $auth->save();
    }

    /**
     * 获取用户信息
     *
     *
     * @param $config
     * @param $appId
     * @param $openId
     * @param $accessToken
     * @return mixed
     * @throws \Exception
     */
    public static function getAuthorizeInfoApi($config,$appId,$openId,$accessToken)
    {
        return WeChatAuthServer::getUserInfo($config,$appId,$openId,$accessToken);
    }

}