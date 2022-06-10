@extends('layouts.main-layout')

@section('title', 'Post')

@section('content')
    @include('includes.login_button')
    <a href="{{route('index')}}" class="btn btn-outline-dark">Back</a>
    <hr>


        <!-- POSTS -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$post['title']}}</h5>
                <p class="card-text">{!! $post['description'] !!}</p>

                <div class="float-start">
                    <span class="text-muted">{{$post['date_publish']}}</span>
                </div>
                <div class="float-end">
                    <form action="{{ route('deletePost', $post['slug']) }}" method="POST">
                        @csrf

                        <a href="{{ route('addUpdatePost', $post['slug']) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
                        <button type="submit" class="btn btn-danger btnDeletePost" onclick="return confirm('Are you sure you want to delete the post? This action cannot be undone')"><i class="bi bi-trash3"></i> Delete</button>
                    </form>
                </div>
            </div>

        </div>

@endsection
