<?php

namespace App\Models\Api\V1;

use App\Models\Api\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class QueueLog extends Model
{
    use HasFactory;

    protected $table = "queue_logs";
    protected $fillable = [
        "message",
        "class_name",
        "status",
        "response",
    ];
}
