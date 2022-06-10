@extends('layouts.main-layout')

@section('title', 'Login')

@section('content')
    <div class="offset-lg-3 col-lg-6 col-12">
        @if($errors->any())
            <div class="alert alert-warning" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
            </div>
        @endif

        <form action="{{route('loginProcess')}}" method="POST" class="shadow-sm border rounded p-3">
            @csrf

            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" class="form-control" name="email"/>
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-control" name="password"/>
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                <p class="float-end mt-3">Not a member? <a href="{{route('register')}}">Register</a></p>
            </div>
        </form>

    </div>
@endsection
