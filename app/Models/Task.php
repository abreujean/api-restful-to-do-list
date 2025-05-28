<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['id', 'hash', 'title', 'description', 'status_id'];

    /**
     * Get the status associated with the task.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
