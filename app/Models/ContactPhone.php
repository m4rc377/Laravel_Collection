<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    protected $table = "contact_phone";

    protected $fillable = ["phone", "contact_id"];
}
