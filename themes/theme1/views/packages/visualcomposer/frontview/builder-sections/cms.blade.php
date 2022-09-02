
@if(isset($data['img']) && $data['img'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.image-block')

@elseif(isset($data['document']) && $data['document'] != '')
    
    @include('visualcomposer::frontview.builder-sections.page-elements.document-block')

@elseif(isset($data['alignment']))

    @include('visualcomposer::frontview.builder-sections.page-elements.image_with_info')

@elseif(isset($data['videotitle']))

    @include('visualcomposer::frontview.builder-sections.page-elements.video_with_info')

@elseif(isset($data['btntitle']))

    @include('visualcomposer::frontview.builder-sections.page-elements.button')

@elseif(isset($data['leftcontent']) && $data['leftcontent'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.2partContent')

@elseif(isset($data['content']) && $data['content'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.description-block')

@elseif(isset($data['vidId']) && $data['vidId'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.video-block')
    
@elseif(isset($data['latitude']) && $data['latitude'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.map-block')
    
@elseif(isset($data['section_address']) && $data['section_address'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.contact-info')

@elseif(isset($data['title']) && $data['title'] != '')

    @include('visualcomposer::frontview.builder-sections.page-elements.title-block')

@endif
