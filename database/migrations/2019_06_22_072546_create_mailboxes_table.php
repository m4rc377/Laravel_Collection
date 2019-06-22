<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string("subject");
            $table->longText("body")->nullable();
            $table->integer("sender_id");
            $table->string("folder")->default("Inbox");
            $table->tinyInteger("is_unread")->default(0);
            $table->tinyInteger("is_important")->default(0);
            $table->string("time_sent");
            $table->integer("parent_id")->default(0);
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
        Schema::dropIfExists('mailboxes');
    }
}
