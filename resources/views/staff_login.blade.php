<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff | Login | Page</title>
    <style>
        .container {
            width: 100%;
            max-width: 400px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s ease-in-out;
            outline: none;
            color: #333;
        }

        input:focus {
            border-color: #555;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #2980b9;
        }



    </style>
    @include('layouts.styles')
</head>
<body style="overflow-x: hidden">
@include('layouts.partials.header')
<div class="row mb-5">
    @include('sweetalert::alert')
    <div class="col-lg-4 offset-lg-4">
        <div class="container">
            <div class="card" style="margin-top: 25vh">
                <h2>Staff Login</h2>
                <p class="text-danger" style="font-size: 12px"> <b>Note:</b>Use your Email Address as your username and your phone number as your password for the first time login</p>

                {{--            @dd(bcrypt(123456))--}}
                <form action="{{route('login')}}" method="post">
                    @csrf
                    <div class="form-group">
                        @error('username')
                        <strong class="text-danger">{{$message}}</strong>
                        @enderror
                        <input type="text" id="username" val class="form-control p-2 @error('username') is-invalid @enderror" name="username" value="{{old('username')}}" placeholder="Username" >
                    </div>

                    <div class="form-group">
                        @error('password')
                        <strong class="text-danger">{{$message}}</strong>
                        @enderror
                        <input type="password"  class="form-control py-2 @error('username') is-invalid @enderror" id="password" name="password" placeholder="Password" >
                    </div>
                    <button type="submit">Login</button>
                    <p><a href="{{route('password.request')}}">Forget Password</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@include('layouts.partials.footer')

</body>
</html>



