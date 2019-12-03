<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeChatAuthorizeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_authorize_user_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('open_id',128)->charset('utf8')->collate('utf8_general_ci');
            $table->string('nickname',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('sex',10)->charset('utf8')->collate('utf8_general_ci');
            $table->string('province',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('city',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('country',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('headimgurl',200)->charset('utf8')->collate('utf8_general_ci');
            $table->string('privilege',512)->charset('utf8')->collate('utf8_general_ci');
            $table->string('unionid',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('language',20)->charset('utf8')->collate('utf8_general_ci')->defaut('');

            $table->timestamps();
            $table->index('open_id');
            $table->index('unionid');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_authorize_user_info');
    }
}
