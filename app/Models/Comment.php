<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected  $table = 'comments';
    public $timestamps = false;//если created_at, updated_at нету

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
        'date'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
