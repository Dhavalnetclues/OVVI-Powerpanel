@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section>
    <div class="inner-page-container cms">
        <div class="container">

            @if(isset($PassPropage) && $PassPropage == 'PP')
            
            <div class="contact_form password_form" id='passpopup'>
                <!-- PassWord Start -->                    
                <p class="statusMsg"></p>
                {!! Form::open(['method' => 'post','url' => url('PagePass_URL_Listing'), 'id'=>'passwordprotect_form']) !!}
                <input type='hidden' name='id' id='id' value='{{ $Pageid }}'>
                <input type='hidden' name='tablename' id='tablename' value='cms_page'>
                <div class="form-group">
                    <label class="label-title" for="name">Password</label>
                    <input type="password" class="form-control ac-input" maxlength="20" id="passwordprotect" name='passwordprotect' value='' placeholder="Enter your password"/>
                </div>                      
                <div class="text-center"><button class="btn ac-border" title="Submit">Submit</button></div>

                {!! Form::close() !!}

                <!-- PassWord End  -->                        
            </div>
            <div id='passwordcontent'></div>
            @else
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
            @endif
        </div>
    </div>
</section>
<script type="text/javascript">
    $("#edit_cms").click(function () {
        $("#details_cms").hide();
        $("#edit_cms").hide();
        $("#ck_cms").show();
        $("#close_cms").show();
    });

    $("#close_cms").click(function () {
        $("#details_cms").show();
        $("#edit_cms").show();
        $("#ck_cms").hide();
        $("#close_cms").hide();
    });
</script>
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

