<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //Method for beautify datetime
    public function beautifyDateTime($datetime)
    {
        return Carbon::parse($datetime)->format('F d, Y') . ' at ' . Carbon::parse($datetime)->format('H:i');
    }

    //Method that returns post with beautified date
    public function getPostBySlug($slug)
    {
        $post = Post::where('slug', $slug)->where('deleted', 0)->first();
        $post['date_publish_beautified'] = $this->beautifyDateTime($post['date_publish']);


        //Comments pagination
        $comments = $post->comments()->paginate(5);
        //Add beautified date to comments
        foreach ($comments as $comment) {
            $comment['date_beautified'] = Carbon::parse($comment['date'])->format('F d, H:i');
        }

        return ['post' => $post, 'comments' => $comments];
    }
}
