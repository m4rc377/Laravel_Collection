<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEmail extends Model
{
    protected $table = "contact_email";

    protected $fillable = ["email", "contact_id"];
}
