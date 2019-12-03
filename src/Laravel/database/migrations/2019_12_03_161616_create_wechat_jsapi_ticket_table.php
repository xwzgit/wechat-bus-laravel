<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeChatJsTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_jsapi_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id',128)->charset('utf8')->collate('utf8_general_ci');
            $table->string('error_msg',200)->charset('utf8')->collate('utf8_general_ci');
            $table->string('ticket',600)->charset('utf8')->collate('utf8_general_ci');

            $table->dateTime('expire_at');
            $table->integer('expire_int_at');

            $table->timestamps();
            $table->index('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_jsapi_ticket');
    }
}
