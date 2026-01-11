<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
        @include('layouts.styles')
</head>
<body style="overflow-x: hidden">
@include('layouts.partials.header')
@include('sweetalert::alert')
<div class="row" style="margin: 100px auto">
    <div class="col-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3">
        <div class="card">
            <div class="card-header">
                <h5>Verity its you</h5>
            </div>
            <div class="card-body">
                    <em class="text-dark">Enter the index of each word to its corresponding box</em>
                    <form action="{{route('two-factor')}}" method="post">
                        @csrf
                        <div class="row ">
                        @foreach($emp_pass as $pass)
                            <div class="col-4 col-md-3 col-lg-2 offset-lg-1 text-center">
                                <label for="" class="text-capitalize">{{$pass->passkey}}</label>
                                <input type="password" name="{{strtolower($pass->passkey)}}" class="form-control @error($pass->passkey) is-invalid @enderror">
                            </div>
                        @endforeach
                        <div class="col-12 mt-3 text-right" >
                            <button class="btn save_btn" type="submit">Verify</button>
                        </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.scripts')
@include('layouts.partials.footer')
</body>
</html>
