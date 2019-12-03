<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenEncryptDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_encrypt_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id',64)->charset('utf8')->collate('utf8_general_ci')->default('');
            $table->text('origin_data')->charset('utf8')->collate('utf8_general_ci')->nullable();
            $table->test('decrypt_data')->charset('utf8')->collate('utf8_general_ci')->nullable();
            $table->string('component_verify_ticket',64)->charset('utf8')->collate('utf8_general_ci');
            $table->string('sign',100)->charset('utf8')->collate('utf8_general_ci')->nullable();
            $table->string('msg_sign',100)->charset('utf8')->collate('utf8_general_ci')->nullable();
            $table->string('nonce',200)->charset('utf8')->collate('utf8_general_ci')->nullable();

            $table->integer('timestamp');
            $table->timestamps();
            $table->index('app_id');
            $table->index('component_verify_ticket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_encrypt_data');
    }
}
