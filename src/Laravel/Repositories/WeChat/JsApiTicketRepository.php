<?php
/**
 * 公众号第三方平台接口调用服务
 *
 */
namespace WeChatBus\Laravel\Repositories\WeChat;



use WeChatBus\Laravel\Models\Open\JsApiTicket;
use WeChatBus\WeChatTokenServer;

class JsApiTicketRepository extends Repository
{

    /**
     * 查询数据库Ticket
     * @param $appId
     * @return mixed
     */
    public static function getTicketDB($appId)
    {
        return JsApiTicket::where('app_id',$appId)
            ->first();
    }

    /**
     * 获取基础access Token
     *
     *
     * @param $appId
     * @param $accessToken
     * @return bool|mixed
     * @throws \Exception
     */
    public static function getJsApiTicket($appId,$accessToken)
    {
        $ticket = static::getTicketDB($appId);
        if($ticket) {
            //提前5分钟过期
            if($ticket->expire_int_at < time()) {

                //过期在请求一次
                $response = WeChatTokenServer::getJsApiTicket($accessToken);
                //更新
                static::updateJsApiTicket($ticket,$response);
            }
        } else {
            $response = WeChatTokenServer::getJsApiTicket($accessToken);
            $ticket = static::storeJsApiTicket($appId,$response);
        }
        if($ticket) {
            return $ticket->ticket;
        }
        return false;
    }

    /**
     * 存储基础AccessToken
     *
     * @param $appId
     * @param $response
     * @return BasicAccessToken|bool
     */
    public static function storeJsApiTicket($appId,$response)
    {
        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $ticket = new JsApiTicket();
        $ticket->app_id = $appId;
        $ticket->ticket = $response['ticket'];
        $ticket->expire_at = date('Y-m-d H:i:s',$timestamp);
        $ticket->expire_int_at = $timestamp;
        $ticket->error_msg = '';
        if($ticket->save()) {
            return $ticket;
        }
        return false;
    }

    /**
     * 更新AccessToken
     *
     * @param $ticket
     * @param $response
     * @return bool
     */
    public static function updateJsApiTicket($ticket,$response)
    {
        $timestamp = strtotime(($response['expires_in']-300).' seconds');

        $ticket->ticket = $response['ticket'];
        $ticket->expire_at = date('Y-m-d H:i:s',$timestamp);
        $ticket->expire_int_at = $timestamp;
        $ticket->error_msg = '';
        if($ticket->save()) {
            return $ticket;
        }
        return false;
    }
}