@auth("web")
    <a href="{{route('logout')}}" class="btn btn-outline-dark float-end mb-2" ><i class="bi bi-door-open"></i> Exit</a>
@endauth

{{--@guest("web")--}}
{{--    <a href="{{route('login')}}" class="float-end mb-2 btn btn-outline-success" ><i class="bi bi-door-open"></i> Login</a>--}}
{{--@endguest--}}
