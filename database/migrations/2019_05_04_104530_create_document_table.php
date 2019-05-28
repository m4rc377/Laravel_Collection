<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('file');
            $table->tinyInteger('status')->default(1)->comment('1=active 2=not active');
            $table->integer('type')->unsigned()->nullable();
            $table->string('publish_date')->nullable();
            $table->string('expiration_date')->nullable();
            $table->integer('created_by_id')->unsigned();
            $table->integer('modified_by_id')->unsigned()->nullable();
            $table->integer('assigned_user_id')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type')->references('id')->on('document_type')->onDelete('set null');
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
        Schema::dropIfExists('document');
    }
}
