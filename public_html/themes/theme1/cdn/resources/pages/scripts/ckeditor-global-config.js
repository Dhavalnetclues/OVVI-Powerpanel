CKEDITOR.replace( 'txtDescription', {
	toolbarGroups: [
			{"name":"basicstyles","groups":["basicstyles"]},
			{"name":"links","groups":["links"]},
			{"name":"paragraph","groups":["list","blocks"]},
			{"name":"document","groups":["mode"]},
			{"name":"insert","groups":["insert"]},
			{"name":"styles","groups":["styles"]},
			{"name": 'colors',"groups":["styles"]}
	],	
  filebrowserBrowseUrl : CKEDITOR_APP_URL+'ckeditor/pdw_file_browser/index.php?editor=ckeditor',
  filebrowserImageBrowseUrl : CKEDITOR_APP_URL+'ckeditor/pdw_file_browser/index.php?editor=ckeditor&filter=image',
  filebrowserUploadUrl: CKEDITOR_APP_URL+'ckeditor/pdw_file_browser/swfupload/Quickupload.php?editor=ckeditor',
  filebrowserFlashBrowseUrl : CKEDITOR_APP_URL+'ckeditor/pdw_file_browser/index.php?editor=ckeditor&filter=flash'
});
