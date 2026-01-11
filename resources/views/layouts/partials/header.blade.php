<style>
    .org_name{
        color:whitesmoke;
        text-shadow:  -1px 4px  #000;
        margin-top: 0px;
        font-size:23px;
        font-weight: bolder;
        padding-left: 5%
    }
    .org_logo{
        height: 42px
    }
    @media (max-width:920px) {
        .org_name{
            color:whitesmoke;
            text-shadow:  -1px 4px  #000;
            margin-top: 0px;
            font-size:18px;
            font-weight: bolder;
            /*padding-left: 10%*/
        }
        .org_logo{
            height: unset;
            width: 68px;
            margin: 0 auto;
        }
    }
</style>
<nav style="margin-bottom: 200px !important;background:  #3d91e3" class="navbar navbar-expand-lg  fixed-top">
{{--    navbar-light bg-light--}}
    <a class="navbar-brand" href="#" style="color:#000;padding:20px 47px;margin: -20px 0 -20px -15px;text-shadow: 1px 2px 1px #fff"><img src="{{url('assets/img/hct.jpeg')}}" alt="" style="height: 30px">HCT PAYROLL</a>
    <button class="navbar-toggler" style="background: darkblue" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-align-center text-white"></i></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto" style="width: 80%;padding-left: 4%">
{{--            <li class="nav-item active">--}}
{{--                <a class="nav-link" href="#"> <i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>--}}
{{--            </li>--}}


                <img src="{{asset('storage/'.app_settings()->logo)}}" alt="" style="border-radius: 50%" class="org_logo">
                <span style="" class="nav-item org_name">{{app_settings()->name}}</span>




        </ul>

        @auth()
            <div class="div float-right mr-3">
                <li class="nav-item dropdown" style="list-style: none">
                    <a class="nav-link dropdown-toggle" href="#" style="color:white" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i>Settings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @can('can_admin')
                            <a class="dropdown-item" href="{{route('user.account')}}"><i class="fa fa-user"></i> Profile</a>
                            <a class="dropdown-item" href="{{route('change.password')}}"><i class="fa fa-lock"></i> Change Password</a>

                            @can('app_setting')
                                <a class="dropdown-item" href="{{route('application.setting')}}"><i class="fa fa-cog"></i> App Settings</a>
                            @endcan
                            <a class="dropdown-item" href="{{route('logout')}}"><i class="fa fa-arrow-circle-left"></i> Logout</a>

                        @endcan
                        @cannot('can_admin')
{{--                                <a class="dropdown-item" href="{{route('staff.dashboard')}}"><i class="fa fa-dashboard-circle-left"></i> Dashboard</a>--}}
{{--                                <a class="dropdown-item" href="{{route('payroll.request')}}"><i class="fa fa-bar-chart"></i>Request Report</a>--}}
{{--                                <a class="dropdown-item" href="{{route('staff.profile')}}"><i class="fa fa-lock"></i> Profile Update</a>--}}
                                <a class="dropdown-item" href="{{route('staff.password')}}"><i class="fa fa-lock"></i> Change Password</a>
                                <a class="dropdown-item" href="{{route('staff.logout')}}"><i class="fa fa-arrow-circle-left"></i> Logout</a>

                            @endcannot

                            <div class="dropdown-divider"></div>

                    </div>
                </li>
            </div>

        @endauth
        <a class="nav-link text-white" href="{{route('help.view')}}"><i class="fa fa-question-circle"></i> Help</a>

    </div>
</nav>
