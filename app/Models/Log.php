<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = "log";

    protected $fillable = array("action", "user_id", "request", "http_method", "ip", "page");


    /**
     * get the user object who made this log
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
