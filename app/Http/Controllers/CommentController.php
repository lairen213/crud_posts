<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReaction;
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

        return redirect(route('getOnePost', [$post_slug, 'date']));
    }

    public function addReaction($comment_id, Request $request){
        $comment = Comment::find($comment_id);

        if($comment && $request->exists('type')){
            $reaction = new CommentReaction([
                'user_id' => Auth::id(),
                'comment_id' => $comment_id,
                'type' => $request->get('type')
            ]);
            $reaction->save();
        }else{
            return response()->json(['status' => 'error'], 404);
        }

        return response()->json(['status' => 'ok'], 200);
    }

    public function deleteReaction($comment_id, Request $request){
        $comment = Comment::find($comment_id);

        if($comment && $request->exists('type')){
            $reaction = CommentReaction::where('user_id', Auth::id())->where('type', $request->get('type'))->where('comment_id', $comment_id)->first();
            if($reaction)
                $reaction->delete();
            else
                return response()->json(['status' => 'error'], 404);
        }else{
            return response()->json(['status' => 'error'], 404);
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
