@php
$name = (isset($name)) ? $name : '';
$id = (isset($id)) ? $id : '';
$class = (isset($class)) ? $class : '';
$value = (isset($value)) ? $value : '';
@endphp
<input type="checkbox" name="{{ $name }}" id="{{ $id }}" class="chkDelete form-check-input multiSelectList {{ $class }}" value="{{ $value }}">