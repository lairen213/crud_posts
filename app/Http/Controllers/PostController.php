<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $posts_paginate = 5;//count of posts per one page

    //Returns main page with posts
    public function index(){
        $posts = Post::where('deleted', 0)->paginate($this->posts_paginate);

        return view('pages.index', [
            'posts' => $posts
        ]);
    }

    //Returns page with one post
    public function getOnePost($slug){
        $post = Post::where('slug', $slug)->where('deleted', 0)->first();

        $post['date_publish'] = $this->beautifyDateTime($post['date_publish']);
        return view('pages.post.post',[
            'post' => $post
        ]);
    }

    /*
     * Delete post by slug.
     * I didn't delete the post directly here. I created deleted field, from which we will already understand whether the post is deleted or not.
     * Since completely delete data from the database, quite dangerous, and it is better to keep them.
    */
    public function deletePost($slug){
        $post = Post::where('deleted', 0)->where('slug', $slug)->first();
        if($post) {
            $post->update(['deleted' => 1]);
            $post->save();
        }

        return redirect((route('index')));
    }

    //Update or add post for forms
    public function addUpdatePostSubmit($slug, Request $request){
        $title = $request->input('title');
        $short_description = $request->input('short_description');
        $description = $request->input('description');
        $date_publish = $request->input('date_publish');
        $errorMessages = [];
        $data = [];

        // if slug = -1, that's 'add', else that's edit of post
        $type = 'edit';
        if ($slug == -1)
            $type = 'add';

        if (!$title || !$short_description || !$description || !$date_publish) {
            $errorMessages[] = 'Not all fields were specified!';
        } else {
            $post = new Post();
            if ($type == 'edit') {
                $post = Post::where('slug', $slug)->first();
                $post->update([
                    'title' => $title,
                    'short_description' => $short_description,
                    'description' => $description,
                    'date_publish' => $date_publish
                ]);
                $data = $post;
            } else {
                $post = new Post([
                    'title' => $title,
                    'short_description' => $short_description,
                    'description' => $description,
                    'date_publish' => $date_publish
                ]);
                $post->save();

                $post->update(['slug' => 'post-'.$post['id']]);
            }
            $post->save();

            //If post was new, we redirecting client after success creation of post to page of this post
            if ($type == 'add')
                return redirect(route('getOnePost', $post['slug']));
        }

        //If the post was successfully edited I overwrite its new data in the data variable, which I then pass to the edit page
        if ($type == 'edit' && !empty($errorMessages))
            $data = Post::where('slug', $slug)->first();

        return view('pages.post.post-add-update', [
            'type' => $type,
            'data' => $data,
            'slug' => $slug,
            'error_messages' => $errorMessages
        ]);
    }

    //Return the page with update/add post
    public function addUpdatePost($slug){
        $type = 'edit';
        $data = '';

        if($slug == -1)
            $type = 'add';
        else
            $data = Post::where('deleted', 0)->where('slug', $slug)->first();

        return view('pages.post.post-add-update', [
            'type' => $type,
            'data' => $data,
            'slug' => $slug
        ]);
    }
}