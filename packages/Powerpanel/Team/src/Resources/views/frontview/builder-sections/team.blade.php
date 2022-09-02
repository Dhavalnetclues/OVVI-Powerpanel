@php $teamurl = ''; @endphp
@if(isset($data['team']) && !empty($data['team']) && count($data['team']) > 0)

    {{-- FOR TEAM  --}}
    <div class="team-listing-wrap">
        <div class="container">
            <div class="col-xl-12" id="pageContent">
              {{--<h2 class="nqtitle n-mt-lg-30 n-mb-lg-30 n-mb-30 n-mb-0 aos-init aos-animate" data-aos="fade-up">Our Management Team</h2>--}}
                <div class="team-sec">
                    <div class="item">
                        <div class="team-item">
                            <div class="row">
                                @foreach($data['team'] as $team)
                                    @php
                                        $recordLinkUrl = '';
                                        if(isset(App\Helpers\MyLibrary::getFront_Uri('team')['uri']))
                                        {
                                            $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('team')['uri'];
                                            $moduleFrontWithCatUrl = ($team->varAlias != false ) ? $moduelFrontPageUrl . '/' . $team->varAlias : $moduelFrontPageUrl;
                                            $recordLinkUrl = $moduleFrontWithCatUrl.'/'.(isset($team->alias->varAlias)?$team->alias->varAlias:'');
                                        }

                                        $phoneNo = isset($team->varPhoneNo) ? $team->varPhoneNo : '';
                                        $email = isset($team->varEmail) ? $team->varEmail : '';
                                    @endphp

                                    @if(isset($team->fkIntImgId))
                                        @php $itemImg = App\Helpers\resize_image::resize($team->fkIntImgId); @endphp
                                    @else
                                        @php $itemImg = $CDN_PATH.'assets/images/directors.png'; @endphp
                                    @endif

                                    @if(isset($team->varShortDescription))
                                        @php $description = $team->varShortDescription; @endphp
                                    @endif
                                    <div class="col-lg-4 col-md-6 team-sec">
                                        <div class="team-member">
                                            <div class="team-img">
                                                <div class="thumbnail-container">
                                                    <div class="thumbnail object-fit">
                                                        <a href="{{$recordLinkUrl}}" class="img_hover" title="{{ $team->varTitle }}">
                                                            <img src="{{ $itemImg }}" alt="{{ $team->varTitle }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="team-desc">
                                                <div class="name">
                                                <a href="{{$recordLinkUrl}}" title="{{ $team->varTitle }}">
                                                     {{ $team->varTitle }}</a>
                                                     </div>
                                                <div class="desig"> {{ $team->varTagLine}} </div>
                                                {{-- <div class="desc cms"><p> {!! $description !!} </p></div> --}}
                                                {{-- <div class="team-footer">
                                                    <span class="social-connect">
                                                        <ul class="nqulli">
                                                            @if(isset($team->varPhoneNo))
                                                                <li><a href="tel:{{$phoneNo}}" class=""><i class="fa fa-phone"></i></a></li>
                                                            @endif
                                                            @if(isset($team->varEmail))
                                                                <li><a href="mailto:{{$email}}" class=""><i class="fa fa-envelope"></i></a></li>
                                                            @endif
                                                        </ul>
                                                    </span>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif