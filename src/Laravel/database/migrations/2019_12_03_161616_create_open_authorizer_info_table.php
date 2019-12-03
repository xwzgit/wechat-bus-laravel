<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenAuthorizerInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_authorizer_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('authorizer_appid',256)->charset('utf8')->collate('utf8_general_ci')->comment('授权的公众号appid');
            $table->string('nick_name',128)->charset('utf8')->collate('utf8_general_ci')->comment('昵称');
            $table->string('head_img',512)->charset('utf8')->collate('utf8_general_ci')->comment('头像url');
            $table->string('user_name',255)->charset('utf8')->collate('utf8_general_ci')->comment('授权方公众号的原始ID');
            $table->string('principal_name',255)->charset('utf8')->collate('utf8_general_ci')->comment('公众号主体名称');
            $table->string('uid',64)->charset('utf8')->collate('utf8_general_ci')->comment('用户uid');
            $table->string('alias',255)->charset('utf8')->collate('utf8_general_ci')->comment('授权方公众号所设置的微信号');
            $table->string('business_info',255)->charset('utf8')->collate('utf8_general_ci')->comment('');
            $table->string('qrcode_url',512)->charset('utf8')->collate('utf8_general_ci')->comment('二维码');
            $table->string('func_info',512)->charset('utf8')->collate('utf8_general_ci')->comment('公众号授权给开发者的权限集列表，ID为1到15时分别代表： 1.消息管理权限 2.用户管理权限 3.帐号服务权限 4.网页服务权限 5.微信小店权限 6.微信多客服权限 7.群发与通知权限 8.微信卡券权限 9.微信扫一扫权限 10.微信连WIFI权限 11.素材管理权限 12.微信摇周边权限 13.微信门店权限 14.微信支付权限 15.自定义菜单权限 请注意： 1）该字段的返回不会考虑公众号是否具备该权限集的权限（因为可能部分具备），请根据公众号的帐号类型和认证情况，来判断公众号的接口权限。');
            $table->tinyInteger('service_type_info')->default(1)->comment('授权方公众号类型：授权方公众号类型，0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号');
            $table->tinyInteger('verify_type_info')->default(1)->comment('授权方认证类型：授权方认证类型，-1代表未认证，0代表微信认证，1代表新浪微博认证，2代表腾讯微博认证，3代表已资质认证通过但还未通过名称认证，4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证');
            $table->tinyInteger('activated')->default(1)->comment('是否授权');
            $table->tinyInteger('authorized')->default(1)->comment('是否激活');
            $table->tinyInteger('check_subscribe')->default(0)->comment('校验关注');
            $table->tinyInteger('open_authorization')->default(0)->comment('开通平台代发微信网页授权');
            $table->timestamps();
            $table->index('authorizer_appid');
            $table->index(['authorized','activated']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_authorizer_info');
    }
}
