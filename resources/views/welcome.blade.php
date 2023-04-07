@extends('layouts.base')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto my-4">
                @if(!is_dir(storage_path('app/amocrm')))
                    <form method="GET" action="{{ route('auth') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Авторизоваться</button>
                    </form>
                @else
                    <a href="{{ route('info') }}"><button class="btn btn-primary">Аккаунт</button></a>
                @endif
            </div>
        </div>
    </div>
@endsection
