<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenApiExceptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_api_exception', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action',50)->charset('utf8')->collate('utf8_general_ci');
            $table->string('api_url',1024)->charset('utf8')->collate('utf8_general_ci');
            $table->string('error_msg',1024)->charset('utf8')->collate('utf8_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_api_exception');
    }
}
