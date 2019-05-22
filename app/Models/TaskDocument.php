<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    protected $table = "task_document";

    protected $fillable = ["task_id", "document_id"];
}
