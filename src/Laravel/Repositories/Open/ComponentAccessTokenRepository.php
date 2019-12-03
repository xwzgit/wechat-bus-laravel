<?php
/**
 *
 * 关注公众号用户
 *
 * User: owner
 * Date: 2018/9/20
 * Time: 17:38
 * Project Name: wechatConsole
 */

namespace WeChatBus\Laravel\Repositories\Open;

use WeChatBus\Laravel\Models\Open\ComponentAccessToken;
use WeChatBus\OpenApiServer;

class ComponentAccessTokenRepository extends Repository
{
    /**
     * 存储平台接口请求的Access Token
     *
     *
     * @param $token
     * @param $expireAt
     * @return ComponentVerifyTicket|bool
     */
    public static function storeAccessToken($token,$expireAt)
    {
        $ticket = new ComponentAccessToken();
        $ticket->access_token = $token;
        $ticket->expire_at = $expireAt;
        if($ticket->save()) {
            return $ticket;
        }

        return false;
    }

    /**
     * 获取平台的Access Token
     *
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getComponentAccessToken()
    {
        $expireAt = date('Y-m-d H:i:s',strtotime('-8 minutes'));
        $token = ComponentAccessToken::select(['access_token as accessToken','expire_at as expireAt'])
            ->where('expire_at','>',$expireAt)
            ->orderByDesc('created_at')
            ->first();

        if(!$token) {
            //获取最近的一个ticket
            $verifyTicket = VerifyTicketRepository::getVerifyTicket();

            $result = OpenApiServer::componentAccessToken(config('weChatBus'),$verifyTicket);
            $token = $result['component_access_token'];
            $expireAt = date('Y-m-d H:i:s',strtotime($result['expires_in'].' seconds'));
            static::storeAccessToken($token,$expireAt);
            return $token;
        }
        return $token->accessToken;
    }

}