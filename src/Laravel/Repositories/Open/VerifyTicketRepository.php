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



use WeChatBus\Laravel\Models\Open\ComponentVerifyTicket;
use WeChatBus\OpenApiServer;
use WeChatBus\Support\Log\Log;

class VerifyTicketRepository extends Repository
{
    /**
     * 存储公众号服务器的验证ticket
     *
     * @param $verifyTicket
     * @return ComponentVerifyTicket|bool
     */
    public static function storeNewVerifyTicket($verifyTicket)
    {
        $ticket = new ComponentVerifyTicket();
        $ticket->verify_ticket = $verifyTicket;
        if($ticket->save()) {
            return $ticket;
        }

        return false;
    }

    /**
     * 获取平台的最新校验码
     *
     * @return bool
     */
    public static function getVerifyTicket()
    {
        $ticket = ComponentVerifyTicket::select(['verify_ticket as verifyTicket'])
            ->orderByDesc('created_at')
            ->first();
        if($ticket) {
            return $ticket->verifyTicket;
        } else {
            throw new \Exception('verify ticket 不存在','911');
        }
    }

    /**
     * 解绑授权
     *
     * @param $msg
     */
    public static function unauthorized($msg)
    {
        if($authInfo = AuthorizeInfoRepository::getAuthorizeInfo($msg['AuthorizerAppid'])) {
            $authInfo->authorized = 0;
            $authInfo->activated = 0;
            $authInfo->save();

            if($accTk = AuthAccessTokenRepository::getAuthAccessToken($msg['AuthorizerAppid'])) {
                $accTk->authorized = 0;
                $accTk->save();
            }
        }

    }


    /**
     * 平台测试推送授权
     *
     *
     * @param $data
     */
    public static function defaultAuthorized($data)
    {
        try{
            if($authCode = $data['AuthorizationCode']) {
                //通过授权码，获取用户Access Token
                $comAccessToken = ComponentAccessTokenRepository::getComponentAccessToken();

                //获取授权Access Token,授权信息
                $authInfo = AuthAccessTokenRepository::getAuthAccessTokenApi($comAccessToken,$authCode);


                $response = OpenApiServer::authorizeInfo(config('weChatBus'),$comAccessToken,$data['AuthorizerAppid']);
                AuthorizeInfoRepository::storeAuthorizeInfo($response,$data['AuthorizerAppid']);
            }
        } catch (\Exception $exception) {
            Log::error('defaultAuthorized',[$exception->getCode(),$exception->getMessage()]);
        }

    }

}