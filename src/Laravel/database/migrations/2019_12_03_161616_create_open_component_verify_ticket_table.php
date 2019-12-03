<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenComponentVerifyTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_component_verify_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->string('verify_ticket',512)->charset('utf8')->collate('utf8_general_ci');
            $table->timestamps();
            $table->index('created_at');
            $table->index('verify_ticket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_component_verify_ticket');
    }
}
