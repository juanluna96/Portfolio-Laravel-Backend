<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiographiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biographies', function (Blueprint $table) {
            $table->id();
            $table->json('description');
            $table->json('stacks_description');
            $table->json('about_me');
            $table->string('phone_1');
            $table->string('phone_2');
            $table->string('email_1');
            $table->string('email_2');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('biographies');
    }
}
