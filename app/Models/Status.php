<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    
    protected $fillable = ['id', 'status'];

    /**
     *  Get the tasks associated with the status.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
