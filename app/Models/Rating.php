<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'job_id',
        'from_user',
        'to_user',
        'rating',
        'review'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user');
    }
}
