<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class PostController extends Controller
{
    private $posts_paginate = 5;//count of posts per one page

    //Returns main page with posts
    public function index()
    {
        $posts = Post::where('deleted', 0)->paginate($this->posts_paginate);

        return view('pages.index', [
            'posts' => $posts
        ]);
    }

    //Returns page with one post
    public function getOnePost($slug)
    {
        $post = $this->getPostBySlug($slug);

        return view('pages.post.post', [
            'post' => $post['post'],
            'comments' => $post['comments']
        ]);
    }

    /*
     * Delete post by slug.
     * I didn't delete the post directly here. I created deleted field, from which we will already understand whether the post is deleted or not.
     * Since completely delete data from the database, quite dangerous, and it is better to keep them.
    */
    public function deletePost($slug)
    {
        $post = Post::where('deleted', 0)->where('slug', $slug)->first();
        if ($post) {
            $post->update(['deleted' => 1]);
            $post->save();
        }

        return redirect((route('index')));
    }

    //Update or add post for forms
    public function addUpdatePostSubmit($slug, Request $request)
    {
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
        } elseif (stristr($date_publish, '-', true) < 1950 || stristr($date_publish, '-', true) > 2100) {
            $errorMessages[] = 'Wrong format of date! (Max year of date 2100, and min 1950)'; //I check by string, because all the other auxiliary classes are parsing the year and so I can't check the original
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

                $post->update(['slug' => 'post-' . $post['id']]);
            }
            $post->save();

            //If post was new, we redirecting client after success creation of post to page of this post
            if ($type == 'add')
                return redirect(route('getOnePost', $post['slug']));

        }

        //If the post was successfully edited I redirect client to this post
        if ($type == 'edit' && !empty($errorMessages))
            $data = Post::where('slug', $slug)->first();
        elseif ($type == 'edit')
            return redirect(route('getOnePost', $post['slug']));


        //If there were errors when adding a new post, I record the data - so that after restarting the data is not cleared
        if ($type == 'add') {
            $data['title'] = $title;
            $data['description'] = $description;
            $data['short_description'] = $short_description;
        }

        return view('pages.post.post-add-update', [
            'type' => $type,
            'data' => $data,
            'slug' => $slug,
            'error_messages' => $errorMessages
        ]);
    }

    //Return the page with update/add post
    public function addUpdatePost($slug)
    {
        $type = 'edit';
        $data = '';

        if ($slug == -1)
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
