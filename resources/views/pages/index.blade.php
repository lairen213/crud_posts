@extends('layouts.main-layout')

@section('title', 'Posts')

@section('content')
    @include('includes.login_button')
    <a href="{{ route('addUpdatePost', -1) }}" class="btn btn-outline-success">Add post</a>
    <hr>

    <div class="row">

        <!-- POSTS -->
            @foreach($posts as $post)
                <div class="col-lg-6 col-md-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{$post['title']}}</h5>
                            <p class="card-text">{!! $post['short_description'] !!}</p>
                            <a class="btn btn-outline-primary" href="{{ route('getOnePost', [$post['slug'], 'date']) }}">Read more</a>
                        </div>
                    </div>
                </div>
            @endforeach

        {{$posts->links('vendor.pagination.bootstrap-4')}}
    </div>
@endsection
