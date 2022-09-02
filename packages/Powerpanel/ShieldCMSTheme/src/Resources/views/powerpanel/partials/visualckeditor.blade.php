<!-- for ckeditor and file upload  code start -->

{{-- <script src="{{ $CDN_PATH.'vendor/unisharp/laravel-ckeditor/config.js' }}"></script> --}}
{{-- <script>
  var CKConfig = '{{ isset($config)?$config:'generalConfig' }}';
  var route_prefix = "{{ url( config('lfm.url_prefix')  ) }}";
</script> --}}


<script src="{{ $CDN_PATH.'resources/global/plugins/ckeditor5-build-classic/ckeditor.js' }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'js/ckfinder/ckfinder.js' }}"></script>
<script type="text/javascript">
var allEditors = document.querySelectorAll('.txtarea');


var simpleConfig = {
      alignment: {
          options: [ 'left', 'right', 'center' , 'justify' ]
      },
      toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'alignment', 'undo', 'redo' ],
      heading: {
          options: [
              { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },                
              { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
              { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
              { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
              { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
              { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
          ]
      }
  };

var cmsConfig = {
      alignment: {
          options: [ 'left', 'right', 'center' , 'justify' ]
      },//'imageUpload'
      toolbar: [
      'heading',       
      '|', 
      'bold', 
      'italic',
      'underline',      
      //'highlight',
      'link', 
      'bulletedList', 
      'numberedList',
      'fontSize',
      'fontFamily',
      'fontColor', 
      'fontBackgroundColor',
//      '|', 
//      'code',
      'imageUpload',
      //'ckfinder',
      //'|', 
      'insertTable',
      'blockQuote',
      'alignment',
      'undo', 
      'redo' ],
      heading: {
          options: [
              { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },                
              { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
              { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
              { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
              { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
              { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
          ]
      },
      fontSize: {
          options: [
              9,
              11,
              13,
              'default',
              17,
              19,
              21
          ]
      },
      table: {
        contentToolbar: [
          'tableColumn',
          'tableRow',
          'mergeTableCells'
        ]
      },
      highlight: {
        options: [
            {
                model: 'greenPen',
                class: 'pen-green',
                title: 'Green pen',
                color: 'hsl(120,100%,25%)',
                type: 'pen'
            },
            {
                model: 'redPen',
                class: 'pen-red',
                title: 'Red pen',
                color: 'hsl(343, 82%, 58%)',
                type: 'pen'
            },
            {
                model: 'greenMarker',
                class: 'marker-green',
                title: 'Green marker',
                color: 'rgb(25, 156, 25)',
                type: 'marker'
            },
            {
                model: 'yellowMarker',
                class: 'marker-yellow',
                title: 'Yellow marker',
                color: '#cac407',
                type: 'marker'
            }            
        ]
    },
    ckfinder: {
        uploadUrl: window.site_url+'/powerpanel/media/upload_image'
        //uploadUrl: window.site_url+'/ckfinder/connector?command=QuickUpload&type=Files&responseType=json'
        //uploadUrl:'https://cksource.com/weuy2g4ryt278ywiue/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
    }
  };

var ckconfig = cmsConfig;

@if(isset($config) && $config == 'simpleConfig')
ckconfig = simpleConfig;
@endif

var editors = {};
for (var i = 0; i < allEditors.length; ++i) {    
    ClassicEditor.create(allEditors[i], ckconfig).then( editor => {      
      var id = $(editor.sourceElement).attr('id');      
      editors[id] = editor;
    } );
}
   
</script>
