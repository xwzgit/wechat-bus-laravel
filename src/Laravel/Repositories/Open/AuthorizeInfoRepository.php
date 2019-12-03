<?php
/**
 * 授权公众号信息
 *
 */
namespace WeChatBus\Laravel\Repositories\Open;



use WeChatBus\Laravel\Models\Open\AuthorizeInfo;
use WeChatBus\OpenApiServer;

class AuthorizeInfoRepository extends Repository
{
    /**
     * 存储公众号授权信息
     *
     *
     * @param $response
     * @param $userName
     * @return ComponentVerifyTicket|bool
     */

    public static function storeAuthorizeInfo($response,$userName)
    {
        $authorizeInfo = $response['authorizer_info'];
        $authInfo = $response['authorization_info'];
        if(($info = static::getAuthorizeInfo($authInfo['authorizer_appid']))) {
            static::processInfo($info,$authInfo,$authorizeInfo);
        } else {
            $info = new AuthorizeInfo();
            static::processInfo($info,$authInfo,$authorizeInfo);
        }
        $info->uid = $userName;

        $info->authorized = 1;
        $info->activated = 1;

        if($info->save()) {
            return $info;
        }
        return false;
    }

    public static function getAuthorizeInfoApi($comAccessToken,$authorizeAppId,$userName)
    {
        //获取授权工作号信息
        $response = OpenApiServer::authorizeInfo(config('weChatBus'),$comAccessToken,$authorizeAppId);

        //存储公众号信息
        return static::storeAuthorizeInfo($response,$userName);
    }

    /**
     * 查询授权信息是否存在
     *
     * @param $appId
     * @return mixed
     *
     */
    public static function getAuthorizeInfo($appId)
    {
        return AuthorizeInfo::where('authorizer_appid',$appId)->first();
    }

    /**
     * 处理授权获取到的参数信息
     *
     * @param $info
     * @param $authInfo
     * @param $authorizeInfo
     */
    protected static function processInfo($info,$authInfo,$authorizeInfo)
    {
        foreach ($authorizeInfo as $key => $val) {
            switch ($key) {
                case 'nick_name':
                case 'head_img':
                case 'user_name':
                case 'principal_name':
                case 'alias':
                case 'qrcode_url':
                    $info->$key = $val;
                    break;
                case 'verify_type_info':
                case 'service_type_info':
                    $info->$key = $val['id'];
                    break;
                case 'business_info':
                    $info->business_info = json_encode($val,JSON_UNESCAPED_UNICODE);
                    break;
            }
        }

        $info->authorizer_appid = $authInfo['authorizer_appid'];
        $funcInfo=',';
        if($f = $authInfo['func_info']) {
            foreach( $f as $item) {
                $funcInfo .= $item['funcscope_category']['id'].',';
            }
        }

        $info->func_info = $funcInfo;

    }


    /**
     * 获取授权绑定的公众号
     *
     * @return mixed
     */
    public static function getAuthInfo()
    {
        return AuthorizeInfo::get();
    }
}