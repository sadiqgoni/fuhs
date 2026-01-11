<div>
    @push('styles')
        <style>
            sup{
                color: #ff4d00;
                font-weight: bolder;
                font-size: 18px;
            }
            .bg-gray-500{
                background: dimgray;
                border: none;
            }
            .bg-blue-500{
                background: darkblue;
                border: none;
            }
            /*strong{*/
            /*    position: absolute !important;left: 0;top:-19px;font-size: 12px !important;z-index:999;*/
            /*}*/
            /* Vertical Tabs */
            .vertical-tabs{font-size:14px;padding:10px 0 10px 0;color: #007580
            }
            .vertical-tabs .nav-tabs .nav-link{background: deepskyblue;border:1px solid transparent;color:#fff;height:37px}
            .vertical-tabs .nav-tabs .nav-link.active{background-color: #009994 !important;border-color:transparent !important;color:#fff;}
            .vertical-tabs .nav-tabs .nav-link{border:1px solid transparent;border-top-left-radius:0rem!important;}
            .vertical-tabs .tab-content>.active{background:#fff;display:block;}
            .vertical-tabs .nav.nav-tabs{border-bottom:0;border-right:1px solid transparent;display:block;float:left;margin-right:20px;padding-right:15px;}
            /*.vertical-tabs div.tab-content{border:solid 1px #4CAF50!important;max-height:200px;}*/
            .vertical-tabs .sv-tab-panel{background:#fff;padding-top:10px;}
            /*.vertical-tabs div#home-v.tab-pane .sv-tab-panel{background:#a6dba6}*/
            /*.vertical-tabs div#profile-v.tab-pane .sv-tab-panel{background:#99d699;}*/
            /*.vertical-tabs div#messages-v.tab-pane .sv-tab-panel{background:#8cd18c}*/
            /*.vertical-tabs div#settings-v.tab-pane .sv-tab-panel{background:#80cc80}*/

            /* Vertical Tabs */

            @media (max-width: 967px) {
                .nav.nav-tabs{border-bottom:0;border-right:1px solid transparent;display:block; margin-right:20px; wodth:100%!important}
                .horizontal-tabs .nav-tabs{width:100%;padding:0}
            }
            svg{
                display: none !important;
            ;
            }
        </style>
    @endpush
        @if($view != true || $record !=true)
        <div wire:loading  style="position: absolute;z-index: 9999;text-align: center;width: 100%;height: 50vh;padding: 25vh">
            <div style="background: rgba(14,13,13,0.13);margin: auto;max-width:100px;">
                <i class="fa fa-spin fa-spinner" style="font-size:100px"></i>
            </div>
        </div>
        @endif
    <div class="row">
        <div class="col-12">
            @include('livewire.forms.employee.record')
            @include('livewire.forms.employee.edit')
            @include('livewire.forms.employee.create')
            @include('livewire.forms.employee.view')
        </div>
    </div>

    @section('title')
             Employee Profile
    @endsection
    @section('page_title')
            EMPLOYEES / Employee Profile
    @endsection
</div>
