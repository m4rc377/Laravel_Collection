<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->integer('status');
            $table->string('referral_source')->nullable();
            $table->string('position_title')->nullable();
            $table->string('industry')->nullable();
            $table->string('project_type')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->text('project_description');
            $table->text('description')->nullable();
            $table->string('budget')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->integer('created_by_id')->unsigned();
            $table->integer('modified_by_id')->unsigned()->nullable();
            $table->integer('assigned_user_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('set null');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('modified_by_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact');
    }
}
