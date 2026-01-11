<x-mail::message>
    @php
        $user=\App\Models\User::where('email',$data)->first();
    @endphp
 Dear {{$user->name}}
Use the below login credentials to login to your account<br><br>
Your Username: {{$user->username}} <br>
Your Password: 123456


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
