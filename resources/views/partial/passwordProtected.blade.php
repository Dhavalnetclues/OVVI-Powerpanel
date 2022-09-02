<div class="col-xl-9 n-mt-25 n-mt-xl-0 ac-form-wd" id='passpopup'>
    <div class="row justify-content-center whois-information">
        <div class="col-sm-8">
            <!-- PassWord Start -->
            <h2 class="nqtitle-ip text-center">This Page is a Password Protected</h2>
            <div class="cms text-center n-mt-10 n-mb-25">
                {{-- <p style="text-align:center;"></p> --}}
            </div>
            <p class="statusMsg"></p>
            {{--'method' => 'post','url' => url('PagePass_URL_Listing'), --}}
            {!! Form::open(['class'=>'passwordprotect_form','id'=>'passwordprotect_form']) !!}
                <input type='hidden' name='id' id='id' value='{{ $Pageid }}'>
                <input type='hidden' name='tablename' id='tablename' value='{{ $tablename }}'>
                <div class="form-group ac-form-group n-mb-0">
                    <label class="form_passwordprotect ac-label" for="passwordprotect">Password</label>
                    {!! Form::password('passwordprotect',  array('autocomplete' => 'off', 'placeholder'=> 'Enter Your Password', 'maxlength'=>20, 'class' => 'form-control ac-input', 'id'=>'passwordprotect')) !!}
                    <button id="submit" class="-search ac-btn ac-btn-primary" title="Submit">Submit</button>
                </div>
                <span class="error" id="error"></span>
            {!! Form::close() !!}
            <!-- PassWord End  -->
        </div>
    </div>
</div>

<script>
    var passwordProtectURL = "{!!url('PagePass_URL_Listing')!!}"
</script>
<script src="{{ $CDN_PATH.'assets/js/passwordprotected.js' }}" type="text/javascript"></script>
