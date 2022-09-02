@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                        
    <div class="col-xl-12" id="pageContent">
    </div>
@else
    <section>
        <div class="inner-page-container cms">
            <div class="container">
                <?php
                    if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]') {
                        echo $PageData['response'];
                    }
                ?>
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

