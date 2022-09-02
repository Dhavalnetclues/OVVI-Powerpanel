@php
$contacturl = '';
@endphp
<section>
    <div class="inner-page-container cms">
        <div class="container">
            <!-- Main Section S -->
            <div class="row">
                @php
                if(isset($data['class'])){
                $class = $data['class'];
                }
                @endphp
                <div class="col-md-9 col-md-12 col-xs-12 animated fadeInUp {{ $class }}">
                    <div class="right_content">
                        @if(isset($data['department']) && count($data['department']) > 0)
                        <div class="department_listing animated fadeInUp">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel_listing panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        @php $i = 0; @endphp
                                        @foreach($data['department'] as $index => $department)
                                        @if($i % 2 == 0)
                                        <div class="panel panel-default" id="headingcollapseTen{{ $department->id }}">
                                            <div class="panel-heading" role="tab" id="headingOne{{ $department->id }}">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" id="depacollapseTen{{ $department->id }}" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTen{{ $department->id }}" aria-expanded="true" aria-controls="collapseTen{{ $department->id }}" title="{{ $department->varTitle }}">
                                                        {{ $department->varTitle }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTen{{ $department->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne{{ $department->id }}">
                                                <div class="panel-body">
                                                    <div class="contact-details">
                                                        @if(!empty($department->varPhoneNo))
                                                        <p><b>Phone: </b><a href="tel:{{ $department->varPhoneNo }}" title="Call us On: {{ $department->varPhoneNo }}">{{ $department->varPhoneNo }}</a></p>
                                                        @endif
                                                        @if(!empty($department->varfax))
                                                        <p><b>Fax: </b><span>{{ $department->varfax }}</span></p>
                                                        @endif
                                                        @if(!empty($department->varEmail))
                                                        <p>
                                                            <b>Email: </b>
                                                            <a href="mailto:{{ $department->varEmail }}" title="Email us on: {{ $department->varEmail }}">{{ $department->varEmail }}</a>
                                                        </p>
                                                        @endif
                                                        <a href="{{ $contacturl }}?id={{ $department->id }}" onclick="contact_department({{ $department->id }})" title="Contact Us" class="btn custom-btn">Contact Us</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @php $i++; @endphp
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel_listing panel-group" id="accordion2" role="tablist" aria-multiselectable="true">

                                        @if(isset($data['department']) && count($data['department']) > 0)
                                        @php $i = 0; @endphp
                                        @foreach($data['department'] as $index => $department)
                                        @if($i % 2 != 0)
                                        <div class="panel panel-default" id="headingcollapseTen{{ $department->id }}">
                                            <div class="panel-heading" role="tab" id="headingTen{{ $department->id }}">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" id="depacollapseTen{{ $department->id }}" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseTen{{ $department->id }}" aria-expanded="false" aria-controls="collapseTen{{ $department->id }}" title="{{ $department->varTitle }}">
                                                        {{ $department->varTitle }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTen{{ $department->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen{{ $department->id }}">
                                                <div class="panel-body">
                                                    <div class="contact-details">
                                                        @if(!empty($department->varPhoneNo))
                                                        <p><b>Phone: </b><a href="tel:{{ $department->varPhoneNo }}" title="Call us On: {{ $department->varPhoneNo }}">{{ $department->varPhoneNo }}</a></p>
                                                        @endif
                                                        @if(!empty($department->varfax))
                                                        <p><b>Fax: </b><span>{{ $department->varfax }}</span></p>
                                                        @endif
                                                        @if(!empty($department->varEmail))
                                                        <p>
                                                            <b>Email: </b>
                                                            <a href="mailto:{{ $department->varEmail }}" title="Email us on: {{ $department->varEmail }}">{{ $department->varEmail }}</a>
                                                        </p>
                                                        @endif
                                                        <a href="{{ $contacturl }}?id={{ $department->id }}" onclick="contact_department({{ $department->id }})" title="Contact Us" class="btn custom-btn">Contact Us</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @php $i++; @endphp
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Main Section E -->
        </div>
    </div>
</section>
<script type="text/javascript">
$(document).ready(function()
{
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            $("a#depa" + hashes).removeClass("collapsed");
            $("div#" + hashes).addClass("in");

            $("html, body").animate({
    scrollTop: $("#heading" + hashes).offset().top - $('header').height() - 100
    }, 1200);
});
</script>
