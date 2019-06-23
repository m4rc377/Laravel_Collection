<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailboxUserFolder extends Model
{
    protected $table = "mailbox_user_folder";

    protected $fillable = ["user_id", "mailbox_id", "folder_id"];
}
