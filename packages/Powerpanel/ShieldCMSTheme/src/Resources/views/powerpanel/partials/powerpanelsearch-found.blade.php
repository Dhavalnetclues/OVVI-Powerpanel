@if(!Request::ajax())
@extends('powerpanel.layouts.app')
    @section('title')
        {{Config::get('Constant.SITE_NAME')}} - PowerPanel
    @endsection
    @section('css')
    @endsection
@section('content')
@endif
@if(!Request::ajax())
      <!-- start page title -->
      {{-- <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Search Results</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                          <li class="breadcrumb-item active">Search Results</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div> --}}
      <!-- end page title -->

      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header border-0 p-0">
                      <div class="row justify-content-center mt-4 mb-1">
                          <div class="col-lg-12">
                              <h5 class="fs-16 fw-semibold text-center mb-0">
                                Showing results for "<span class="text-primary fw-medium fst-italic">{{ $searchTerm }}</span>"
                              </h5>
                          </div>
                      </div><!--end row-->
                  </div>
                  @endif
                  @if(!empty($searchResults) && count($searchResults) > 0)
                  @if(!Request::ajax())
                  {{-- <div>
                      <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                          <li class="nav-item">
                              <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab" aria-selected="false">
                                  <i class="ri-search-2-line text-muted align-bottom me-1"></i> All Results
                              </a>
                          </li>
                      </ul>
                  </div> --}}
                  
                  <div class="card-body p-4" id="gridbody_front">
                      <div > <!-- class="tab-content text-muted" -->
                          <div id="all" role="tabpanel"> <!-- class="tab-pane active" -->
                          	<div class="searchres_load">
                          	@endif
                          	@foreach($searchResults as $result)
                          	@php
                          	$url = url('/powerpanel/'.$result->varModuleName);
              							$url = $url.'/?term='.urlencode($result->term);
              							@endphp
                              <div class="py-4">
                                  <h5 class="mb-2"><a href="{{ $url }}" target="_blank">{{ $result->term }}</a> @if(!empty($result->moduleTitle))<span>({{ ucfirst($result->moduleTitle) }})</span>@endif</h5>
                                  {{-- <p class="text-success mb-2">https://themesbrand.com/velzon/index.html</p> --}}
                                  <p class="text-muted mb-0">{!! trim(substr(	strip_tags($result->info), 0, 275)); !!}</p>
                              </div>
                              <div class="border border-dashed"></div>
														@endforeach
														@if(!Request::ajax())
														</div>
														<div class="newajaxLoadmorebtn">
															@if(!empty($searchResults) && count($searchResults) > 0 && $currentPage != $lastPage)
																<div class="ajaxLoadmorebtn mt-4 mb-1">
																	<div class="load-more text-center">
																			<a href="javascript:;" id="load-more" title="Load More" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
																				<div class="flex-shrink-0">
                                            <i class="ri-loader-2-line label-icon align-middle fs-20 me-2 loadicon"></i>
                                        </div> Load More	                  
																			</a>
																	</div>
																</div>
															@endif
														</div>
                              {{-- <div>
                                  <ul class="pagination pagination-separated justify-content-center mb-0">
                                      <li class="page-item disabled">
                                          <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                      </li>
                                      <li class="page-item active">
                                          <a href="javascript:void(0);" class="page-link">1</a>
                                      </li>
                                      <li class="page-item">
                                          <a href="javascript:void(0);" class="page-link">2</a>
                                      </li>
                                      <li class="page-item">
                                          <a href="javascript:void(0);" class="page-link">3</a>
                                      </li>
                                      <li class="page-item">
                                          <a href="javascript:void(0);" class="page-link">4</a>
                                      </li>
                                      <li class="page-item">
                                          <a href="javascript:void(0);" class="page-link">5</a>
                                      </li>
                                      <li class="page-item">
                                          <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                      </li>
                                  </ul>
                              </div> --}}
                          </div>
                      </div><!--end tab-content-->
                  </div><!--end card-body-->

                  @endif
              </div><!--end card -->
          </div><!--end card -->
      </div><!--end row-->
      @endif
      @if(!Request::ajax())
	@section('scripts')
	<script type="text/javascript">
		var pageNumber = {!! isset($currentPage) ? $currentPage : 1 !!};
		var ajaxModuleUrl = "{{ url('powerpanel/search') }}";
		$(document).on("click", '#load-more', function () {
			pageNumber += 1;
			$.ajax({
					type: 'POST',
					url: ajaxModuleUrl,
					data:{
							page:pageNumber,
							current_page:ajaxModuleUrl,
							'_token':$('meta[name="csrf-token"]').attr('content'),
							searchValue:encodeURIComponent("{{ $searchTerm }}")
					},
					dataType: "json",
					beforeSend: function() {
			        // setting a timeout
			        $('#gridbody_front .newajaxLoadmorebtn .loadicon').addClass('mdi-spin');
			    },
					success: function (data) {
							if (data.length == 0) {
							} else {
									$('.searchres_load').append(data.html);
									$("#gridbody_front").find('.ajaxLoadmorebtn').remove();
									$("#gridbody_front .newajaxLoadmorebtn").html(data.loadmoreHtml);
							}
					},
					complete: function () {
							$('#gridbody_front .newajaxLoadmorebtn .loadicon').removeClass('mdi-spin');
					},
					error: function (data) {
						$('#gridbody_front .newajaxLoadmorebtn .loadicon').removeClass('mdi-spin');
					},
			});
	});
	</script>
	@endsection
	@endif
@endsection