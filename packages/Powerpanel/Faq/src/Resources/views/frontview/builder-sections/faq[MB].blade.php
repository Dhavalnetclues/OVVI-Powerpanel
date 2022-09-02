<section>
    @php 
    if(isset($data['class'])){
    $class = $data['class'];
    }
    @endphp
    <div class="inner-page-container cms faqs_section {{ $class }}">
        <!-- Inner Shap Logo S -->
        <div class="inner_shap">
            <div class="shap_1"></div>
            <div class="shap_2"></div>
        </div>
        <!-- Inner Shap Logo E -->
        <div class="container">
            <!-- Main Section S -->
            <div class="row">
                <div class="col-md-9 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
                        <div class="panel_listing panel-group section_node" id="accordion">
                            @if(isset($PassPropage) && $PassPropage == 'PP')
                            <div class="password_form" id='passpopup'>
                                <!-- PassWord Start -->                    
                                <p class="statusMsg"></p>
                                {!! Form::open(['method' => 'post','url' => url('PagePass_URL_Listing'), 'id'=>'passwordprotect_form']) !!}
                                <input type='hidden' name='id' id='id' value='{{ $Pageid }}'>
                                <input type='hidden' name='moduleid' id='moduleid' value='{{ $moduleid }}'>
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="name">Password</label>
                                    <div class="password-field">
                                        <input type="password" class="form-control ac-input" maxlength="20" id="passwordprotect" name='passwordprotect' value='' placeholder="Enter your password"/>
                                        <button class="ac-btn ac-btn-primary" title="Submit">Submit</button>
                                    </div>
                                </div>                      
                                <div class="text-center">
                                </div>
                                {!! Form::close() !!}
                                <!-- PassWord End  -->                        
                            </div>
                            <div id='passwordcontent'></div>
                            @else
                            @if(!empty($data['faqs']) && count($data['faqs'])>0)
                            @php
                            $i = 1;
                            @endphp
                            @foreach($data['faqs'] as $key=>$faqArr)

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        @if($i == 1)
                                        @php
                                        $class = '';
                                        @endphp
                                        @else
                                        @php
                                        $class = 'collapsed';
                                        @endphp
                                        @endif
                                        <a title="{{ $faqArr->varTitle }}" data-toggle="collapse" data-parent="#accordion" href="#faqid_{{ $faqArr->id }}" class="{{ $class }}">
                                            {{ $faqArr->varTitle }}</a>
                                    </h4>
                                </div>
                                <div id="faqid_{{ $faqArr->id }}" class="panel-collapse collapse @if($key==0) in @endif">
                                    <div class="panel-body">
                                        {!! $faqArr->txtDescription !!}
                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                            @else
                            <h2 class="no_record">No Record Found.</h2>
                            @endif
                            @if($data['faqs']->total() > $data['faqs']->perPage())
                                <div class="row">
                                    <div class="col-sm-12 n-mt-30" data-aos="fade-up">
                                            {{ $data['faqs']->links() }}
                                    </div>
                                </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Section E -->
        </div>
    </div>
</section>
<script type="text/javascript">
    var ajaxModuleUrl = "{{ App\Helpers\MyLibrary::getFront_Uri('faq')['uri'] }}";
</script>
<script src="{{ $CDN_PATH.'assets/js/packages/faq/faq.js' }}"></script>
