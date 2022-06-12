<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function addComment($post_slug, Request $request)
    {
        $post = Post::where('deleted', 0)->where('slug', $post_slug)->first();
        if ($post) {
            $comment_text_area = strip_tags($request->get('comment'));//delete html and php from a string

            if ($comment_text_area) {
                $comment = new Comment([
                    'post_id' => $post['id'],
                    'user_id' => Auth::id(),
                    'comment' => $comment_text_area,
                    'date' => date('Y-m-d H:i:s')
                ]);
                $comment->save();
            }
        }

        return redirect(route('getOnePost', $post_slug));
    }
}
