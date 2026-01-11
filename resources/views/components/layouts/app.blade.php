<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>
    @include('layouts.styles')
    @stack('styles')
</head>
<body style="overflow-x: hidden">
{{--@include('spinner')--}}
@include('sweetalert::alert')
@include('layouts.partials.header')
@auth
    @include('layouts.partials.sidebar')

@endauth
@yield('help')
@include('layouts.scripts')
{{--<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}

{{----}}
{{--<script src="{{ asset('vendor/jantinnerezo/livewire-alert/livewire-alert.js') }}"></script>--}}
{{--<script src="{{ asset('vendor/livewire-alert/resources/livewire-alert.js') }}"></script>--}}
<script src="{{url('assets/js/swit-alert.js')}}"></script>
<x-livewire-alert::scripts />
<x-livewire-alert::flash />
<script src="{{url('assets/js/chart.js')}}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.7.0/d3.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.6.7/c3.min.js"></script>--}}
@stack('scripts')
@if(isset($chart))
    {!! $chart->script() !!}
@endif
{{--window.{{ $chart->id }}--}}

</body>
</html>
