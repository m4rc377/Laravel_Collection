<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactStatus extends Model
{
    protected $table = "contact_status";

    protected $fillable = ["name"];
}
