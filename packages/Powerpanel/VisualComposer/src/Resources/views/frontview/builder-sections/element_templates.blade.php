 @if($eval['type'] == "only_title")
    @php
        $data = array();
        $data['title'] = $eval['val']['content'];
        $data['extra_class'] = $eval['val']['extclass'];
        $data['headingtype'] = isset($eval['val']['headingtype']) ? ($eval['val']['headingtype']) : "";
    @endphp
    @include('visualcomposer::frontview.builder-sections.page-elements.title-block',compact('data'))
@elseif($eval['type'] == "textarea")
    @php
        $data = array();
        $data['content'] = $eval['val']['content'];
        $data['extclass'] = $eval['val']['extclass'];
    @endphp
    @include('visualcomposer::frontview.builder-sections.page-elements.description-block',compact('data'))
@elseif($eval['type'] == "accordianblock")

    @php
        $data = array();
        $data['content'] = $eval['val']['content'];
        $data['title'] = $eval['val']['title'];
        $data['ekey'] = $ekey;
    @endphp

    @if($ekey == 0)
        <ul class="nqul ac-collapse accordionv" id="accordionExample">
    @endif
        @include('visualcomposer::frontview.builder-sections.page-elements.accordian-block',compact('data'))
    @if(isset($totalElement) && (($totalElement + 1) == $ekey))
        </ul>
    @endif

@elseif($eval['type'] == "image")
    @php
        $data = array();
        $data = $eval['val']; 
    @endphp
    @include('visualcomposer::frontview.builder-sections.page-elements.image-block',compact('data'))
@elseif($eval['type'] == "document")
    @php
        $data = array();
        $data = $eval['val']; 
    @endphp
    @include('visualcomposer::frontview.builder-sections.page-elements.document-block',compact('data'))
@elseif($eval['type'] == "button_info")
    @php
        $data = array();
        $data = $eval['val']; 
    @endphp
    @include('visualcomposer::frontview.builder-sections.page-elements.button',compact('data'))
@endif