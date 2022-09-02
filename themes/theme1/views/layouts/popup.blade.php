@if(isset($popupObj) && !empty($popupObj))

@foreach($popupObj as $key => $popup )
@if(isset($popup->fkModuleId) && !empty($popup->fkModuleId) )
@php
$module = \App\Modules::getModuleById($popup->fkModuleId);

if(isset($module->id) && !empty($module->id) && $module->id == 3 ){
$alias = \App\Alias::getAliasbyID($popup->fkIntPageId);
if($alias->intFkModuleCode == $popup->fkModuleId)
{
$segment1 = $alias->varAlias;
}
}

else{
if ($module->varModuleNameSpace != '') {
$model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
} else {
$model = '\\App\\' . $module->varModelName;
}
$aliasid = $model::getRecordById($popup->fkIntPageId);
$alias = \App\Alias::getAliasbyID($aliasid->intAliasId);

if($alias->intFkModuleCode == $popup->fkModuleId)
{
$segment2 = $alias->varAlias;

}
}
@endphp

@endif


{{-- @if(isset($segment1) && Request::segment(1) == $segment1 && !empty($popup->fkModuleId == 3))

 <div class="modal fade common-modal" id="myModel_{{ $popup->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"></button>
                <div class="image">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img  src="{!! App\Helpers\resize_image::resize($popup->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($popup->fkIntImgId) !!}">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@elseif(isset($segment2) && Request::segment(2) == $segment2 && !empty($popup->fkModuleId))
<div class="modal fade common-modal" id="myModell_{{ $popup->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"></button>
                <div class="image">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img  src="{!! App\Helpers\resize_image::resize($popup->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($popup->fkIntImgId) !!}">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@elseif(empty($popup->fkModuleId) && $popup->fkModuleId == '' && $popup->chrDisplay == 'Y' )
<div class="modal fade common-modal" id="myModal">
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
    </div>
@endif --}}




@endforeach
 
@endif
<script>
    $(window).on('load', function () {
<?php
foreach ($popupObj as $key => $popup) {
    if (isset($popup->fkModuleId) && !empty($popup->fkModuleId)) {
        $module = \App\Modules::getModuleById($popup->fkModuleId);
        if (isset($module->id) && !empty($module->id) && $module->id == 3) {
            $alias = \App\Alias::getAliasbyID($popup->fkIntPageId);
            if ($alias->intFkModuleCode == $popup->fkModuleId) {
                $segmentone = $alias->varAlias;
            }
        } else {
            if ($module->varModuleNameSpace != '') {
                $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
            } else {
                $model = '\\App\\' . $module->varModelName;
            }
            $aliasid = $model::getRecordById($popup->fkIntPageId);
            $alias = \App\Alias::getAliasbyID($aliasid->intAliasId);

            if ($alias->intFkModuleCode == $popup->fkModuleId) {
                $segmenttwo = $alias->varAlias;
            }
        }
    }
    if (isset($segmentone) && Request::segment(1) == $segmentone && !empty($popup->fkModuleId == 3)) {
        ?>
                $('#myModel_<?php echo $popup->id; ?>').modal('show');


    <?php } elseif (isset($segmenttwo) && Request::segment(2) == $segmenttwo) {
        ?>

                $('#myModell_<?php echo $popup->id; ?>').modal('show');

        <?php
    }
    ?>
<?php }
?>

        $('#myModal').modal('show');


    });
</script>