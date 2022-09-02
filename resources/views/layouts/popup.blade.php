
@if(isset($popupObj) && !empty($popupObj))
    


<div class="modal fade common-modal" id="myModel_{{ $popupObj->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"></button>
                <div class="image">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img  src="{!! App\Helpers\resize_image::resize($popupObj->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($popupObj->fkIntImgId) !!}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
    {{-- <div class="modal fade common-modal" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <div class="image">
                        <div class="thumbnail-container">
                            <div class="thumbnail">
                                <img src="{!! url('cdn/assets/images/poupop.png') !!}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}



<script>
    $(window).on('load', function () {
    
                            $('#myModel_<?php echo $popupObj->id; ?>').modal('show');

             
               
});
</script>
@endif