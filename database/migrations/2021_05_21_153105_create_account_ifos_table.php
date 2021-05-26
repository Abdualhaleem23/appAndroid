<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountIfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ifos', function (Blueprint $table) {
            $table->bigIncrements('id_info');
            $table->unsignedBigInteger('user_id');
            $table->enum('set_get',['set','get']);
            $table->bigInteger('money')->default('0');
            $table->unsignedBigInteger('code_bank_user_1');
            $table->text('code_bank_user_2');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('account_ifos');
    }
}
