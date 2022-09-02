@if(Request::segment(1) != '')

    <div class="mailing_box animated fadeInUp load">
        @if(isset($data['section_address']) && $data['section_address'] != '')
        <h4>Address</h4>
        <p>{{ $data['section_address'] }}</p>
        @endif
        <p>
            @if(isset($data['section_email']) && $data['section_email'] != '')
            <b>Email:-</b> <a href="mailto:{{ $data['section_email'] }}" title="{{ $data['section_email'] }}">{{ $data['section_email'] }}</a><br>
            @endif
            @if(isset($data['section_phone']) && $data['section_phone'] != '')
            <b>Phone:-</b><a href="tel:{{ $data['section_phone'] }}"> {{ $data['section_phone'] }}</a><br>
            @endif
        </p>
        @if(isset($data['othercontent']) && $data['othercontent'] != '')
        <p>{!! $data['othercontent'] !!}</p>
        @endif
    </div>

@else

    <section class="section">
        <div class="container">
            <div class="row">
                @if(isset($data['section_address']) && $data['section_address'] != '')
                <div class="col-sm-12">
                    <div class="mailing_box animated fadeInUp load">
                        @if(isset($data['section_address']) && $data['section_address'] != '')
                        <h4>Address</h4>
                        <p>{{ $data['section_address'] }}</p>
                        @endif
                        <p>
                            @if(isset($data['section_email']) && $data['section_email'] != '')
                            <b>Email:-</b> <a href="mailto:{{ $data['section_email'] }}" title="{{ $data['section_email'] }}">{{ $data['section_email'] }}</a><br>
                            @endif
                            @if(isset($data['section_phone']) && $data['section_phone'] != '')
                            <b>Phone:-</b><a href="tel:{{ $data['section_phone'] }}"> {{ $data['section_phone'] }}</a><br>
                            @endif
                        </p>
                        @if(isset($data['othercontent']) && $data['othercontent'] != '')
                        <p>{!! $data['othercontent'] !!}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    
@endif