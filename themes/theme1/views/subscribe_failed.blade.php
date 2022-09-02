@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@endif
<section class="notfound">
    <div class="container">
        <div class="notfound__table">
            <div class="notfound__center">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div class="notfound_main">
                            <div class="notfound__title">
                                <div class="notfound__desc">
                                    <h1>Access Denied</h1>The link you are trying to access is no longer exist.
                                </div>
                                <div class="notfound__link">
                                    <a href="{{ url('/') }}" title="Back To Home" class="btn"> Back To Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif