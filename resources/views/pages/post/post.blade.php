@extends('layouts.main-layout')

@section('title', 'Post')

@section('content')
    @include('includes.login_button')
    <a href="{{route('index')}}" class="btn btn-outline-dark">Back</a>
    <hr>

    <div class="row">
        <!-- Post -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$post['title']}}</h5>
                    <p class="card-text">{!! $post['description'] !!}</p>

                    <div class="float-start">
                        <span class="text-muted">{{$post['date_publish_beautified']}}</span>
                    </div>
                    <div class="float-end">
                        <form action="{{ route('deletePost', $post['slug']) }}" method="POST">
                            @csrf

                            <a href="{{ route('addUpdatePost', $post['slug']) }}" class="btn btn-outline-warning"><i
                                    class="bi bi-pencil"></i> Edit</a>
                            <button type="submit" class="btn btn-danger btnDeletePost"
                                    onclick="return confirm('Are you sure you want to delete the post? This action cannot be undone')">
                                <i class="bi bi-trash3"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts commentaries section -->
        <div class="col-12 mt-5 mb-3">
            <h5>Commentaries</h5>
            <form action="{{route('addComment', $post['slug'])}}" method="POST">
                @csrf

                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="commentTextArea"
                              style="height: 100px" name="comment"></textarea>
                    <label for="commentTextArea">Leave a comment here</label>
                </div>
                <div class="float-start col-6">
                    @if(isset($error_messages))
                        @foreach($error_messages as $error)
                            <span class="badge bg-warning">{{$error}}</span>
                        @endforeach
                    @endif
                </div>
                <button class="btn btn-outline-primary btn-sm float-end mt-1">Comment</button>
            </form>
        </div>

        <div class="col-12 mb-5">
            <div class="list-group mb-2">
                @foreach($comments as $comment)
                    <div
                        class="list-group-item list-group-item-action @if(Auth::id() == $comment->user->id) bg-light @endif"
                        aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$comment->user->name}}</h5>
                                <small>
                                    {{$comment->date_beautified}}
                                </small>
                            </div>

                            <p class="mb-1 float-start" style="white-space: pre-wrap;">{{$comment->comment}}</p>
                    </div>
                @endforeach
            </div>

            {{$comments->links('vendor.pagination.bootstrap-5')}}
        </div>
    </div>
@endsection
