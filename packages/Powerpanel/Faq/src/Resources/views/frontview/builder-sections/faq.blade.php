@if(isset($data['faqs']) && !empty($data['faqs']) && $data['faqs'] != '[]')
<div class="row">

    {{-- <div class="col-sm-8" data-aos="zoom-in">
        <h2 class="nqtitle-ip text-center">How can we help you?</h2>
        <div class="ac-form-wd n-mt-15">
            <div class="form-group ac-form-group">
                <label class="ac-label" for="firstName">Search for answers</label>
                <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
            </div>
        </div>
    </div> --}}
    <div class="col-12">
        <ul class="nqul ac-collapse accordion" id="faqaccordion">
            @php $i = 1; @endphp
            @foreach($data['faqs'] as $key => $faqArr)
                @if($i == 1)
                    @php $class = ''; @endphp
                @else
                    @php $class = 'collapsed'; @endphp
                @endif
                <li class="card">
                    <div class="card-header">
                        <a class="card-link -tabs {{ $class }}" data-toggle="collapse" href="#faqid_{{ $faqArr->id }}" aria-expanded="true" aria-controls="faqid_{{ $faqArr->id }}" title="{{ $faqArr->varTitle }}">{{ $faqArr->varTitle }} 
                            <i class="n-icon fa fa-angle-down" data-icon="s-arrow-down"></i>
                        </a>
                    </div>
                    <div id="faqid_{{ $faqArr->id }}" class="-info collapse @if($key==0) show @endif" aria-labelledby="headingOne" data-parent="#faqaccordion">
                        <div class="cms card-body">
                            {!! $faqArr->txtDescription !!}
                        </div>
                    </div>
                </li>
                @php $i++; @endphp    
            @endforeach
        </ul>
    </div>
</div>
@endif