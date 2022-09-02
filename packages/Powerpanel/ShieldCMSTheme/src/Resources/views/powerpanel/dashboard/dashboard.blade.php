@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" /> -->
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .dataTables_filter, .dataTables_info { display: none; }
    /*.badge-danger{background: #D33600!important;}*/
</style>
@endsection

@section('content')
<div class="row position-relative">
    <div class="col">
        <div class="h-100">

            <!-- Flash Message -->
            @if(Session::has('message'))
                <div class="row">
                    <div class="col-xl-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                @php
                if((isset($dashboardWidgetSettings->widget_leadstatistics) && $dashboardWidgetSettings->widget_leadstatistics->widget_display=="Y") || (isset($dashboardWidgetSettings->widget_liveusercountry) && $dashboardWidgetSettings->widget_liveusercountry->widget_display=="Y") || (isset($dashboardWidgetSettings->widget_formbuilderleads) && $dashboardWidgetSettings->widget_formbuilderleads->widget_display=="Y")) {
                    $divClass = 'col-xl-8';
                } else {
                    $divClass = 'col-xl-12';
                }
                @endphp
                <div class="{{ $divClass }}">
                    <div class="dashboard-img mb-4">
                        {{-- <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/dashboard.png' }}" alt="" title="" /> --}}
                        <div class="content">
                            <div class="smtitle">Wecome to PowerPanel</div>
                            <h2 class="title">The best place to grab complete insight about your documents, leads, and in-approval data</h2>
                        </div>
                        <div class="svg-img">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 165" style="enable-background:new 0 0 256 165;" xml:space="preserve">
                                <style type="text/css">
                                    .st0{fill:#E4ECF9;}
                                    .st1{fill:none;stroke:#96D8E7;stroke-linecap:round;stroke-miterlimit:10;}
                                    .st2{fill:none;stroke:#96D8E7;stroke-linecap:round;stroke-miterlimit:10;stroke-dasharray:3.9598,3.9598;}
                                    .st3{fill:#96D8E7;}
                                    .st4{fill:#64C6C2;}
                                    .st5{fill:#45968E;}
                                    .st6{fill:#8EC64E;}
                                    .st7{opacity:0.15;}
                                    .st8{fill:#FFFFFF;}
                                    .st9{fill:#466BB3;}
                                    .st10{fill:#5981C1;}
                                    .st11{fill:#B3CEF6;}
                                    .st12{opacity:0.2;fill:#3A7EC1;enable-background:new    ;}
                                    .st13{fill:#F99F1E;}
                                    .st14{fill:#D89444;}
                                    .st15{fill:#FFC81B;}
                                    .st16{fill:#DF4F43;}
                                    .st17{opacity:0.25;fill:#3A7EC1;enable-background:new    ;}
                                    .st18{fill:#EDEDED;}
                                    .st19{fill:#CECECE;}
                                    .st20{fill:#FEB137;}
                                    .st21{fill:#F8CA42;}
                                    .st22{fill:#01CABF;}
                                </style>
                                <g>
                                    <g>
                                        <g>
                                            <g>
                                                <polygon class="st0" points="162.8,135.7 161.9,135 184.7,108.2 195.3,121.7 214.8,97.3 223.9,108.3 241.6,89.8 242.6,90.7 
                                                    223.9,110.1 214.8,99.3 195.3,123.8 184.6,110.2              "/>
                                            </g>
                                        </g>
                                        <g>
                                            <polygon class="st0" points="238.2,88.9 246.1,86.3 243.4,94.2           "/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path class="st1" d="M79.6,114.9c-0.6-0.2-1.3-0.4-1.9-0.7"/>
                                            <path class="st2" d="M74.1,112.9C25.9,93.6,11.7,47,47.7,19.6C84.9-8.8,145.6,27.3,166.4,33c16.2,4.4,39.9,5.5,49.2,5.7"/>
                                            <path class="st1" d="M217.6,38.7c1.3,0,2,0,2,0"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path class="st3" d="M231.1,46.2L256,1l-52.3,16.3l-0.1,0.1L231.1,46.2L231.1,46.2z"/>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path class="st4" d="M253.2,3.7l-32.7,31.4l0,0l-5,6.9c0,0-0.1,0.1-0.1-0.1l-2-14.3c0,0,0,0,2-1.2L253.2,3.7z"/>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path class="st5" d="M215.5,41.9l8-3.7l0.1-0.1l-3.1-3.2l0,0L215.5,41.9L215.5,41.9z"/>
                                            </g>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path class="st6" d="M54.8,34.9c0,7-1.2,33.7-19.5,47c-0.3,0.3-1,0.4-1.5,0.3C12.1,75.6,2.4,50.9,0.1,44c-0.3-0.8,0.2-1.8,1.2-2
                                                    c13.8-3.3,20.3-12.7,22.6-16.7c0.5-0.9,1.7-1,2.4-0.4c3.4,3.1,12.7,9.8,26.7,8.4C53.9,33.3,54.7,33.9,54.8,34.9z"/>
                                            </g>
                                        </g>
                                        <g>
                                            <g class="st7">
                                                <g>
                                                    <g>
                                                        <ellipse cx="30.1" cy="54.2" rx="12.3" ry="12.3"/>
                                                    </g>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <ellipse class="st8" cx="28.7" cy="53.3" rx="12.3" ry="12.3"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <g>
                                                        <path class="st9" d="M27.7,59.9L27.7,59.9c-0.2,0-0.7,0.1-1-0.1l-3.1-1.4c-0.5-0.2-0.9-1.1-0.5-1.6c0.2-0.5,1.1-0.9,1.6-0.5
                                                            l2.1,0.9l4.7-10.8c0.2-0.5,1.1-0.9,1.6-0.5c0.5,0.2,0.9,1.1,0.5,1.6l-5.2,11.9C28.4,59.6,27.9,59.7,27.7,59.9z"/>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g>
                                        <path class="st9" d="M197.6,161.3c-0.1,0-0.2,0-0.3,0l-67-7.5c-0.8-0.1-1.3-0.5-1.8-1.1c-0.5-0.6-0.7-1.2-0.6-2l9.8-87.8
                                            c0.1-0.8,0.5-1.3,1.1-1.8c0.6-0.5,1.2-0.7,2-0.6l67,7.5c0.8,0.1,1.3,0.5,1.8,1.1c0.5,0.6,0.7,1.2,0.6,2l-9.9,87.9
                                            c-0.1,0.8-0.5,1.3-1.1,1.8C198.8,161.1,198.2,161.3,197.6,161.3z M140.5,61.3c-0.5,0-1,0.2-1.2,0.5c-0.5,0.4-0.7,0.9-0.8,1.3
                                            l-9.9,87.9c-0.1,0.6,0.1,1.1,0.5,1.5c0.4,0.5,0.9,0.7,1.4,0.8l67,7.5c0.6,0.1,1.1-0.1,1.5-0.5c0.4-0.4,0.7-0.9,0.8-1.3l9.7-87.9
                                            c0.1-0.6-0.1-1.1-0.5-1.5c-0.4-0.4-0.9-0.7-1.4-0.8L140.5,61.3L140.5,61.3z"/>
                                        <g>
                                            <path class="st10" d="M189.1,163.9l-64.9-18.2c-1.2-0.4-2-1.7-1.6-3l24-85.1c0.4-1.2,1.7-2,3-1.6l64.9,18.2c1.2,0.4,2,1.7,1.6,3
                                                l-24,85.1C191.7,163.5,190.4,164.3,189.1,163.9z"/>
                                            <path class="st11" d="M193.2,85.6l-31.6-8.8c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l31.6,8.8c1,0.3,1.5,1.2,1.2,2.2
                                                l0,0C195.1,85.3,194.1,85.8,193.2,85.6z"/>
                                            <path class="st9" d="M199.5,98l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C201.4,97.7,200.5,98.2,199.5,98z"/>
                                            <path class="st9" d="M197.3,105.7l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C199.2,105.4,198.3,106,197.3,105.7z"/>
                                            <path class="st9" d="M195.1,113.5l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C197,113.2,196,113.8,195.1,113.5z"/>
                                            <path class="st9" d="M192.9,121.3l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C194.7,121,193.8,121.6,192.9,121.3z"/>
                                            <path class="st9" d="M190.7,129.2l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C192.6,128.9,191.7,129.4,190.7,129.2z"/>
                                            <path class="st9" d="M188.5,136.9l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C190.4,136.6,189.4,137.2,188.5,136.9z"/>
                                            <path class="st11" d="M186.3,144.7l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2
                                                l0,0C188.2,144.4,187.2,145,186.3,144.7z"/>
                                            <path class="st9" d="M184.2,152.5l-49.6-14c-1-0.3-1.5-1.2-1.2-2.2l0,0c0.3-1,1.2-1.5,2.2-1.2l49.6,14c1,0.3,1.5,1.2,1.2,2.2l0,0
                                                C186,152.2,184.9,152.8,184.2,152.5z"/>
                                        </g>
                                        <path class="st8" d="M97.3,143.7L97.3,143.7c-0.1,0-0.2,0-0.3,0l0,0l0,0c-0.1,0-0.2,0-0.2,0l0,0c0,0,0,0-0.1,0
                                            c-0.1,0-0.2,0-0.3-0.1c0,0,0,0-0.1,0l0,0c-0.1,0-0.1,0-0.2-0.1l0,0H96c-0.1,0-0.1-0.1-0.2-0.1c0,0-0.1,0-0.1-0.1
                                            c-0.1,0-0.1-0.1-0.2-0.1c0,0-0.1,0-0.1-0.1l0,0l-0.1-0.1l-0.1-0.1l-0.1-0.1l0,0c0,0,0,0-0.1-0.1c-0.1-0.1-0.1-0.1-0.2-0.2l0,0l0,0
                                            c-0.1-0.1-0.1-0.1-0.2-0.2c0,0,0,0-0.1-0.1l0,0c0-0.1-0.1-0.1-0.1-0.2l0,0c0,0,0,0,0-0.1c-0.1-0.1-0.1-0.2-0.1-0.3l-0.4-0.4
                                            L59.2,61.6c0-0.1-0.1-0.2-0.1-0.3c0,0,0,0,0-0.1l0,0c0-0.1,0-0.1-0.1-0.2v0.1l0,0c0-0.1,0-0.1,0-0.2v-0.1c0-0.1,0-0.1,0-0.2v-0.1
                                            c0-0.1,0-0.1,0-0.2l0,0v-0.1c0-0.1,0-0.1,0-0.2l0,0v-0.1c0-0.1,0-0.1,0-0.2l0,0c0-0.2,0.1-0.3,0.1-0.5l0,0l0,0
                                            c0-0.1,0.1-0.2,0.1-0.3c0,0,0,0,0-0.1l0,0c0-0.1,0.1-0.1,0.1-0.2c0,0,0-0.1,0.1-0.1l0,0c0-0.1,0.1-0.1,0.1-0.2l0.1-0.1
                                            c0-0.1,0.1-0.1,0.2-0.2l0,0l0.1-0.1c0.1-0.1,0.1-0.1,0.2-0.1l0,0c0,0,0.1,0,0.1-0.1c0.1-0.1,0.1-0.1,0.2-0.1l0,0c0,0,0,0,0.1,0
                                            c0.1-0.1,0.2-0.1,0.3-0.2l62.9-27.2c0.1,0,0.2-0.1,0.4-0.1h0.1l0,0c0.1,0,0.2,0,0.2-0.1h0.1l0,0c0.1,0,0.1,0,0.2,0h0.1l0,0
                                            c0.1,0,0.2,0,0.2,0h0.1c0.1,0,0.1,0,0.2,0l0,0h0.1c0.1,0,0.1,0,0.2,0l0,0h0.1c0.1,0,0.2,0,0.3,0.1l0,0c0,0,0,0,0.1,0
                                            c0.1,0.1,0.3,0.1,0.4,0.2l0,0c0,0,0.1,0,0.1,0.1c0,0,0,0,0.1,0l0,0c0.1,0,0.1,0.1,0.2,0.1c0,0,0.1,0,0.1,0.1l0,0
                                            c0.1,0,0.1,0.1,0.2,0.1l0.1,0.1l0.1,0.1l0.1,0.1c0,0,0.1,0.1,0.1,0.2l0,0c0,0,0,0,0.1,0.1c0,0.1,0.1,0.1,0.1,0.2l0,0
                                            c0,0,0,0,0,0.1c0.1,0.1,0.1,0.2,0.1,0.3l34.9,80.2c0,0.1,0.1,0.2,0.1,0.3c0,0,0,0,0,0.1l0,0c0,0.1,0,0.1,0.1,0.2l0,0v0.1
                                            c0,0.1,0,0.2,0,0.3l0,0l0,0c0,0.1,0,0.2,0,0.3v0.1l0,0c0,0.1,0,0.1,0,0.2v0.1c0,0.1,0,0.1,0,0.2c0,0.2-0.1,0.3-0.1,0.5
                                            c0,0,0,0,0,0.1l0,0c0,0.1-0.1,0.2-0.1,0.3v0.1l0,0c0,0.1-0.1,0.2-0.1,0.3l0,0l0,0c-0.1,0.1-0.1,0.2-0.2,0.3l0,0
                                            c-0.1,0.1-0.1,0.2-0.2,0.3c0,0,0,0-0.1,0.1l0,0c-0.1,0.1-0.1,0.1-0.2,0.2l-0.1,0.1c-0.1,0.1-0.1,0.1-0.2,0.2l0,0h-0.1
                                            c-0.1,0.1-0.2,0.1-0.4,0.2l-62.8,27.2c-0.1,0-0.2,0.1-0.3,0.1l0,0c0,0,0,0-0.1,0c-0.1,0-0.2,0.1-0.3,0.1l0,0l0,0
                                            C97.6,143.6,97.5,143.6,97.3,143.7L97.3,143.7L97.3,143.7C97.6,143.7,97.5,143.7,97.3,143.7L97.3,143.7z M125,30.6L125,30.6
                                            L125,30.6h-0.2c-0.1,0-0.1,0-0.2,0l0,0c0,0,0,0-0.1,0c-0.1,0-0.1,0-0.2,0l0,0l0,0c-0.1,0-0.2,0.1-0.3,0.1L61.2,57.9
                                            c-0.1,0-0.2,0.1-0.3,0.1l0,0l0,0c-0.1,0-0.1,0.1-0.2,0.1c0,0,0,0-0.1,0l0,0l-0.1,0.1c0,0,0,0-0.1,0.1l0,0l-0.1,0.1
                                            c0,0,0,0.1-0.1,0.1l-0.1,0.1l0,0c0,0,0,0,0,0.1c0,0-0.1,0.1-0.1,0.2l0,0l0,0c0,0.1-0.1,0.1-0.1,0.2l0,0l0,0c0,0.1-0.1,0.2-0.1,0.4
                                            l0,0v0.1c0,0,0,0,0,0.1l0,0c0,0,0,0.1,0,0.2c0,0,0,0,0,0.1l0,0c0,0.1,0,0.1,0,0.2v-0.1c0,0.1,0,0.1,0,0.2v0.1c0,0,0,0.1,0,0.2l0,0
                                            c0,0,0,0,0,0.1c0,0.1,0,0.1,0,0.2l0,0l0,0c0,0.1,0.1,0.2,0.1,0.2l34.9,80.2c0,0.1,0.1,0.1,0.1,0.2l0,0l0,0c0,0.1,0.1,0.1,0.1,0.2
                                            l0,0c0,0,0,0,0,0.1s0.1,0.1,0.1,0.2l0,0l0,0c0,0.1,0.1,0.1,0.2,0.2l0,0l0,0l0.1,0.1c0,0,0.1,0,0.1,0.1c0,0,0.1,0,0.1,0.1l0,0
                                            c0,0,0,0,0.1,0c0,0,0.1,0.1,0.2,0.1H96c0.1,0,0.1,0.1,0.2,0.1c0,0,0,0,0.1,0c0.1,0,0.1,0,0.2,0.1l0,0c0,0,0,0,0.1,0
                                            c0.1,0,0.1,0,0.2,0l0,0l0,0c0.1,0,0.1,0,0.2,0l0,0l0,0c0.1,0,0.1,0,0.2,0c0,0,0,0,0.1,0c0.1,0,0.2,0,0.2,0l0,0c0.1,0,0.1,0,0.2,0
                                            l0,0c0.1,0,0.2,0,0.2-0.1l0,0l0,0c0.1,0,0.2-0.1,0.2-0.1l62.8-27.2c0.1,0,0.2-0.1,0.3-0.1l0,0l0,0c0.1,0,0.1-0.1,0.2-0.1
                                            c0,0,0.1,0,0.1-0.1c0.1,0,0.1-0.1,0.2-0.1l0,0l0,0c0.1-0.1,0.1-0.1,0.2-0.2l0,0c0.1-0.1,0.1-0.2,0.2-0.2l0,0l0,0
                                            c0-0.1,0.1-0.1,0.1-0.2l0,0c0,0,0,0,0-0.1c0-0.1,0.1-0.1,0.1-0.2l0,0l0,0c0-0.1,0.1-0.3,0.1-0.4v-0.1v-0.1v-0.1l0,0c0,0,0,0,0-0.1
                                            c0-0.1,0-0.2,0-0.2l0,0l0,0c0-0.1,0-0.1,0-0.2c0,0,0,0,0-0.1l0,0c0-0.1,0-0.1,0-0.2l0,0l0,0c0-0.1-0.1-0.2-0.1-0.2l-34.8-80.3
                                            c0-0.1-0.1-0.2-0.1-0.2l0,0l0,0c0-0.1-0.1-0.1-0.1-0.1s0,0,0-0.1l0,0c0-0.1-0.1-0.1-0.1-0.1s0,0-0.1-0.1l-0.1-0.1c0,0,0,0-0.1-0.1
                                            l-0.1-0.1l0,0c0,0,0,0-0.1,0l-0.1-0.1l0,0l0,0c0,0-0.1,0-0.1-0.1l0,0c-0.1-0.1-0.2-0.1-0.4-0.2l0,0l0,0c-0.1,0-0.1,0-0.2-0.1
                                            c0,0,0,0-0.1,0l0,0c-0.1,0-0.1,0-0.2,0h-0.1l0,0c-0.1,0-0.1,0-0.2,0C125.1,30.6,125.1,30.6,125,30.6C125.1,30.6,125,30.6,125,30.6
                                            z"/>
                                        <path class="st12" d="M157.3,124.7C157.3,124.6,157.3,124.6,157.3,124.7c0.1-0.2,0.1-0.2,0.2-0.3c0,0,0,0,0-0.1
                                            c0.1-0.1,0.1-0.3,0.2-0.4v-0.1v-0.1c0-0.1,0-0.1,0-0.2v-0.1c0-0.1,0-0.2,0-0.3l0,0c0-0.1,0-0.2,0-0.2v-0.1c0-0.1,0-0.1,0-0.2v-0.1
                                            c0-0.1,0-0.2-0.1-0.3l-0.1-0.5l0,0l-21.5-83.1l0,0l-0.3-1.1c0-0.1-0.1-0.2-0.1-0.3v0.1c0-0.1-0.1-0.1-0.1-0.2V37
                                            c0-0.1-0.1-0.1-0.1-0.2c0,0,0-0.1-0.1-0.1c0-0.1-0.1-0.1-0.1-0.1s0-0.1-0.1-0.1l-0.1-0.1c0,0,0-0.1-0.1-0.1l-0.1-0.1
                                            c0,0-0.1,0-0.1-0.1l-0.1-0.1c-0.1-0.1-0.2-0.2-0.4-0.2c0,0,0,0-0.1,0c-0.1,0-0.1-0.1-0.2-0.1h-0.1c-0.1,0-0.1-0.1-0.2-0.1h-0.1
                                            c-0.1,0-0.1,0-0.2,0h-0.1c-0.1,0-0.1,0-0.2,0H133c-0.1,0-0.1,0-0.2,0h-0.1c-0.1,0-0.1,0-0.2,0h-0.1c-0.1,0-0.2,0-0.3,0.1L66,52.8
                                            c-0.1,0-0.2,0.1-0.3,0.1h-0.1c-0.1,0-0.1,0.1-0.2,0.1c0,0-0.1,0-0.1,0.1c-0.1,0-0.1,0.1-0.2,0.1c0,0-0.1,0-0.1,0.1
                                            c-0.1,0-0.1,0.1-0.2,0.1c0,0-0.1,0-0.1,0.1c-0.1,0-0.1,0.1-0.2,0.1l-0.1,0.1c-0.1,0.1-0.1,0.1-0.1,0.2c0,0,0,0-0.1,0.1
                                            c-0.1,0.1-0.1,0.1-0.1,0.2c0,0,0,0,0,0.1c-0.1,0.1-0.1,0.3-0.2,0.4c0,0.1,0,0.1-0.1,0.2V55c0,0.1,0,0.1-0.1,0.2v0.1
                                            c0,0.1,0,0.1,0,0.2v0.1c0,0.1,0,0.1,0,0.2v0.1c0,0.1,0,0.1,0,0.2v0c0,0.1,0,0.1,0,0.2v0.1c0,0.1,0,0.2,0.1,0.3l0.3,1.1l0,0
                                            l21.6,83l0,0l0.1,0.5c0,0.1,0.1,0.2,0.1,0.3v0.1c0,0.1,0.1,0.1,0.1,0.2v0.1c0,0.1,0.1,0.1,0.1,0.2l0,0c0,0.1,0.1,0.1,0.2,0.2
                                            c0,0,0,0,0.1,0.1l0.1,0.1l0.1,0.1l0.1,0.1l0.1,0.1c0.1,0,0.1,0.1,0.2,0.1c0,0,0.1,0,0.1,0.1c0.1,0,0.1,0.1,0.2,0.1h0.1
                                            c0.1,0,0.1,0.1,0.2,0.1h0.1c0.1,0,0.1,0.1,0.2,0.1c0,0,0,0,0.1,0c0.1,0,0.1,0,0.2,0.1l0,0c0.1,0,0.2,0,0.2,0s0,0,0.1,0
                                            s0.2,0,0.3,0l0,0c0.1,0,0.2,0,0.2,0s0,0,0.1,0s0.2,0,0.3,0c0,0,0,0,0.1,0s0.2,0,0.3-0.1l66.2-17.1c0.1,0,0.2-0.1,0.3-0.1h0.1
                                            c0.1,0,0.1-0.1,0.2-0.1c0,0,0.1,0,0.1-0.1c0.1,0,0.1-0.1,0.2-0.1c0,0,0.1,0,0.1-0.1c0.1-0.1,0.2-0.1,0.2-0.2l0,0
                                            c0.1-0.1,0.2-0.2,0.2-0.2s0,0,0-0.1C157.2,124.8,157.2,124.7,157.3,124.7z"/>
                                        <g>
                                            <path class="st13" d="M136.2,35.2l22,84.6c0.2,0.6,0.1,1.2-0.1,1.7c-0.3,0.9-1.1,1.6-2,1.8l-66.2,17.1c-1,0.3-2,0-2.7-0.7
                                                c-0.5-0.4-0.8-0.9-1-1.5l-22-84.6c-0.2-0.7-0.1-1.4,0.2-2c0.4-0.8,1.1-1.3,1.9-1.6L132.5,33c0.9-0.2,1.8,0,2.5,0.5
                                                C135.6,33.9,136,34.5,136.2,35.2z"/>
                                            <path class="st14" d="M136.2,35.2l0.3,1.1l-3.3,10.2l-30.3,8.1l-30.5,7.5l-7.8-7.3l-0.3-1.1c-0.4-1.5,0.6-3.2,2.1-3.6l66.2-17
                                                C134.2,32.8,135.8,33.6,136.2,35.2z"/>
                                            <path class="st14" d="M158,119.5l0.1,0.5c0.4,1.5-0.6,3.2-2.1,3.6l-66.2,17.1c-1.6,0.4-3.2-0.6-3.6-2.1l-0.1-0.5l2.3-6.6
                                                c0.2-0.6,0.7-1.1,1.2-1.2l61.5-15.8c0.6-0.2,1.2,0,1.6,0.4L158,119.5z"/>
                                            <path class="st15" d="M158,121.6c-0.3,0.9-1.1,1.6-2,1.8l-66.2,17.1c-1,0.3-2,0-2.7-0.7l1.4-8.5c0.1-0.6,0.6-1.1,1.2-1.2
                                                l61.5-15.8c0.6-0.2,1.2,0.1,1.5,0.5L158,121.6z"/>
                                            <path class="st15" d="M135,33.6l-1.8,12.7c-0.1,0.4-0.3,0.7-0.7,0.8L73.1,62.4c-0.4,0.1-0.8,0-1-0.3l-7.7-10.2
                                                c0.4-0.8,1.1-1.3,1.9-1.6l66.2-17.1C133.5,33,134.4,33.2,135,33.6z"/>
                                            <path class="st9" d="M106.4,74.1c-1.2,0-2.4-0.9-2.7-2.1l-5.9-22.6c-0.2-0.8-0.1-1.4,0.3-2.1c0.4-0.7,1-1.2,1.7-1.2l1.3-0.4
                                                c1.5-0.4,3.1,0.5,3.5,2l5.9,22.6c0.2,0.8,0.1,1.4-0.3,2.1c-0.4,0.7-1,1.2-1.7,1.2l0,0l-1.3,0.4C106.9,74.1,106.7,74.1,106.4,74.1
                                                z M102,46.5c-0.2,0-0.3,0-0.5,0.1l-1.3,0.4c-0.5,0.1-1,0.5-1.2,1c-0.3,0.5-0.4,1-0.2,1.5l5.9,22.6c0.3,1.1,1.3,1.7,2.5,1.4
                                                l1.2-0.5c0.5-0.1,1-0.5,1.2-1c0.3-0.5,0.4-1,0.2-1.5l-5.9-22.6C103.6,47.1,102.8,46.5,102,46.5z"/>
                                            <ellipse class="st16" cx="101.4" cy="49.3" rx="3.9" ry="3.9"/>
                                            <ellipse class="st16" cx="107.1" cy="71.3" rx="3.9" ry="3.9"/>
                                        </g>
                                        <path class="st17" d="M184.7,58.9l-54.4-5.6c-2.1-0.2-4.1,1.3-4.3,3.5l-8.9,85c-0.2,2.1,1.3,4,3.5,4.3l66.2,6.8
                                            c2.1,0.2,4.1-1.3,4.3-3.5l7.7-73.3L184.7,58.9z"/>
                                        <g>
                                            <path class="st8" d="M182.7,57.3l-54.4-5.7c-2.1-0.2-4.1,1.3-4.3,3.5l-8.9,85c-0.2,2.1,1.3,4,3.5,4.3l66.2,6.8
                                                c2.1,0.2,4.1-1.3,4.3-3.5l7.7-73.3L182.7,57.3z"/>
                                            <path class="st18" d="M181.5,69c-0.2,2.1,1.3,4,3.5,4.3l11.8,1.2l-13.9-17.2L181.5,69z"/>
                                            <polygon class="st19" points="184.3,73 196.4,76.4 196.6,74.4            "/>
                                            <path class="st10" d="M176,75.5l-33.2-3.5c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l33.2,3.5c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C177.7,74.9,176.9,75.6,176,75.5z"/>
                                            <path class="st0" d="M185.7,87.7l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C187.4,87.1,186.6,87.8,185.7,87.7z"/>
                                            <path class="st0" d="M184.8,95.9l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C186.6,95.4,185.7,96,184.8,95.9z"/>
                                            <path class="st0" d="M184.1,104.2l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C185.7,103.6,184.8,104.3,184.1,104.2z"/>
                                            <path class="st0" d="M183.2,112.4l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C184.8,111.9,184.1,112.5,183.2,112.4z"/>
                                            <path class="st0" d="M182.2,120.7l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C184,120.3,183.2,120.8,182.2,120.7z"/>
                                            <path class="st0" d="M181.4,129.2l-55-5.7c-0.9-0.1-1.5-0.9-1.4-1.8l0,0c0.1-0.9,1-1.5,1.8-1.4l55,5.7c0.9,0.1,1.5,0.9,1.4,1.8
                                                l0,0C183.1,128.6,182.3,129.2,181.4,129.2z"/>
                                            <path class="st0" d="M174.4,145.2c-3.3-0.5-5.4-3.6-4.9-6.7c0.5-3.3,3.6-5.4,6.7-4.9c3.2,0.5,5.4,3.6,4.9,6.7
                                                C180.6,143.5,177.5,145.7,174.4,145.2z M175.9,134.7c-2.6-0.4-4.9,1.3-5.3,3.8c-0.4,2.6,1.3,4.9,3.9,5.3c2.6,0.4,4.9-1.3,5.3-3.8
                                                C180.2,137.5,178.5,135.1,175.9,134.7z"/>
                                            <path class="st9" d="M184,135.2L184,135.2c-0.6,0.1-1.1,0.2-1.4,0.4l-0.7,0.2l-0.7,0.3c-0.9,0.4-1.6,0.8-2.5,1.2
                                                c-0.8,0.4-1.5,1-2.3,1.4c-0.4,0.3-0.8,0.6-1.2,1c-0.2-0.2-0.5-0.4-0.7-0.6c-0.3-0.3-0.7-0.5-1.1-0.8c-0.4-0.3-0.8-0.5-1.2-0.7
                                                l0,0h-0.1c0,0-0.1,0.1,0,0.1c1,1.3,1.8,2.7,2.8,4c0.1,0.1,0.2,0.1,0.2,0c1.4-1.1,3-2.1,4.4-3.2l2.2-1.6c0.8-0.5,1.4-1.1,2.1-1.6
                                                v-0.1C184,135.2,184,135.1,184,135.2z"/>
                                        </g>
                                        <ellipse class="st10" cx="175.7" cy="14.6" rx="3.4" ry="3.4"/>
                                    </g>
                                    <polygon class="st20" points="81.9,20.7 85.7,19.5 81.9,18.4 80.8,14.5 79.6,18.4 75.8,19.5 79.6,20.7 80.8,24.4   "/>
                                    <polygon class="st21" points="64.5,124.4 67.5,123.4 64.5,122.5 63.6,119.5 62.6,122.5 59.6,123.4 62.6,124.4 63.6,127.4   "/>
                                    <polygon class="st22" points="111.5,156.6 116.1,155.3 111.5,154 110.2,149.3 108.9,154 104.2,155.3 108.9,156.6 110.2,161.2   "/>
                                    <polygon class="st22" points="161.8,44.9 164.9,44 161.8,43.1 160.9,40 160,43.1 157,44 160,44.9 160.9,48     "/>
                                    <polygon class="st20" points="212.1,155.1 215.8,153.9 212,152.8 210.9,149 209.8,152.8 205.9,153.9 209.8,155.1 210.9,158.8   "/>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <!-- Document Views & Downloads -->
                    @if(isset($dashboardWidgetSettings->widget_download) && $dashboardWidgetSettings->widget_download->widget_display=="Y")
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-md-flex dashboard-header">
                            <h4 class="card-title mb-0 flex-grow-1">Document Views & Downloads</h4>
                            <div class="all-btn">
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="all">ALL</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter active" data-value="0">Current Year</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="1">1Y</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="2">2Y</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="3">3Y</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="4">4Y</button>
                                <button type="button" class="btn btn-soft-dark btn-sm docChartFilter" data-value="5">5Y</button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body p-0 pb-2">
                            <div class="w-100">
                                <div id="doc-chart" data-colors='["--vz-primary", "--vz-danger", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                    @endif <!-- end Document Views & Downloads -->
                    <div class="row">
                        <!-- Contact Leads -->
                        @if(isset($dashboardWidgetSettings->widget_conatctleads) && $dashboardWidgetSettings->widget_conatctleads->widget_display=="Y")
                        <div class={{ (isset($dashboardWidgetSettings->widget_inapporval) && $dashboardWidgetSettings->widget_inapporval->widget_display=='Y') ? 'col-xl-6' : 'col-xl-12' }}>
                            <div class="card contactuslead">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Contact Leads</h4>
                                </div><!-- end card header -->
                                <div class="card-body" data-simplebar style="height: 300px;">
                                    <div class="table-responsive"> <!-- table-card -->
                                        <table class="table table-hover table-centered align-middle table-nowrap mb-0 lastchild-border-0">
                                            <thead> <!-- class="text-muted table-light" -->
                                                <tr>
                                                    <!-- <th scope="col">ID</th> -->
                                                    <th scope="col" align="left" title="{{ trans('template.common.name') }}">{{ trans('template.common.name') }}/{{ trans('template.common.emailid') }}</th>
                                                    <!-- <th scope="col" align="left" title="{{ trans('template.common.emailid') }}"> {{ trans('template.common.emailid') }} </th> -->
                                                    <th scope="col" align="left" title="{{ trans('template.powerPanelDashboard.receivedDateTime') }}"> {{ trans('template.powerPanelDashboard.receivedDate') }}</th>
                                                    <th scope="col" align="left" title="{{ trans('template.common.details') }}"> <!-- {{ trans('template.common.details') }} -->&nbsp; </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($leads->isEmpty())
                                                    <tr>
                                                        <td align="center" colspan="4">
                                                            {{ trans('template.powerPanelDashboard.noContactLead') }} 
                                                            <a target="_blank" href="https://www.netclues.com/social-media-marketing"> 
                                                                {{ trans('template.powerPanelDashboard.here') }}
                                                            </a> 
                                                            {{ trans('template.powerPanelDashboard.findContactLead') }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    @foreach ($leads as $key=>$lead)
                                                        @if($key<=4)
                                                        <tr>
                                                            <!-- <td><span class="fw-medium link-primary">#{!! $lead->id !!}</span></td> -->
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-2 contact-avatar">
                                                                        <div class="avatar-sm">
                                                                            <div class="avatar-title bg-black text-white rounded fs-14 text-uppercase">
                                                                                @php
                                                                                $title = explode(' ', $lead->varTitle);
                                                                                $varTitle = '';
                                                                                if(count($title) > 1) {
                                                                                    foreach ($title as $key => $val) {
                                                                                        if($key < 2) { $varTitle .= $val[0]; }
                                                                                    }
                                                                                } else {
                                                                                    $varTitle = substr($lead->varTitle, 0, 2);
                                                                                }
                                                                                @endphp
                                                                                {!! $varTitle !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h6 class="mb-0">{!! $lead->varTitle !!}</h6>
                                                                        <p class="text-muted mb-0 fs-11">{!! App\Helpers\MyLibrary::getDecryptedString($lead->varEmail); !!}</p>
                                                                    </div>
                                                                </div>
                                                                
                                                            </td>
                                                            <!-- <td align="left">
                                                                {!! App\Helpers\MyLibrary::getDecryptedString($lead->varEmail); !!}
                                                            </td> -->
                                                            <td align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT').'', strtotime($lead->created_at)) }}">
                                                                {{ date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($lead->created_at)) }}
                                                            </td>
                                                            <td align="left" class='numeric'>
                                                                <a class="contactUsLead" href="javascript:void(0);" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('template.powerPanelDashboard.clickDetails') }}" id="{!! $lead->id !!}"><i class="ri-arrow-right-up-line fs-16 body-color"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @endif <!-- end tr -->
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div>
                                </div>
                                @if(isset($leads) && !empty($leads) && count($leads) > 0 )
                                    <div class="card-footer">
                                        <div class="justify-content-end">
                                            <a class="btn btn-soft-dark btn-sm" href="{{ url('powerpanel/contact-us') }}" title="{{ trans('template.powerPanelDashboard.seeAllRecords') }}"><i class="ri-file-list-3-line align-middle"></i> {{ trans('template.powerPanelDashboard.seeAllRecords') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div> <!-- .card-->
                        </div><!-- end col -->
                        @endif <!-- end Contact Leads -->

                        <!-- In Approval -->
                        @if(isset($dashboardWidgetSettings->widget_inapporval) && $dashboardWidgetSettings->widget_inapporval->widget_display=="Y")
                        <div class={{ (isset($dashboardWidgetSettings->widget_conatctleads) && $dashboardWidgetSettings->widget_conatctleads->widget_display=='Y') ? 'col-xl-6' : 'col-xl-12' }}>
                            <div class="card inapproval-card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"  title="In Approval">In Approval</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dash-approve-search pull-right">
                                            <!-- <input type="search" class="form-control form-control-solid placeholder-no-fix" placeholder="Search" id="searchfilter"> -->
                                            <div class="cm-search">
                                                <input type="search" class="form-control form-control-solid placeholder-no-fix" placeholder="Search" id="searchfilter">
                                                <span class="open-search cursor-pointer"><i id="clearSearchFilter" class="ri-search-2-line fs-20"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body" data-simplebar style="height: 360px;">
                                    <div class="table-responsive"> <!-- table-card -->
                                        <table class="table table-hover table-centered align-middle table-nowrap mb-0 lastchild-border-0" id="approvals">
                                            <thead> <!-- class="text-muted table-light" -->
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col" align="left" title="Module"> Module </th>
                                                    <!-- <th scope="col" align="left" title="View"> View </th> -->
                                                    <th scope="col" align="left" title="Date &amp; Time"> Date </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($approvals->isEmpty())
                                                    <tr><td align="center" colspan="4">No data available</td></tr>
                                                @else
                                                    @foreach ($approvals as $key=>$approval)
                                                        @if(auth()->user()->can($approval->module->varModuleName.'-reviewchanges'))
                                                        <tr>
                                                            <td><span class="fw-medium link-primary"><a class="body-color" href="{{ url('powerpanel/'.$approval->module->varModuleName) }}?tab=A" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{!! $approval->module->varTitle !!}">#{!! $approval->id !!}</a></span></td>
                                                            <td><a class="body-color" href="{{ url('powerpanel/'.$approval->module->varModuleName.'?tab=A') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{!! $approval->module->varTitle !!}">{!! $approval->module->varTitle !!}</a></td>
                                                            <td align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ date(Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($approval->created_at)) }}">{{ date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($approval->created_at)) }}</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td><span class="fw-medium link-primary">#{!! $approval->id !!}</span></td>
                                                            <td><a href="{{ url('powerpanel/workflow') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create workflow for {!! $approval->module->varTitle !!}">{!! $approval->module->varTitle !!} <span class="badge badge-pill badge-danger">No Workflow</span></a></td>
                                                            <td align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ date(Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($approval->created_at)) }}">
                                                                {{ date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($approval->created_at)) }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div> <!-- .col-->
                        @endif <!-- end In Approval -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-4">
                    <!-- Leads Statistics -->
                    @if(isset($dashboardWidgetSettings->widget_leadstatistics) && $dashboardWidgetSettings->widget_leadstatistics->widget_display=="Y")
                    <div class="card"> <!-- card-height-100 -->
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1" title="Leads Statistics">Leads Statistics</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted" id="currentLeadFilter">Current Year <i class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="all">ALL</a>
                                        <a class="dropdown-item LeadFilter active" href="javascript:void(0);" data-value="0">Current Year</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="1">Last One Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="2">Last Two Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="3">Last Three Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="4">Last Four Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="5">Last Five Years</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="curve_chart" data-colors='["--vz-danger", "--vz-success", "--vz-warning", "--vz-info", "--vz-primary", "--vz-dark"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div> <!-- .card-->
                    @endif <!-- end Leads Statistics -->

                    @if(isset($dashboardWidgetSettings->widget_liveusercountry) && $dashboardWidgetSettings->widget_liveusercountry->widget_display=="Y")
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Live Users By Country</h4>
                        </div><!-- end card header -->
						<div class="card-body">
                            <div id="users-by-country" data-colors='["--vz-light"]' style="height: 140px"></div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                    @endif <!-- end Live Users By Country -->
                    <!-- Form Builder Leads -->
                    @if(isset($dashboardWidgetSettings->widget_formbuilderleads) && $dashboardWidgetSettings->widget_formbuilderleads->widget_display=="Y")
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1" title="In Approval">Form Builder Leads</h4>
                            </div><!-- end card header -->
                            <div class="card-body" data-simplebar style="height: 300px;">
                                <div class="table-responsive"> <!-- table-card -->
                                    <table class="table table-hover table-centered align-middle table-nowrap mb-0 formbuilder-table lastchild-border-0" id="formbuilder_leeds">
                                        <thead> <!-- class="text-muted table-light" -->
                                            <tr>
                                                <th class="date-r" scope="col" title="Date">Date</th>
                                                <th class="name-r" scope="col" align="left" title="Name"> Name </th>
                                                <th class="content-r" scope="col" align="left" title="View"> Contents </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(empty($formBuilderLead))
                                                <tr><td align="center" colspan="4">No data available</td></tr>
                                            @else
                                                @foreach ($formBuilderLead as $key=>$formBuilder)
                                                    @if($key<=4)
                                                    <tr>
                                                        <td class="date-r" align="left">
                                                            <div class="mini-stats-wid">
                                                                <div class="flex-shrink-0 avatar-sm">
                                                                    <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                                        {{ date('d', strtotime($formBuilder[5])) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="name-r">
                                                            <h6 class="mb-1 form_name">{!! $formBuilder[1] !!}</h6>
                                                            <p class="text-muted mb-0 form_email">{!! $formBuilder[2] !!}</p>
                                                        </td>
                                                        <td class="content-r">
                                                            {!! $formBuilder[3] !!}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div>
                            </div><!-- end card-body -->
                            @if(isset($formBuilderLead) && !empty($formBuilderLead) && count($formBuilderLead) > 0 )
                                <div class="card-footer">
                                    <div class="justify-content-end">
                                        <a class="btn btn-soft-dark btn-sm" href="{{ url('powerpanel/formbuilder-lead') }}" title="{{ trans('template.powerPanelDashboard.seeAllRecords') }}"><i class="ri-file-list-3-line align-middle"></i> {{ trans('template.powerPanelDashboard.seeAllRecords') }}</a>
                                    </div>
                                </div>
                            @endif
                        </div><!-- end card -->
                    </div> <!-- .col-->
                    @endif <!-- end Form Builder Leads -->
                </div> <!-- .col-->
            </div>
        </div> <!-- end .h-100-->
    </div> <!-- end col -->
</div>
<!-- End Page-content -->

<div id="detailsCmsPage" class="modal fade detailsCmsPage" tabindex="-1" aria-labelledby="detailsCmsPage" aria-hidden="true"></div>

<div class="modal fade BlogDetails" tabindex="-1" aria-labelledby="BlogDetails" aria-hidden="true"></div>
<!-- ContactUsLead offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="detailsContactUsLead" aria-labelledby="detailsContactUsLead">
</div>

@include('powerpanel.partials.cmsPageCommentsUser')
<script>
    function loadModelpopup(id, intRecordID, fkMainRecord, varModuleNameSpace, intCommentBy, varModuleTitle) {
        $('#CmsPageComments1User').show();
        $('#CmsPageComments1User').modal({
            backdrop: 'static',
            keyboard: false
        });
        document.getElementById('id').value = id;
        document.getElementById('intRecordID').value = intRecordID;
        document.getElementById('fkMainRecord').value = fkMainRecord;
        document.getElementById('varModuleNameSpace').value = varModuleNameSpace;
        document.getElementById('intCommentBy').value = intCommentBy;
        document.getElementById('varModuleTitle').value = varModuleTitle;
        document.getElementById('CmsPageComments_user').value = '';
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/Get_Comments_user",
            data: {'id': id, 'intRecordID': intRecordID, 'fkMainRecord': fkMainRecord, 'varModuleNameSpace': varModuleNameSpace, 'intCommentBy': intCommentBy, 'varModuleTitle': varModuleTitle},
            async: false,
            success: function (data)
            {
                document.getElementById('test').innerHTML = data;
            }
        });
    }
</script>
@endsection

@section('scripts')
<script>window.site_url = '{!! url("/") !!}';</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/dashboard-ajax.js?v='.time() }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<script>
    function dashBoardUpdate(row) {
    var rows = $(row);
        var order = [];
        $.each(rows, function (index) {
        order.push($(this).data('id'));
        });
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/updateorder",
            data: {'order':JSON.stringify(order)},
            async: false,
            success: function (data)
            {
            }
        });
    }
</script>
<script type="text/javascript">
    @if (Session::has('alert-success'))
        toastr.options = {
        "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.success("{{Session::get('alert-success')}} Welcome to {{Config::get('Constant.SITE_NAME')}}.");
    @endif
    @if (Session::has('alert-success'))
        $("#topMsg").show().delay(5000).fadeOut();
        $("#topMsg").fadeOut("slow", function () {
            $('.page-header').css('top', '0');
            $('.page-container').css('top', '0');
        });
    @endif
    $(document).on('click', '#close_icn', function (e) {
        $("#topMsg").hide();
        $('.page-header').css('top', '0');
        $('.page-container').css('top', '0');
    });
    var dataTable = $('#approvals').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "oLanguage": {
            "sEmptyTable": "No Approvals are pending"
        }
    });
    $("#searchfilter").keyup(function () {
        dataTable.search(this.value).draw();
    });
    $("#clearSearchFilter").click(function () {
        var is_open_search_input=$("#clearSearchFilter").parent().parent().hasClass("visible"); // Check the search input is open or not
        if(is_open_search_input==true){   
            $("#searchfilter").val("");
            var searhfilterVal=$("#searchfilter").val();
            dataTable.search(searhfilterVal).draw();     
        }
    });
    var liveUsersEnabled = false;
    @if(isset($dashboardWidgetSettings->widget_liveusercountry) && $dashboardWidgetSettings->widget_liveusercountry->widget_display=="Y")
    	liveUsersEnabled = true;
    @endif
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/dashboard-chart.js?v='.time() }}" type="text/javascript"></script>

<script type="text/javascript">
    $('.open-search').on('click',function() {
      $('.cm-search').toggleClass('visible');
    });
</script>
@endsection