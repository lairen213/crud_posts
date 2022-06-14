<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
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
    public function comments($orderBy_type = 'date')
    {
        if ($orderBy_type == 'rate') { // sort comments by rate

            //I made this kind of query in the database, because if I had done it with groupBy then paginate wouldn't have worked and I would have had to write it myself
            return $this->hasMany(Comment::class)
                ->select('*')
                ->addSelect(DB::raw("(SELECT COUNT(`comment_reactions`.`id`) FROM `comment_reactions` WHERE `comment_reactions`.`comment_id` = `comments`.`id` AND `comment_reactions`.`type` = 'like')  AS `total_likes`"))
                ->orderBy('total_likes', 'DESC');
        } else { // sort comments by date
            return $this->hasMany(Comment::class)->orderBy('date', 'DESC');
        }
    }
}
