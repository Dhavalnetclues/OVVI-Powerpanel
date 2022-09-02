@php
    $checked = (isset($checked)) ? $checked : '';
    $name = (isset($name)) ? $name : '';
    $id = (isset($id)) ? $id : '';
    $data_off_text = (isset($data_off_text)) ? $data_off_text : 'No';
    $data_on_text = (isset($data_on_text)) ? $data_on_text : 'Yes';
    $class = (isset($class)) ? $class : '';
    $data_controller = (isset($data_controller)) ? $data_controller : '';
    $title = (isset($title)) ? $title : '';
    $data_value = (isset($data_value)) ? $data_value : '';
    $data_alias = (isset($data_alias)) ? $data_alias : '';
@endphp

<input {{$checked}} name="{{ $name }}" id="{{ $id }}" data-off-text="{{ $data_off_text }}" data-on-text="{{ $data_on_text }}" class="form-check-input publish {{ $class }}" data-off-color="info" data-on-color="primary" type="checkbox" data-controller="{{ $data_controller }}" title="{{ $title }}" data-value="{{ $data_value }}" data-alias="{{ $data_alias }}" data-bs-toggle="tooltip" data-bs-placement="bottom" >