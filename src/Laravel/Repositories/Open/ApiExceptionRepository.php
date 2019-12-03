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



use WeChatBus\Laravel\Models\Open\ApiException;
use WeChatBus\Laravel\Models\Open\EncryptData;

class ApiExceptionRepository extends Repository
{
    /**
     * 添加接口请求异常记录
     *
     * @param $action
     * @param $url
     * @param $msg
     */
    public static function addException($action,$url,$msg)
    {
        $e = new ApiException();
        $e->action = $action;
        $e->api_url = $url;
        $e->error_msg = $msg;
        $e->save();
    }

    /**
     * 存储ticket 推送数据
     *
     * @param $params
     */
    public static function storeTicketData($params)
    {
        $d = new EncryptData();
        if(isset($params['app_id'])) {
            $d->app_id = $params['app_id'] ;
        }

        $d->origin_data = $params['originContent'] ;
        $d->component_verify_ticket =  $params['verifyType'];
        $d->decrypt_data =  $params['decryptData'];
        $d->sign =  $params['signature'];
        $d->msg_sign =  $params['msg_signature'];
        $d->timestamp =  $params['timestamp'];
        $d->nonce =  $params['nonce'];
        $d->save();
    }
}