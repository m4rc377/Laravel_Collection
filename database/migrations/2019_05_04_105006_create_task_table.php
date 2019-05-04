<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('priority')->nullable()->comment('Low Normal High Urgent');
            $table->integer('status')->unsigned()->nullable();
            $table->integer('type_id')->unsigned();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('complete_date')->nullable();
            $table->string('contact_type')->nullable()->comment('lead opportunity customer closed');
            $table->integer('contact_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by_id')->unsigned();
            $table->integer('modified_by_id')->unsigned()->nullable();
            $table->integer('assigned_user_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('status')->references('id')->on('task_status')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('task_type');
            $table->foreign('contact_id')->references('id')->on('contact')->onDelete('set null');
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
        Schema::dropIfExists('task');
    }
}
