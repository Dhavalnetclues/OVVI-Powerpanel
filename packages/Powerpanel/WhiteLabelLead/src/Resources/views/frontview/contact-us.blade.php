@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section class="inner-page-gap contact-page">
   <div class="container">
      <div class="row">
         <div class="col-lg-6 col-md-6">
            <div class="address-wrap">
              <div class="heading">
                <h3 class="title">Get in touch with us.</h3>
                <p>Want to get in touch? We'd love to hear from you. Here's how you can reach us...</p>
              </div>
               <ul class="list-unstyled">
                  <li>
                     <div class="add-item">
                        <div class="icn-sec">
                           <i class="fa fa-home" aria-hidden="true"></i>
                        </div>
                        <div class="info">
                           <h3 class="c-title">Mailing Address</h3>
                           <p>{{substr($primaryContact->mailingaddress, 0, 12)}}</p>
                           <p>{{substr($primaryContact->mailingaddress, 13, 24)}}</p>
                           <p>{{substr($primaryContact->mailingaddress, 35, 50)}}</p>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="add-item">
                        <div class="icn-sec">
                           <i class="fa fa-clock-o" aria-hidden="true"></i>
                        </div>
                        <div class="info">
                           <h3 class="c-title">Business Hours</h3>
                           <p>{{substr($primaryContact->txtDescription, 0, 17)}}</p>
                           <p>{{substr($primaryContact->txtDescription, 18, 18)}}</p>
                           <p>{{substr($primaryContact->txtDescription, 36, 40)}}</p>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="add-item">
                        <div class="icn-sec">
                           <i class="fa fa-phone" aria-hidden="true"></i>
                        </div>
                        <div class="info">
                           <h3 class="c-title">Support</h3>
                           <p><a href="mailto:{{$primaryContact->varEmail}}">{{$primaryContact->varEmail}}</a></p>
                           <p><a href="tel:{{$primaryContact->varPhoneNo}}" title="Call Us On {{$primaryContact->varPhoneNo}}">{{$primaryContact->varPhoneNo}}</a></p>
                           <p><a href="tel:{{$primaryContact->varFax}}" title="Call Us On {{$primaryContact->varFax}}">{{$primaryContact->varFax}}</a></p>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="add-item">
                        <div class="icn-sec">
                           <i class="fa fa-home" aria-hidden="true"></i>
                        </div>
                        <div class="info">
                           <h3 class="c-title">Physical Address</h3>
                           <p>{{substr($primaryContact->txtAddress, 0, 30)}}</p>
                           <p>{{substr($primaryContact->txtAddress, 31, 33)}}</p>
                           <p>{{substr($primaryContact->txtAddress, 64, 100)}}</p>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
         <div class="col-lg-6 col-md-6">
            <div class="contact-wrap">
               {!! Form::open(['method' => 'post','class'=>'nqform','id'=>'contactus_form']) !!}
               <div class="row">
                  <div class="col-lg-6">
                     <div class="ac-form-group">
                        <label>Full Name</label>
                        {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'ac-input form-control', 'autocomplete'=>'off','id'=>'first_name', 'placeholder'=>'Name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="ac-form-group">
                        <label>Phone no.</label>
                        {!! Form::text('phone', old('phone'), array('id'=>'phone', 'class'=>'ac-input form-control', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;','placeholder'=>'Phone', 'ondrop'=>'return false;')) !!}
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="ac-form-group">
                        <label>Email</label>
                        {!! Form::text('email', old('email'), array('id'=>'email', 'class'=>'ac-input form-control', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;','placeholder'=>'Email', 'ondrop'=>'return false;')) !!}
                     </div>
                  </div>
                  {{-- <div class="col-lg-6">
                     <div class="ac-form-group">
                        {!! Form::text('contacting_about', old('contacting_about'), array('id'=>'contacting_about', 'class'=>'ac-input form-control', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;','placeholder'=>'What are you contacting about', 'ondrop'=>'return false;')) !!}
                     </div>
                  </div> --}}
                  <div class="col-lg-12">
                     <div class="ac-form-group">
                        <label>Message</label>
                        {!! Form::textarea('message', old('message'), array('id'=>'message', 'class'=>'ac-textarea form-control', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600','placeholder'=>'Message', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-12">
                     <div class="form-group ac-form-group">
                        <div id="contactus_html_element" class="g-recaptcha"></div>
                        <div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                           @if ($errors->has('g-recaptcha-response'))
                           <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-12">
                     <div class="form-group ac-form-group n-mb-0 n-mt-lg-0 n-tar-lg n-tal">
                        <button id="contact_submit" type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                     </div>
                  </div>
               </div>
               {!! Form::close() !!}
            </div>
         </div>
         <div class="col-md-12">
            <div class="contact-map">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.2941225368018!2d72.52761451535397!3d23.012970522460883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84606d232851%3A0xc5c64db3565e9695!2sNetclues%20India!5e0!3m2!1sen!2sin!4v1609400228811!5m2!1sen!2sin" width="100%"></iframe>
            </div>
         </div>
      </div>
   </div>
   {{--
   <div class="cont-map">
      <div class="container-fluid p-0">
         <div id="map" style="width:100%;height:400px;"> </div>
         <div class="overlay-text">
            <img class="map-pin" src="{{ $CDN_PATH.'assets/images/map-pin.png' }}">
            <div class="add-box">
               <div class="name">PHYSICAL ADDRESS</div>
               <p>{{$primaryContact->txtAddress}}</p>
               <p>{{substr($primaryContact->txtAddress, 0, 30)}}</p>
               <p>{{substr($primaryContact->txtAddress, 31, 33)}}</p>
               <p>{{substr($primaryContact->txtAddress, 64, 100)}}</p>
            </div>
         </div>
      </div>
   </div>
   --}}
</section>
@endsection
@section('page_scripts')
<script type="text/javascript">
   var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
   var address = '501, Mauryansh Elanza, 5th Floor, Nr Parekh Hospital Nr, Shyamal Cross Rd, Satellite, Ahmedabad, Gujarat 380015';
   var onContactloadCallback = function () {
       grecaptcha.render('contactus_html_element', {
           'sitekey': sitekey
       });
   };

   function initMap() {
   
       var map;
   
       geocoder = new google.maps.Geocoder();
   
       if (geocoder) {
   
           geocoder.geocode({
   
               'address': address
   
           }, function (results, status) {
   
               if (status == google.maps.GeocoderStatus.OK) {
   
                   var lattitude = results[0].geometry.location.lat();
   
                   var longitude = results[0].geometry.location.lng();
   
                   map = new google.maps.Map(document.getElementById('map'), {
   
                       center: {
   
                           lat: lattitude,
   
                           lng: longitude
   
                       },
   
                       zoom: 20,
   
                       styles: [{
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#ebe3cd"
   
                                   }]
   
                           }, {
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#523735"
   
                                   }]
   
                           }, {
   
                               "elementType": "labels.text.stroke",
   
                               "stylers": [{
   
                                       "color": "#f5f1e6"
   
                                   }]
   
                           }, {
   
                               "featureType": "administrative",
   
                               "elementType": "geometry.stroke",
   
                               "stylers": [{
   
                                       "color": "#c9b2a6"
   
                                   }]
   
                           }, {
   
                               "featureType": "administrative.land_parcel",
   
                               "elementType": "geometry.stroke",
   
                               "stylers": [{
   
                                       "color": "#dcd2be"
   
                                   }]
   
                           }, {
   
                               "featureType": "administrative.land_parcel",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#ae9e90"
   
                                   }]
   
                           }, {
   
                               "featureType": "landscape.natural",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#dfd2ae"
   
                                   }]
   
                           }, {
   
                               "featureType": "poi",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#dfd2ae"
   
                                   }]
   
                           }, {
   
                               "featureType": "poi",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#93817c"
   
                                   }]
   
                           }, {
   
                               "featureType": "poi.park",
   
                               "elementType": "geometry.fill",
   
                               "stylers": [{
   
                                       "color": "#a5b076"
   
                                   }]
   
                           }, {
   
                               "featureType": "poi.park",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#447530"
   
                                   }]
   
                           }, {
   
                               "featureType": "road",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#f5f1e6"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.arterial",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#fdfcf8"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.highway",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#f8c967"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.highway",
   
                               "elementType": "geometry.stroke",
   
                               "stylers": [{
   
                                       "color": "#e9bc62"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.highway.controlled_access",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#e98d58"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.highway.controlled_access",
   
                               "elementType": "geometry.stroke",
   
                               "stylers": [{
   
                                       "color": "#db8555"
   
                                   }]
   
                           }, {
   
                               "featureType": "road.local",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#806b63"
   
                                   }]
   
                           }, {
   
                               "featureType": "transit.line",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#dfd2ae"
   
                                   }]
   
                           }, {
   
                               "featureType": "transit.line",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#8f7d77"
   
                                   }]
   
                           }, {
   
                               "featureType": "transit.line",
   
                               "elementType": "labels.text.stroke",
   
                               "stylers": [{
   
                                       "color": "#ebe3cd"
   
                                   }]
   
                           }, {
   
                               "featureType": "transit.station",
   
                               "elementType": "geometry",
   
                               "stylers": [{
   
                                       "color": "#dfd2ae"
   
                                   }]
   
                           }, {
   
                               "featureType": "water",
   
                               "elementType": "geometry.fill",
   
                               "stylers": [{
   
                                       "color": "#b9d3c2"
   
                                   }]
   
                           }, {
   
                               "featureType": "water",
   
                               "elementType": "labels.text.fill",
   
                               "stylers": [{
   
                                       "color": "#92998d"
   
                                   }]
   
                           }]
   
                   });
   
                   var infowindow = new google.maps.InfoWindow({
   
                       content: pinaddress
   
                   });
   
                   var marker = new google.maps.Marker({
   
                       position: {
   
                           lat: lattitude,
   
                           lng: longitude
   
                       },
   
                       map: map
   
                   });
   
                   marker.addListener('click', function () {
   
                       infowindow.open(map, marker);
   
                   });
   
                   infowindow.open(map, marker);
   
               }
   
           });
   
       }
   
   }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH.'assets/js/packages/contact-us/contactus-form.js' }}" type="text/javascript"></script>
@endsection