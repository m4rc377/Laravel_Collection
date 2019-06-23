<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mailbox extends Model
{
    protected $table = "mailbox";

    protected $fillable = ["subject", "body", "sender_id", "time_sent", "parent_id"];


    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receivers()
    {
        return $this->hasMany(MailboxReceiver::class);
    }

    public function attachments()
    {
        return $this->hasMany(MailboxAttachment::class, "mailbox_id");
    }

    public function replies()
    {
        return $this->hasMany(self::class, "parent_id")->where("parent_id", "<>", 0);
    }

    public function currentReceiver()
    {
        $curr = null;

        foreach ($this->receivers as $receiver) {

            if($receiver->receiver_id == Auth::user()->id) {
                $curr = $receiver;

                break;
            }
        }

        return $curr;
    }
}
