<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactPhoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->integer('contact_id')->unsigned();

            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_phone');
    }
}
