@extends('layouts.wrapper')

@section('body')
    <body class="d-flex flex-column" data-spy="scroll" data-offset="70">
        @guest
            @if(config('settings.announcement_guest'))
                @include('shared.announcement', ['message' => config('settings.announcement_guest'), 'type' => config('settings.announcement_guest_type'), 'id' => config('settings.announcement_guest_id')])
            @endif
        @else
            @if(config('settings.announcement_user'))
                @include('shared.announcement', ['message' => config('settings.announcement_user'), 'type' => config('settings.announcement_user_type'), 'id' => config('settings.announcement_user_id')])
            @endif
        @endguest

        @include('shared.header')

        <div class="d-flex flex-column flex-fill @auth content @endauth">
            @yield('content')

            @include('shared.modals.confirmation')
            @include('shared.footer')
        </div>
    </body>
@endsection
