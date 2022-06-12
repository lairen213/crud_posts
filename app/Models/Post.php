<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected  $table = 'posts';
    public $timestamps = false;//если created_at, updated_at нету

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'slug',
        'title',
        'short_description',
        'description',
        'date_publish',
        'deleted'
    ];

    /**
     * Get the comments for the post.
     */
    public function comments(){
        return $this->hasMany(Comment::class)->orderBy('date', 'DESC');
    }
}
