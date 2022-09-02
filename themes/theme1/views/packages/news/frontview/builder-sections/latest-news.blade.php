@if(isset($data['latestNews']) && !empty($data['latestNews']))

    @if(isset($data['class']))
        @php $class = $data['class']; @endphp
    @endif

    @php
        $moduelFrontPageUrl = '#';
        $recordLinkUrl = '#';
        if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])) {
            $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
        }
    @endphp

    <section class="n-pt-30 n-pt-lg-60 n-pb-50 n-pb-lg-100 home-notification {{ $class }}" data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 d-md-flex align-self-center">
					<div class="latest-n">
						<span>{{ $data['title'] }}</span>
					</div>
					<div class="n-item-m">
						<div class="owl-carousel">
                            @php
                                $recordLinkUrl = (isset($data['latestNews']->alias->varAlias) && !empty($data['latestNews']->alias->varAlias)) ? $moduelFrontPageUrl . '/' . $data['latestNews']->alias->varAlias : $moduelFrontPageUrl;
                            @endphp
                            <div class="item">
                                <a href="{{ $recordLinkUrl }}" title="{{ $data['latestNews']->varTitle  }}">{{ str_limit($data['latestNews']->varTitle,100) }}</a>
                                @if(isset($data['latestNews']->dtDateTime) && $data['latestNews']->dtDateTime != '')
                                    <span class="date"> {{ date('M',strtotime($data['latestNews']->dtDateTime)) }} {{ date('d',strtotime($data['latestNews']->dtDateTime)) }}, {{ date('Y',strtotime($data['latestNews']->dtDateTime)) }} </span>
                                @endif
                            </div>
						</div>
					</div>
					<div class="clearfix d-md-none"></div>
					<div class="read-more n-mt-10 n-mv-md-0">
						<a href="{{ $recordLinkUrl }}" title="Read More">Read More <i class="fa fa-angle-right d-md-none d-sm-block n-ml-5"></i></a>
					</div>

					<div class="view-all-n n-mt-10 n-mv-md-0">
						<a href="{{ $moduelFrontPageUrl }}" title="All News">All News <i class="fa fa-angle-right d-md-none d-sm-block n-ml-5"></i></a>
					</div>
				</div>
			</div>
		</div>
	</section>
@endif
