@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())
@if(isset($team) && !empty($team))
<section class="team-details inner-page-gap">  
      <div class="container">
         <div class="row">
         {{--  <div class="col">
               <div class="d-name">
                   <h3 class="nqtitle n-mt-lg-30 n-mt-20">{{ $team->varTitle }}</h3>
                   <p>{{ $team->varTagLine }}</p>
               </div>
               </div>
            <div class="col">
               <div class="date text-right"><h2 class="year">{{ date("Y") }}</h2> </div>
            </div>   --}}
         </div>        
         {{--  <div class="name-owner col-md-8">
            <p>{{ $team->varTagLine }}</p>
         </div>--}}
         <div class="row">
            <div class="col-lg-5 col-sm-12">
               <div class="-img">
                  <div class="thumbnail-container">
                     <div class="thumbnail">
                        <img src="{{ App\Helpers\resize_image::resize($team->fkIntImgId) }}" alt="{{ $team->varTitle }}">
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-7 col-sm-12 cms">
               <div class="d-name">
                  <h4 class="nqtitle">{{ $team->varTitle }}</h4>
                  <p>{{ $team->varTagLine }}</p>
               </div>
               <div class="team-info">
                  @if(isset($team->varEmail) && $team->varEmail != "")
                  <ul>
                     <li>
                        <span class="phone"><b>Email : </b>{{ $team->varEmail }}</span>
                     </li>
                     @endif
                     @if(isset($team->varPhoneNo) && $team->varPhoneNo != "")
                     <li>
                        <span class="phone"> <b>Phone : </b></span>
                        <span class="phone-desc">{{ $team->varPhoneNo }}</span>
                     </li>
                     @endif
                     {{-- @if(isset($team->varAddress) && $team->varAddress != "")
                     <li>
                        <span class="phone-desc"><b>Address :</b><br>{{ $team->varAddress }}</span>
                     </li>
                  </ul>
                  @endif --}}
               </div>
               <p>{{ $team->varShortDescription }}</p>
               {{-- <p>{{ $team->varShortDescription }}</p> --}}                        
            </div>
            <div class="profile-contact w-100">
               <div class="col-md-12 cms n-mt-15 n-mt-lg-10">
                  {!! htmlspecialchars_decode($txtDescription) !!}
               </div>
            </div>
         </div>
      
         @php $docURL = '' @endphp
         @if(isset($team->fkIntDocId) && !empty($team->fkIntDocId))
         @php
         $docObj = App\Document::getDocDataById($team->fkIntDocId);
         @endphp
         @foreach($docObj as $key => $val)
         @php
         $CDN_PATH = Config::get('Constant.CDN_PATH');
         if ($val->fk_folder > 0 && !empty($val->foldername)) {
         if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
         $docURL = route('viewFolderPDF',['dir' => 'documents' ,'foldername' => $val->foldername,'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
         } else {
         $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
         }
         } else {
         if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
         $docURL = route('viewPDF',['dir' => 'documents','filename' => $val->txtSrcDocumentName.'.'.$val->varDocumentExtension]);
         }
         else{
         $docURL = $CDN_PATH . 'documents/'. $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
         }
         }
         @endphp
         @endforeach
         @endif
         @if(!empty($docURL))
         <a target="_blank" href="{{ $docURL }}" class="ac-btn ac-btn-primary n-mt-30 n-ml-60" title="Read More">Download</a>
         @endif
      </div>
   
</section>
@endif
@endif
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif