<?php
/**
 *
 *
 */
namespace WeChatBus\Laravel\Models\WeChat;


use Illuminate\Database\Eloquent\Model;

class AuthorizeUserInfo extends Model
{
    protected $table = 'wechat_authorize_user_info';

    protected $fillable = ['open_id','nickname','language','sex','province','city','country','headimgurl','privilege','unionid'];
}