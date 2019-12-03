<?php
/**
 * 公众号第三方平台接口调用服务
 *
 */
namespace WeChatBus\Laravel\Repositories\Open;


use WeChatBus\Laravel\Models\Open\AuthorizeAccessToken;
use WeChatBus\OpenApiServer;

class AuthAccessTokenRepository extends Repository
{
    /**
     * 存储调用公众号接口的Access Token
     *
     *
     * @param $authInfo
     * @return ComponentVerifyTicket|bool
     */

    public static function storeAuthAccessToken($authInfo)
    {

        if(($accToken = static::getAuthAccessToken($authInfo['authorizer_appid']))) {
            return static::updateAccessToken($accToken,$authInfo);
        } else {
            $accToken = new AuthorizeAccessToken();
            foreach ($authInfo as $key => $val) {
                switch ($key) {
                    case 'authorizer_appid':
                        $accToken->authorizer_appid = $val;
                        break;
                    case 'authorizer_access_token':
                        $accToken->authorizer_access_token = $val;
                        break;
                    case 'expires_in':
                        $timestamp = strtotime(($val-300).' seconds');

                        $expireAt = date('Y-m-d H:i:s',$timestamp);
                        $accToken->expire_at = $expireAt;
                        break;
                    case 'authorizer_refresh_token':
                        $accToken->authorizer_refresh_token = $val;

                        break;
                }
            }
            $accToken->authorized = 1;

            if($accToken->save()) {
                return $accToken;
            }

        }

        return false;
    }

    /**
     * 接口获取公众号授权令牌
     *
     * @param $comAccessToken
     * @param $authCode
     * @return mixed
     * @throws \Exception
     */
    public static function getAuthAccessTokenApi($comAccessToken,$authCode)
    {
        $authAccessToken = OpenApiServer::authAccessToken(config('weChatBus'),$comAccessToken,$authCode);

        //存贮用户Access Token
        $authInfo = $authAccessToken['authorization_info'];
        static::storeAuthAccessToken($authInfo);

        return $authInfo;
    }

    /**
     * 查询授权信息是否存在
     *
     * @param $appId
     * @return mixed
     *
     */
    public static function getAuthAccessToken($appId)
    {
        return AuthorizeAccessToken::select(['id','authorizer_appid as appId',
            'authorized','authorizer_access_token as accTk',
            'authorizer_refresh_token as accRefTk','expire_at as expireAt'])
            ->where('authorizer_appid',$appId)
            ->first();
    }


    /**
     * 获取平台预授权码，存储有效期600秒，提前30秒过期
     *
     * @param $appId
     * @return bool
     * @throws \Exception
     */
    public static function getAuthAccessTokenActive($appId)
    {
        $accTk = static::getAuthAccessToken($appId);
        if($accTk) {
            //有效提前5分钟过期，刷新token
            if($accTk->expireAt > date('Y-m-d H:i:s')) {
                return $accTk->accTk;
            } else {
                //过期
                $comAccessToken = ComponentAccessTokenRepository::getComponentAccessToken();
                $response = OpenApiServer::refreshAuthAccessToken(config('weChatBus'),$comAccessToken,$accTk->appId,$accTk->accRefTk);
                static::updateAccessToken($accTk,$response);

                return $response['authorizer_access_token'];
            }
        }
        return false;
    }

    /**
     * 更新Access Token
     *
     *
     * @param $accTk
     * @param $response
     * @param int $authorized
     * @return mixed
     */
    public static function updateAccessToken($accTk,$response,$authorized = 1)
    {
        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $update = [
            'authorizer_access_token' => $response['authorizer_access_token'],
            'expire_at' => date('Y-m-d H:i:s',$timestamp),
            'authorizer_access_token' => $response['authorizer_access_token'],
            'authorized' => $authorized
        ];
        return AuthorizeAccessToken::where('id',$accTk->id)
            ->update($update);
    }
}