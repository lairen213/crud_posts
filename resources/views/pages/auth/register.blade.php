@extends('layouts.main-layout')

@section('title', 'Registration')

@section('content')

    <div class="offset-lg-3 col-lg-6 col-12">
        @if($errors->any())
            <div class="alert alert-warning" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
            </div>
        @endif

        <form class="shadow-sm border rounded p-3" action="{{route("registerProcess")}}" method="POST">
            @csrf

            <!-- Name input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="name">Name</label>
                <input value="{{old('name')}}" type="text" id="name" class="form-control @error('name') border-danger @enderror" name="name"/>
            </div>

            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="email">Email address</label>
                <input value="{{old('email')}}" type="email" id="email" class="form-control @error('email') border-danger @enderror" name="email"/>
            </div>

            <!-- Passwords input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-control @error('password') border-danger @enderror" name="password"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="password_confirmation">Password confirm</label>
                <input type="password" id="password_confirmation" class="form-control @error('password') border-danger @enderror" name="password_confirmation"/>
            </div>

            <div>
                <button type="submit" class="btn btn-success btn-block">Sign up</button>
                <p class="float-end mt-3">Already have an account? <a href="{{route('login')}}">Login</a></p>
            </div>
        </form>
    </div>
@endsection
