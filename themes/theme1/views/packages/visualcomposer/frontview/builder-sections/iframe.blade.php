@if(isset($data['extclass']) && $data['extclass'] != '')
    @php $extclass = $data['extclass']; @endphp
@else
    @php $extclass = ''; @endphp
@endif
<div class="{{ $extclass }}">
    <iframe src="{{ $data['iframe'] }}" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
        
