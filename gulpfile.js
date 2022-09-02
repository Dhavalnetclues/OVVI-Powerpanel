'use strict';

/* ----------------------------
	Install Node Js
	https://nodejs.org/en/download/
	-----------------------------
	Install Gulp Globally 
	@Note: Only one time for one machine.
	npm install gulp-cli -g
	----------------------------
	STEP:1
	----------------------------
	Open node.js command prompt
	d: [MOVE TO COMPUTER DRIVE]
	cd [PROJECT_FOLDER_PATH]
	After then run below command
	----------------------------
	STEP: 2
	----------------------------
	npm install gulp --save-dev
	npm init
	npm install
---------------------------- */


/* Variable Declared S */
	var gulp				=	require('gulp'),
	    plumber				=	require('gulp-plumber'),
	    notify 				= 	require('gulp-notify'),
	    sass				=	require('gulp-sass'),
	    stripCssComments	=	require('gulp-strip-css-comments'),
	    cleanCSS			=	require('gulp-clean-css'),
	    combineMq			=	require('gulp-combine-mq'),
	    autoprefixer 		= 	require('gulp-autoprefixer'),
	    htmlbeautify		=	require('gulp-html-beautify'),	    
	    extname 			= 	require('gulp-extname'),
	    ftp 				= 	require('vinyl-ftp'),
	    gutil 				= 	require('gulp-util'),
	    browserSync			=	require('browser-sync').create(),

	    del 				= 	require("del"),
	    rename 				= 	require("gulp-rename"),
	    count 				= 	require('gulp-file-count'),
	    minify 				= 	require('gulp-minifier'),
	    htmlreplace 		= 	require('gulp-html-replace'),
	    replace 			= 	require('gulp-replace'),
	    useref 				= 	require('gulp-useref'),
	    gulpif 				= 	require('gulp-if'),
    	uglify 				= 	require('gulp-uglify'),
	    open 				= 	require('gulp-open'),
	    wait 				= 	require('gulp-wait'),
	    purify 				= 	require('gulp-purify-css'),

	    sitePath			=	'E:/wamp64/www/Ofreg/Ofreg/',
	    folder = {
	    	designHTML				: 	sitePath + 'resources/views/',
	    	designHTMLPro			: 	sitePath + 'build/',

	        designSource 			: 	sitePath + 'public_html/cdn/assets/',
	        designSourcePro 		: 	sitePath + 'build/assets/',
	        
	        developmentMainUrl		: 	'http://localhost/Ofreg/Ofreg/public_html',
	    },
	    productionmainUrl = {
		  	uri: 'http://localhost/project-name/build/',
		  	app: 'chrome'
		},
		uploadFolder = '';
/* Variable Declared E */


/* Gulpfile Development S */
	/* BrowserSync Processing S */
		function developmentMainUrl(done) {
		    browserSync.init({
		        proxy: folder.developmentMainUrl,
		        //port: 5000
		    });
		    done();
		}
		function browserSyncReload(done) {
		  browserSync.reload();
		  done();
		}
	/* BrowserSync Processing E */

	/* SCSS Processing S */
		function scss() {
		    var onError = function(err) {
		        notify.onError({
		            title:    "Gulp",
		            subtitle: "Failure!",
		            message:  "Sass Task Error: <%= error.message %>",
		            sound:    "Beep"
		        })(err);
		        this.emit('end');
		    };
		    return gulp.src(folder.designSource + '*.+(scss)')
		        .pipe(plumber({errorHandler: onError}))
		        .pipe(sass({
		            outputStyle: 'nested',				// compressed | nested | expanded
		            imagePath: '../images/',
		            precision: 4,
		            errLogToConsole: true,
		        }))
		        .pipe(stripCssComments())
		        .pipe(cleanCSS({compatibility: 'ie8'}))
		        .pipe(combineMq({
					beautify: false					// false | true
			    }))
			    .pipe(autoprefixer({
    	            browsers: ['last 7 versions'],
    	            cascade: true
    	        }))
		        .pipe(gulp.dest(folder.designSource + 'css/'))
		        .pipe(browserSync.stream());
		}
	/* SCSS Processing E */

	function purify() {
	    return gulp.src(folder.designSource + 'css/main.css')
	    	.pipe(purify([folder.designHTML + '**/*.+(php|html)']))
	        .pipe(gulp.dest(folder.designSource + 'css/one/'));
	};

	/* HTML Beautify S */
		function htmlb () {
		    var options = {
	            "indent_size": 4,
	            "indent_char": " ",
	            "eol": "\n",
	            "indent_level": 0,
	            "indent_with_tabs": false,
	            "preserve_newlines": true,
	            "max_preserve_newlines": 0,
	            "jslint_happy": false,
	            "space_after_anon_function": false,
	            "brace_style": "collapse",
	            "keep_array_indentation": false,
	            "keep_function_indentation": false,
	            "space_before_conditional": true,
	            "break_chained_methods": false,
	            "eval_code": false,
	            "unescape_strings": false,
	            "wrap_line_length": 0,
	            "wrap_attributes": "auto",
	            "wrap_attributes_indent_size": 4,
	            "end_with_newline": false
		    };
		    return gulp.src(folder.designHTML + '**/*.+(php|html)')
		        .pipe(plumber())
		        .pipe(htmlbeautify(options))
		    	.pipe(gulp.dest(folder.designHTML));
		};
	/* HTML Beautify E */

	/* File Move S */
		function copy() {
		    gulp.src(folder.designSource + 'libraries/html5/html5.min.js')
	    		.pipe(gulp.dest(folder.designSource + 'js/'))

	    	gulp.src(folder.designSource + 'libraries/jquery/jquery-3.5.1.min.js')
	    		.pipe(gulp.dest(folder.designSource + 'js/'))

	    	gulp.src(folder.designSource + 'libraries/font-awesome-4.7.0/fonts/**/*')
	    		.pipe(gulp.dest(folder.designSource + 'fonts/'))

	    	return gulp.src(folder.designSource + 'libraries/owl.carousel/img/**/*')
	    		.pipe(gulp.dest(folder.designSource + 'images/'));
		}
	/* File Move E */

	/* Watch for Changes S */
		function watch() {
			gulp.watch(folder.designSource + '**/*.scss', gulp.series(scss));
			gulp.watch(folder.designSource + '**/*.js', gulp.series(browserSyncReload));
			gulp.watch(folder.designHTML + '**/*.php', gulp.series(browserSyncReload));
		}
	/* Watch for Changes E */
