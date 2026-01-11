<link rel="stylesheet" href="{{url('assets/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/fonts/font-awesome.min.css')}}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="{{url('assets/css/Dynamic-Table.css')}}">
<link rel="stylesheet" href="{{url('assets/css/Pretty-Header.css')}}">
<link rel="stylesheet" href="{{url('assets/css/Simple-Vertical-Navigation-Menu-v-10.css')}}">
<link rel="stylesheet" href="{{url('assets/css/styles.css')}}">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
<style>
    .list-group li{
        border: 1px solid skyblue;
        margin-bottom: 2px  !important;
    }
    .list-group li:hover{
       background: #000042;
        transition: ease-in-out .5s;
    }
    fieldset{border:3px solid cornflowerblue !important;padding: 5px}
    legend {
        background-color: #3d91e3;
        color: white;
        padding: 2px 10px;
    }
    thead{
        background: #e5ebef !important;
    }

    .table td, .table th {
        padding: 0 5px !important;

    }
    .table th {
        padding: 0 5px !important;

    }
    .record .form-group{
        margin-bottom: 5px !important;
    }
    .record .input-group-text{
        padding: 2px !important;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #f9fbfc;
        opacity: 1;
    }
    .record .form-control {
        display: block;
        width: 100%;
    padding: .100rem .75rem;
    }
  .record  select.form-control:not([size]):not([multiple]) {
        height: calc(1.8rem + 1px);
    }
    .create{
        background: dodgerblue !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .create:hover{
        background: #0c4a86 !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-out .5s;
    }
    .close_btn{
        background:darkred !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .close_btn:hover{
        background: #420000 !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .edit_btn{
        background: #77c25e !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .edit_btn:hover{
        background: #3d782b !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .save_btn{
        background: #03125f !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .save_btn:hover{
        background: #020c34 !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .generate{
        background: #035f5f !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .generate:hover{
        background: #052625 !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .view{
        background: #77c25e !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .view:hover{
        background: #274b1c !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .export{
        background: #1c1a1a !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .export:hover{
        background: #423e3e !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .reset_btn{
        background: #6c6a00 !important;
        width:10rem !important;
        padding: 6px;
        color:white
    }
    .reset_btn:hover{
        background: #363500 !important;
        width:10rem !important;
        padding: 6px;
        color:white;
        transition: ease-in-out .5s;
    }
    .form-group{
        margin-bottom: 0.5rem !important;
    ;
    }
    input[type=radio]{
        height: 25px;
        width: 25px;
        border: solid white;
        border-width: 0 3px 3px 0;
    }
    .normal-container {
        position: absolute;
        height: 100%;
        width: 100%;
    }
    .smile-rating-container {
        position: relative;
        height: 10%;
        min-width: 220px;
        max-width: 520px;
        margin: auto;
        font-family: 'Roboto', sans-serif;
        top: 20%;
    }
    .submit-rating {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .rating-label {
        position: relative;
        font-size: 1.6em;
        text-align: center;
        flex: 0.34;
        z-index: 3;
        font-weight: bold;
        cursor: pointer;
        color: #d0cccd;
        transition: 500ms;
    }
    .rating-label:hover, .rating-label:active {
        color: #d0cccd;
    }
    .rating-label-fun {
        left: -58px;
        text-align: right;
    }
    .rating-label-meh {
        left: 58px;
        text-align: left;
        color: #222;
    }
    .smile-rating-container input {
        display: none;
    }
    .toggle-rating-pill {
        position: relative;
        height: 65px;
        width: 165px;
        background: #d0cccd;
        border-radius: 500px;
        transition: all 500ms;
    }
    .smile-rating-toggle {
        position: absolute;
        width: 54px;
        height: 54px;
        background-color: white;
        left: 182px;
        border-radius: 500px;
        transition: all 500ms;
        z-index: 4;
    }
    .rating-eye {
        position: absolute;
        height: 12px;
        width: 8px;
        top: 22px;
        background: #d0cccd;
        border-radius: 500px;
        z-index: 5;
        transition: all 440ms;
        animation: blink-eye 3s infinite;
    }
    @keyframes blink-eye {
        0% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        20% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        40% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        60% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        80% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        90% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
        95% {
            height: 2px;
            width: 8px;
            top: 30px;
        }
        100% {
            height: 12px;
            width: 8px;
            top: 22px;
        }
    }
    .rating-eye-left {
        left: 192px;
    }
    .rating-eye-right {
        left: 216px;
    }
    .mouth {
        position: absolute;
        width: 28px;
        height: 20px;
        z-index: 6;
        border: 4px solid #d0cccd;
        border-radius: 10%;
        border-bottom-color: rgba(1, 1, 1, 0);
        border-right-color: rgba(1, 1, 1, 0);
        border-left-color: rgba(1, 1, 1, 0);
        top: 42px;
        left: 190px;
        transition: all 500ms;
    }
    /*
     Toggle Changes
     */
    #meh:checked ~ .rating-label-meh {
        color: #555e63;
    }
    #fun:checked ~ .rating-label-meh {
        color: #d0cccd;
    }
    #fun:checked ~ .mouth {
        border: 4px solid #00b9ee;
        border-bottom-color: rgba(1, 1, 1, 0);
        border-right-color: rgba(1, 1, 1, 0);
        border-left-color: rgba(1, 1, 1, 0);
        top: 23px;
        left: 291px;
        transform: rotateX(180deg);
        border-radius: 100%;
    }
    #fun:checked ~ .rating-label-fun {
        color: #555e63;
    }
    #fun:checked ~ .smile-rating-toggle {
        left: 282px;
    }
    #fun:checked ~ .rating-eye-left {
        left: 292px;
    }
    #fun:checked ~ .rating-eye-right {
        left: 316px;
    }
    #fun:checked ~ .toggle-rating-pill {
        background-color: #00b9ee;
    }
    #fun:checked ~ .rating-eye {
        background-color: #00b9ee;
    }
    @media only screen and (max-width: 524px) {
        .normal-container {
            position: absolute;
            height: 100%;
            width: 100%;
        }
        .smile-rating-container {
            position: relative;
            height: 10%;
            width: 490px;
            margin: auto;
            font-family: 'Roboto', sans-serif;
            top: 20%;
        }
        .submit-rating {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .rating-label {
            position: relative;
            font-size: 1.6em;
            text-align: center;
            flex: 0.34;
            z-index: 3;
            font-weight: bold;
            cursor: pointer;
            color: #d0cccd;
            transition: 500ms;
        }
        .rating-label:hover, .rating-label:active {
            color: #d0cccd;
        }
        .rating-label-fun {
            left: -58px;
            text-align: right;
        }
        .rating-label-meh {
            left: 50px;
            text-align: left;
            color: #222;
        }
        .smile-rating-container input {
            display: none;
        }
        .toggle-rating-pill {
            position: relative;
            height: 65px;
            width: 165px;
            background: #d0cccd;
            border-radius: 500px;
            transition: all 500ms;
        }
        .smile-rating-toggle {
            position: absolute;
            width: 54px;
            height: 54px;
            background-color: white;
            left: 168px;
            border-radius: 500px;
            transition: all 500ms;
            z-index: 4;
        }
        .rating-eye {
            position: absolute;
            height: 12px;
            width: 8px;
            background: #d0cccd;
            border-radius: 500px;
            top: 22px;
            z-index: 5;
            transition: all 440ms;
        }
        .rating-eye-left {
            left: 180px;
        }
        .rating-eye-right {
            left: 201px;
        }
        .mouth {
            position: absolute;
            width: 28px;
            height: 20px;
            z-index: 6;
            border: 4px solid #d0cccd;
            border-radius: 10%;
            border-bottom-color: rgba(1, 1, 1, 0);
            border-right-color: rgba(1, 1, 1, 0);
            border-left-color: rgba(1, 1, 1, 0);
            top: 42px;
            left: 177px;
            transition: all 500ms;
            /*
             Toggle Changes
             */
        }
        #meh:checked ~ .rating-label-meh {
            color: #555e63;
        }
        #fun:checked ~ .rating-label-meh {
            color: #d0cccd;
        }
        #fun:checked ~ .mouth {
            border: 4px solid #00b9ee;
            border-bottom-color: rgba(1, 1, 1, 0);
            border-right-color: rgba(1, 1, 1, 0);
            border-left-color: rgba(1, 1, 1, 0);
            top: 23px;
            left: 275px;
            transform: rotateX(180deg);
            border-radius: 100%;
        }
        #fun:checked ~ .rating-label-fun {
            color: #555e63;
        }
        #fun:checked ~ .smile-rating-toggle {
            left: 266px;
        }
        #fun:checked ~ .rating-eye-left {
            left: 275px;
        }
        #fun:checked ~ .rating-eye-right {
            left: 300px;
        }
        #fun:checked ~ .toggle-rating-pill {
            background-color: #00b9ee;
        }
        #fun:checked ~ .rating-eye {
            background-color: #00b9ee;
        }
    }
@media (max-width:920px) {
    .table{
        margin-top: 10px;
        font-size: 12px;
    }
    .create{
        background: dodgerblue !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .create:hover{
        background: #0c4a86 !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-out .5s;
    }
    .close_btn{
        background:darkred !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .close_btn:hover{
        background: #420000 !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .edit_btn{
        background: #77c25e !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .edit_btn:hover{
        background: #3d782b !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .save_btn{
        background: #03125f !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .save_btn:hover{
        background: #020c34 !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .generate{
        background: #035f5f !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .generate:hover{
        background: #052625 !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .view{
        background: #77c25e !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .view:hover{
        background: #274b1c !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .export{
        background: #1c1a1a !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .export:hover{
        background: #423e3e !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
    .reset_btn{
        background: #6c6a00 !important;
        width:100% !important;
        padding: 3px;
        color:white
    }
    .reset_btn:hover{
        background: #363500 !important;
        width:100% !important;
        padding: 3px;
        color:white;
        transition: ease-in-out .5s;
    }
}
</style>
