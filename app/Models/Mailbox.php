<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Mailbox extends Model
{
    protected $table = "mailboxes";

    protected $fillable = ["subject", "body", "sender_id", "folder", "is_unread", "is_important", "time_sent", "parent_id"];


    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receivers()
    {
        return $this->hasMany(MailboxReceiver::class, "mailbox_id");
    }

    public function attachments()
    {
        return $this->hasMany(MailboxAttachment::class, "mailbox_id");
    }

    public function replies()
    {
        return $this->hasMany(self::class, "parent_id")->where("parent_id", "<>", 0);
    }
}
