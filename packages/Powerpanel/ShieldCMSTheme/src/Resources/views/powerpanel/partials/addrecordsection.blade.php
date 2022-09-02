@if((isset($adUrl) || isset($marketlink)) && Auth::user()->can(Config::get('Constant.MODULE.NAME').'-create'))
<div class="addrecord_drop drop_border dz-clickable partialDz" @php if(isset($adUrl)){ @endphp onclick='window.location ="{{$adUrl}}"' @php } else {@endphp onclick="window.open('{{$marketlink}}')" @php } @endphp id="my-dropzone">
		 <div class="dz-message needsclick">
				@if(isset($marketlink))
				<img src="{{ Config::get('Constant.CDN_PATH').'resources/global/img/add_record.png' }}">
				<h2 class="sbold">Please Click Here to find out how to get more {{$type}} leads.</h2>
				<h3>No {{$type}} leads received yet.</h3>
				@else
				<img src="{{ Config::get('Constant.CDN_PATH').'resources/global/img/add_record.png' }}">
				<!--      <h2 class="sbold">Okay, Let's Create Your First {{$type}}</h2>-->
				<h2 class="sbold">No records added yet</h2>
				<p>Click on the below button to add a new record.</p>
				<a href="javascript:;" class="btn btn-green-drake">Add New</a>
				<h3></h3>
				@endif
		</div>
</div>
@else
<div class="addrecord_drop noaddrecord drop_border partialDz ropzone-file-area" id="my-dropzone">
		<div class="dz-message needsclick">
				<img src="{{ Config::get('Constant.CDN_PATH').'resources/global/img/norecord_img.png' }}">
				<h2 class="sbold">No Record found</h2>      
		</div>
</div>
@endif