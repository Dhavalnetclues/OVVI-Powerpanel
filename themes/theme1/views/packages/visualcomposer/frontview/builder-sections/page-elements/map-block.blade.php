@if(Request::segment(1) != '')
    <div class="location_map">
        <iframe src="http://maps.google.com/maps?q={{ $data['latitude'] }}, {{ $data['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
    </div>
 @else
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="location_map">
                    <iframe src="http://maps.google.com/maps?q={{ $data['latitude'] }}, {{ $data['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
                </div>
            </div>
        </div>
    </section>
 @endif   