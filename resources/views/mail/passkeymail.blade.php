<x-mail::message>
Dear {{\Illuminate\Support\Facades\Auth::user()->name}}

Your Passkey are.

<ul>
    @foreach($data as $data)
<li style="list-style: none;font-size: 20px;" class="text-capitalize">{{$data->rand_int}} - {{$data->passkey}}</li>
    @endforeach
</ul>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
