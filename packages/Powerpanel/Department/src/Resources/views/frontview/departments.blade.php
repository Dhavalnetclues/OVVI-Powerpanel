@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
<section>
    <div class="inner-page-container cms">
        <div class="container"> 
            @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
            <div id='pageContent'></div>
        </div>
    </div>
</section>
@else
    <section>
        <div class="inner-page-container cms">
            <div class="container">
                @if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
                    {!! $PageData['response'] !!}
                @else 
                    <section class="page_section">
                        <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <h2>Coming Soon...</h2>
                                    </div>	
                                </div>
                        </div>  
                    </section>    
                @endif
            </div>
        </div>
    </section>
@endif
<style>
    .password_form {
        padding: 40px;
        background: #fff;
        box-shadow: 0 0 25px rgba(0,0,0,.5);
        max-width: 600px;
        margin: auto;
    }
    .password_form .label-title {    
        font-weight: 400;
        margin-bottom: 5px;
        font-size: 14px;
        color: gray;
    }
    .ac-border {   
        max-width: 200px;
        width: 100%;
        margin-top:10px;
    }
</style>
@endsection