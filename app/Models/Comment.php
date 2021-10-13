<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    /**
    * Relation: comment belongs to user
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
