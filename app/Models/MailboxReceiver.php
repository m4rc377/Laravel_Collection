<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailboxReceiver extends Model
{
    protected $table = "mailbox_receivers";

    protected $fillable = ["mailbox_id", "receiver_id"];
}
