<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDocument extends Model
{
    protected $table = "contact_document";

    protected $fillable = ["contact_id", "document_id"];
}
