<div class="row justify-content-center">
    <div class="col-sm-8" data-aos="zoom-in">
        <h2 class="nqtitle-ip text-center">How can we help you?</h2>
        <div class="ac-form-wd n-mt-15">
            <div class="form-group ac-form-group">
                <label class="ac-label" for="firstName">Search for answers</label>
                <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="col-12" data-aos="zoom-in">
        <ul class="nqul ac-collapse accordion" id="faqaccordion">
            @php $i = 1; @endphp
            @foreach($data['faqs'] as $key=>$faqArr)
                @if($i == 1)
                    @php $class = ''; @endphp
                @else
                    @php $class = 'collapsed'; @endphp
                @endif
                <li class="-li">
                    <a class="-tabs {{ $class }}" data-toggle="collapse" href="#faqid_{{ $faqArr->id }}" aria-expanded="true" aria-controls="faqid_{{ $faqArr->id }}" title="{{ $faqArr->varTitle }}">{{ $faqArr->varTitle }} <span></span></a>
                    <div id="faqid_{{ $faqArr->id }}" class="-info collapse @if($key==0) show @endif" aria-labelledby="headingOne" data-parent="#faqaccordion">
                        <div class="cms">
                            {!! $faqArr->txtDescription !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-lg-10" data-aos="zoom-in">
        <div class="n-bs-1 n-pa-40 n-mt-15">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-9">
                    <div class="nqtitle-small">Need to know more?</div>
                    <p class="n-fs-16 n-ff-2 n-fw-400 n-lh-150 n-fc-black-500 n-mt-5">Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>It is a long established fact that a reader will be distracted.</p>
                </div>
                <div class="col-lg-3 n-tar-lg n-mt-15 n-mt-lg-15">
                    <a href="#" class="ac-btn ac-btn-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>