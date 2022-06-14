@extends('layouts.main-layout')

@section('title', 'Post '.$type)

@section('content')
    @include('includes.login_button')
    <a href="@if($type == 'add') {{route('index')}} @else {{route('getOnePost', [$slug, 'date'])}} @endif" class="btn btn-outline-dark">Back</a>
    <hr>
    @if(isset($error_messages))
        @foreach($error_messages as $error)
            <div class="alert alert-warning">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="row">
        <form action="{{ route('addUpdatePostSubmit', $slug) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input maxlength="250" type="text" class="form-control" id="title" name="title" @if(isset($data['title']) && $data['title'])value="{{$data['title']}}"@endif>
            </div>

            <div class="mb-3">
                <label for="short_description" class="form-label">Short description</label>
                <input maxlength="250" type="text" class="form-control" id="short_description" name="short_description" @if(isset($data['short_description']) && $data['short_description'])value="{{$data['short_description']}}"@endif>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description">@if(isset($data['description']) && $data['description']){{$data['description']}}@endif</textarea>
            </div>

            <div class="mb-3">
                <label for="date_publish" class="form-label">Date publish</label>
                <input min="1999-12-31" type="datetime-local" class="form-control" id="date_publish" name="date_publish" @if(isset($data['date_publish']) && $data['date_publish'])value="{{date('Y-m-d\TH:i', strtotime($data['date_publish']))}}"@endif>
            </div>

            <button type="submit" class="btn btn-outline-success">Сохранить</button>
        </form>
    </div>
@endsection
