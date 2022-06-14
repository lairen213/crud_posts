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
            @if(!$comments->isEmpty())
                <div class="dropdown mb-1">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="">
                        <li><a class="dropdown-item" href="{{ route('getOnePost', [$post['slug'], 'rate']) }}">Sort by rate</a></li>
                        <li><a class="dropdown-item" href="{{ route('getOnePost', [$post['slug'], 'date']) }}">Sort by date</a></li>
                    </ul>
                </div>
            @endif
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


                        <p class="mb-1 float-start" style="white-space: pre-wrap; word-break: break-all; overflow: scroll; max-height: 250px;">{{$comment->comment}}</p>
                        <div class="float-end">
                            <input type="checkbox" class="btn-check btn-reaction-check" id="btn-like-{{$comment->id}}"
                                   autocomplete="off" name="btn-reaction-{{$comment->id}}" @if($comment->user_reaction && $comment->user_reaction == 'like') checked @endif>
                            <label class="btn-sm btn btn-outline-secondary" for="btn-like-{{$comment->id}}"><i
                                    class="bi bi-hand-thumbs-up"></i> <span
                                    id="span-like-{{$comment->id}}">{{$comment['count_likes']}}</span></label>

                            <input type="checkbox" class="btn-check btn-reaction-check"
                                   id="btn-dislike-{{$comment->id}}" autocomplete="off"
                                   name="btn-reaction-{{$comment->id}}" @if($comment->user_reaction && $comment->user_reaction == 'dislike') checked @endif>
                            <label class="btn-sm btn btn-outline-secondary" for="btn-dislike-{{$comment->id}}"><i
                                    class="bi bi-hand-thumbs-down"></i> <span
                                    id="span-dislike-{{$comment->id}}">{{$comment['count_dislikes']}}</span></label>
                        </div>
                    </div>
                @endforeach
            </div>

            {{$comments->links('vendor.pagination.bootstrap-5')}}
        </div>
    </div>

    <script>
        $('.btn-reaction-check').on('click', function () {
            if (this.id) {
                let comment_id = this.id.split('-')[2];
                let type = 'like';
                let opposite_type = 'dislike';

                if (this.id.includes('dislike')) {
                    opposite_type = type;
                    type = 'dislike';
                }

                //If the opposite button was checked, then uncheck
                if ($('#btn-' + opposite_type + '-' + comment_id)[0].checked) {
                    //delete reaction in db
                    $.ajax({
                        url: "/comments-delete-reaction/" + comment_id,
                        type: "POST",
                        data: {
                            _token: _token,
                            type: opposite_type
                        },
                        success: function (response) {
                            $('#span-' + opposite_type + '-' + comment_id).text(parseInt($('#span-' + opposite_type + '-' + comment_id).text()) - 1);
                            $('#btn-' + opposite_type + '-' + comment_id).prop("checked", false);
                        },
                        error: function (error) {
                            alert('Произошла ошибка... Попробуйте перезапустить страницу');
                            console.log(error);
                        }
                    })
                }

                //If we changed the check, then +1, if we just removed check, then -1
                let span = '#span-' + type + '-' + comment_id;
                if ($('#btn-' + type + '-' + comment_id)[0].checked) {

                    //add reaction to db
                    $.ajax({
                        url: "/comments-add-reaction/" + comment_id,
                        type: "POST",
                        data: {
                            _token: _token,
                            type: type
                        },
                        success: function (response) {
                            $(span).text(parseInt($(span).text()) + 1);
                        },
                        error: function (error) {
                            alert('Произошла ошибка... Попробуйте перезапустить страницу');
                            console.log(error);
                        }
                    })
                } else {
                    //delete reaction in db, if we just removed check from checked button
                    $.ajax({
                        url: "/comments-delete-reaction/" + comment_id,
                        type: "POST",
                        data: {
                            _token: _token,
                            type: type
                        },
                        success: function (response) {
                            $(span).text(parseInt($(span).text()) - 1);
                        },
                        error: function (error) {
                            alert('Произошла ошибка... Попробуйте перезапустить страницу');
                            console.log(error);
                        }
                    })
                }
            }
        })
    </script>
@endsection