/* Gulpfile Development E */


/* Gulpfile Production S */
	/* Delete Folder S */
		function clean () {
		  	return del([folder.designHTMLPro]);
		}
	/* Delete Folder E */

	/* CSS Minify S */
		function css () {
		    return gulp.src(folder.designSource + 'css/main.css')
		        .pipe(plumber())
		        .pipe(cleanCSS({compatibility: 'ie8'}))
		        .pipe(rename({ suffix: ".min" }))
		        .pipe(gulp.dest(folder.designSourcePro + 'css/'))
		}
	/* CSS Minify E */

	/* Images, Fonts Move E */
		function move (done) {     
			gulp.src(folder.designSource + 'js/**/*')
	    		.pipe(gulp.dest(folder.designSourcePro + 'js/'))

			gulp.src(folder.designSource + 'libraries/**/*')
	    		.pipe(gulp.dest(folder.designSourcePro + 'libraries/'))

			gulp.src(folder.designSource + 'fonts/**/*')
	    		.pipe(gulp.dest(folder.designSourcePro + 'fonts/'))

	    	gulp.src(folder.designHTML + '**/*.+(php|html)')
	    		.pipe(gulp.dest(folder.designHTMLPro))

	    	gulp.src([folder.designHTML + '.htaccess', folder.designHTML + '.htpasswd', folder.designHTML + 'robots.txt'])
	    		.pipe(gulp.dest(folder.designHTMLPro))

		    return gulp.src(folder.designSource + 'images/**/*')
	    		.pipe(gulp.dest(folder.designSourcePro + 'images/'))
	    }
	/* Images, Fonts Move S */

	/* CSS Replace S */
		function replacecss () {
			return gulp.src(folder.designHTMLPro + '**/*.+(php|html)')
			    .pipe(plumber())
			    .pipe(htmlreplace({
			    	'css': '<link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/css/main.min.css" media="all" />',
			    	'define': "<?php define('SITE_PATH', '" + productionmainUrl.uri + "'); ?>",
			    }))
			    .pipe(wait(5000))
				.pipe(gulp.dest(folder.designHTMLPro));
		}
	/* CSS Replace E */

	/* JS Path Replace S */
		function replacejspath () {
			return gulp.src(folder.designHTML + 'Templates/footer.php')
			    .pipe(plumber())
			    .pipe(wait(3000))
			    .pipe(replace('<script src="assets/', '<script src="../assets/'))
			    .pipe(wait(3000))
				.pipe(gulp.dest(folder.designHTMLPro + 'Templates/'));
		}
	/* JS Path Replace E */

	/* Combined CSS Js S */
		function userefCssJs () {
			return gulp.src(folder.designHTMLPro + '**/*.+(php|html)')
				.pipe(plumber())
				.pipe(wait(3000))
			    .pipe(useref())
				.pipe(gulp.dest(folder.designHTMLPro));
		}
	/* Combined CSS Js E */

	/* HTML Minify S */
		function htmlm () {
		    var optionsMinify = {
	            "indent_size": 0,
	            "indent_char": "",
	            "eol": "",
	            "indent_level": 0,
	            "indent_with_tabs": false,
	            "preserve_newlines": true,
	            "max_preserve_newlines": 0,
	            "jslint_happy": false,
	            "space_after_anon_function": false,
	            "brace_style": "collapse",
	            "keep_array_indentation": false,
	            "keep_function_indentation": false,
	            "space_before_conditional": true,
	            "break_chained_methods": false,
	            "eval_code": false,
	            "unescape_strings": false,
	            "wrap_line_length": 0,
	            "wrap_attributes": "auto",
	            "wrap_attributes_indent_size": 0,
	            "end_with_newline": false
		    };
		    return gulp.src(folder.designHTMLPro + '**/*.+(php|html)')
		        .pipe(plumber())
		        .pipe(htmlbeautify(optionsMinify))
		    	.pipe(gulp.dest(folder.designHTMLPro));
		};
	/* HTML Minify E */

	/* JS Minify S */
		function jscompress () {
		    return gulp.src(folder.designSourcePro + 'js/**/*')
		        .pipe(plumber())
		        .pipe(minify({
		        	minify: true,
	        	    minifyJS: {
	        	      sourceMap: false
	        	    },
	        	    getKeptComment: function (content, filePath) {
	        	        var m = content.match(/\/\*![\s\S]*?\*\//img);
	        	        return m && m.join('\n') + '\n' || '';
	        	    }
		        }))
		        .pipe(gulp.dest(folder.designSourcePro + 'jscompressjs/'))
		}

		function movejscompressjs (done) {
			gulp.src(folder.designSourcePro + 'jscompressjs/jquery.min.js')
	    		.pipe(gulp.dest(folder.designSourcePro + 'js/'))

		    return gulp.src(folder.designSourcePro + 'jscompressjs/pagesjs/**/*')
	    		.pipe(gulp.dest(folder.designSourcePro + 'js/pagesjs/'))
	    }
	/* JS Minify E */

	/* Delete Folder S */
		function librariesRemove () {
		  	return del([folder.designSourcePro + 'libraries', folder.designSourcePro + 'js/**/*']);
		}
		function jscompressjsRemove () {
		  	return del([folder.designSourcePro + 'jscompressjs']);
		}
	/* Delete Folder E */

	/* Upload S */
		function upload() {
		    var conn = ftp.create( {
		        host:     '',
		        user:     '',
		        password: '',
		        parallel: 5,
		        log: gutil.log
		    });
		    return gulp.src([folder.designHTMLPro + '**/*',], {base: folder.designHTMLPro, buffer: false})
		        .pipe(conn.newer('/public_html/front-html/'))
		        .pipe(conn.dest('/public_html/front-html/'))
		}
	/* Upload E */

	/* Open Production Mode URL  S */
		function productionMainUrl () {
			return gulp.src(__filename)
				.pipe(open(productionmainUrl))
				.pipe(notify({
		           	title: 'Gulp',
		           	subtitle: 'Success',
		           	message: '\n\nProduction Mode Successfully Run.\n# CONGRATULATIONS #\n',
		           	sound: "Pop"
		       	}));
		}
	/* Open Production Mode URL  E */
/* Gulpfile Production E */


/* Development and Production Mode S */
	var developmentTaskList = gulp.series(scss, copy);
	//var developmentTaskList = gulp.series(scss, htmlb, copy);
	
	var development = gulp.parallel(watch, developmentTaskList, developmentMainUrl);
	
	var production = gulp.series(clean, css, move, replacecss, replacejspath, userefCssJs, htmlm, jscompress, librariesRemove, movejscompressjs, jscompressjsRemove, productionMainUrl);
	//var production = gulp.series(clean, css, move, replacecss, replacejspath, userefCssJs, htmlm, jscompress, librariesRemove, movejscompressjs, jscompressjsRemove, upload, productionMainUrl);

	module.exports = {
	  	scss: scss,
	  	purify: purify,
		htmlb: htmlb,
		copy: copy,

		clean: clean,
		css: css,
		move: move,
		replacecss: replacecss,
		replacejspath: replacejspath,
		userefCssJs: userefCssJs,
		htmlm: htmlm,
		jscompress: jscompress,
		librariesRemove: librariesRemove,
		movejscompressjs: movejscompressjs,
		jscompressjsRemove: jscompressjsRemove,
		upload: upload,
		
		watch: watch,

		developmentTaskList: developmentTaskList,
		development: development,
		
		production: production,
		productionMainUrl: productionMainUrl,

		default: development,
	};
/* Development and Production Mode E */