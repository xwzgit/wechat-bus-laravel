<?php
/**
 * 公众号第三方平台接口调用服务
 *
 */
namespace WeChatBus\Laravel\Repositories\Open;

use WeChatBus\OpenApiServer;

class BaseAuthorizeRepository extends Repository
{
    /**
     * 第三方公众号平台授权码地址获取
     *
     *
     * @param $config
     * @return mixed
     * @throws \Exception
     */
    public static function openAuthorizeUrl($config)
    {
        $comAccessToken = ComponentAccessTokenRepository::getComponentAccessToken();

        $result = OpenApiServer::preAuthCode($config,$comAccessToken);

        return OpenApiServer::createAuthUrl($result['pre_auth_code']);

    }
}