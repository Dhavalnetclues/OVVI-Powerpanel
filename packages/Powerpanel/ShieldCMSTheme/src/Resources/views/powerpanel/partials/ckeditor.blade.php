<script src="{{ $CDN_PATH.'resources/global/plugins/ckeditor/ckeditor.js' }}"></script>
<script>
class ImageUploadAdapter
{
    constructor( loader ) {
        // The file loader instance to use during the upload.
        this.loader = loader;
    }

    // Starts the upload process.
    upload() {
        return this.loader.file
            .then( file => new Promise( ( resolve, reject ) => {
                this._initRequest();
                this._initListeners( resolve, reject, file );
                this._sendRequest( file );
            } ) );
    }

    // Aborts the upload process.
    abort() {
        if ( this.xhr ) {
            this.xhr.abort();
        }
    }

    // Initializes the XMLHttpRequest object using the URL passed to the constructor.
    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();

        // Note that your request may look different. It is up to you and your editor
        // integration to choose the right communication channel. This example uses
        // a POST request with JSON as a data structure but your configuration
        // could be different.

        xhr.open( 'POST', '{{ url("/powerpanel/ckeditor/upload-image") }}', true);
        xhr.responseType = 'json';
    }

    // Initializes XMLHttpRequest listeners.
    _initListeners( resolve, reject, file ) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${ file.name }.`;

        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
        xhr.addEventListener( 'abort', () => reject() );
        xhr.addEventListener( 'load', () => {

            const response = xhr.response;

            // This example assumes the XHR server's "response" object will come with
            // an "error" which has its own "message" that can be passed to reject()
            // in the upload promise.
            //
            // Your integration may handle upload errors in a different way so make sure
            // it is done properly. The reject() function must be called when the upload fails.
            if ( !response || response.error ) {
                return reject( response && response.error ? response.error.message : genericErrorText );
            }

            // If the upload is successful, resolve the upload promise with an object containing
            // at least the "default" URL, pointing to the image on the server.
            // This URL will be used to display the image in the content. Learn more in the
            // UploadAdapter#upload documentation.
            resolve( {
                default: response.url
            } );
        } );

        // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
        // properties which are used e.g. to display the upload progress bar in the editor
        // user interface.
        if ( xhr.upload ) {
            xhr.upload.addEventListener( 'progress', evt => {
                if ( evt.lengthComputable ) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            } );
        }
    }

    // Prepares the data and sends the request.
    _sendRequest( file ) {
        // Prepare the form data.
        const data = new FormData();

        data.append( 'upload', file );

        // Important note: This is the right place to implement security mechanisms
        // like authentication and CSRF protection. For instance, you can use
        // XMLHttpRequest.setRequestHeader() to set the request headers containing
        // the CSRF token generated earlier by your application.
        // Send the request.
        this.xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        this.xhr.send( data );
    }
}

function ImageUploadAdapterPlugin( editor ) {
    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
        // Configure the URL to the upload script in your back-end here!
        return new ImageUploadAdapter( loader );
    };
}
var editorObj;

ClassicEditor.create( document.querySelector( '#txtDescription' ),
{
    placeholder:"Type the content here!",
    toolbar: [ 'heading', '|', 'bold', 'italic', 'underline', 'strikethrough' ,'|' ,'alignment', 'blockQuote', 'insertTable', '|' , 'bulletedList', 'numberedList', '|',  'link', 'imageUpload','mediaEmbed','|','pageBreak', 'horizontalLine', '|', 'removeFormat', '|' ,'indent', 'outdent', '|', 'undo', 'redo'],
    blockToolbar: ['paragraph', 'heading2', 'heading3', '|', 'bold', 'italic', 'underline', 'strikethrough' ,'|' ,'alignment', 'blockQuote', 'insertTable', '|' , 'bulletedList', 'numberedList', '|',  'link','pageBreak', 'horizontalLine'],
    extraPlugins: [ ImageUploadAdapterPlugin ],
    heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                { model: 'heading1', view: { name: 'h1'} , title: 'Heading 1', class: 'h1' },
                { model: 'heading2', view: { name: 'h2'},  title: 'Heading 2', class: 'h2' },
                { model: 'heading3', view: { name: 'h3'},  title: 'Heading 3', class: 'h3' },
                { model: 'heading4', view: { name: 'h4'},  title: 'Heading 4', class: 'h4' },
                { model: 'heading5', view: { name: 'h5'},  title: 'Heading 5', class: 'h5' },
                { model: 'heading6', view: { name: 'h6'},  title: 'Heading 6', class: 'h6' },
                { model: 'classh1', view: { name: 'div', classes:'h1'},  title: 'Class H1' },
                { model: 'classh2', view: { name: 'div', classes:'h2'},  title: 'Class H2' },
                { model: 'classh3', view: { name: 'div', classes:'h3'},  title: 'Class H3' },
                { model: 'classh4', view: { name: 'div', classes:'h4'},  title: 'Class H4' },
                { model: 'classh5', view: { name: 'div', classes:'h5'},  title: 'Class H5' },
                { model: 'classh6', view: { name: 'div', classes:'h6'},  title: 'Class H6' }
            ]
    },
    image: {
        resizeUnit: 'px',
        toolbar: ['imageTextAlternative', '|','imageStyle:alignLeft', 'imageStyle:full','imageStyle:alignCenter', 'imageStyle:alignRight' ],
        styles: [
            'full',
            'alignLeft',
            'alignCenter',
            'alignRight'
        ]
    },
    table: {
		contentToolbar: [
			'tableColumn',
			'tableRow',
			'mergeTableCells'
		]
    },
}).then( editor => {
            editorObj = editor;
            
            var wordCountPlugin = editor.plugins.get( 'WordCount' );
            var wordCountWrapper = document.getElementById( 'word-count' );
            //wordCountWrapper.appendChild( wordCountPlugin.wordCountContainer );

}).catch( error => {
    console.error( error );
});

</script>
