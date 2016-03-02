var gulp = require('gulp');
var gulpLoadPlugins = require('gulp-load-plugins');

var path = require('path');
var runSequence = require('run-sequence');

var exec = require('child_process').exec;
var requireClean = require('require-clean');

var browserSync = require('browser-sync').create();

var bowerInstaller;

// Gulp plugins
var plugins = gulpLoadPlugins();




// Directories
var dir =
{
	config: 		'./config/',

	pages: 			'./',

	img: 			'images/',

	less: 			'./less/',

	css: 			'./css/',

	js: 			'js/',
	jsPlugins:  	'js/plugins/',
	jsLibs:  		'js/lib/',

	fonts: 				'fonts/',
	fontsSVG: 			'fonts/svg/',
	fontsSVGOriginals: 	'fonts/svg_originals/',

	bower: 			'bower_components/',
	nodeModules: 	'node_modules/'
};


// Files
var files =
{
	pages: 					dir.pages + 		'*.html',

	img: 					dir.img + 			'**/*',
	imgPNG:					dir.img + 			'**/*.png',

	less: 					dir.less + 			'**/*.less',
	lessCompile: 			dir.less + 			'bootstrap.less',
	lessIconfont:			dir.less + 			'_iconfont.less',
	lessIconfontTpl:		dir.less + 			'_iconfont.tpl.less',

	css: 					dir.css + 			'*.css',
	cssCompile: 			dir.css + 			'compile.css',

	js: 					dir.js +  			'*.js',
	jsLibs: 				dir.jsLibs + 		'*.js',
	jsPlugins: 				dir.js + 			'jquery.plugins.min.js',

	svgIcons: 				dir.fontsSVG + 		'*.svg',

	jsFilesConfig: 			dir.config + 		'js_files.json'
};


// Config files
// var jsFilesConfig = require(files.jsFilesConfig);



// minifyCSS config
var minCSSConfig = 
{
	compatibility: 'ie8,' +
		'-units.ch,' +
		'-units.in,' +
		'-units.pc,' +
		'-units.pt,' +
		'-units.rem,' +
		'-units.vh,' +
		'-units.vm,' +
		'-units.vmax,' +
		'-units.vmin'
}


/**
 * Task : default
 * 		- BrowserSync
 *******************************************/
gulp.task('default', ['browserSync'], function()
{
});


/**
 * Task : Reset
 * 		- Delete install files
 *******************************************/
gulp.task('reset', ['jsFilesClean'], function()
{
	var sources = [dir.bower];

	return gulp
		.src(sources, {read: false})
		.pipe(plugins.rimraf());
});


/**
 * Task : init
 * 		- Delete install files
 * 		- Update index
 *		- Install Bower dependencies
 *		- Move JS libs and plugins
 * 		- Concat JS plugins
 *******************************************/
gulp.task('init', ['reset'], function()
{
	 runSequence(['bower'], ['jsLibs', 'jsPlugins'], 'jsPluginsCompile');
});



/**
 * Task : bower
 *		- Install Bower dependencies
 *******************************************/
gulp.task('bower', function(cb)
{
	return plugins.bower();
});



/**
 * Task : jsFilesClean
 * 		- Clean JS files
 *******************************************/
gulp.task('jsFilesClean', function()
{
	// Config file without cache
	jsFilesConfig = requireClean(files.jsFilesConfig);

	return gulp
		.src(jsFilesConfig.clean, {read: false})
	    .pipe(plugins.rimraf());
});


/**
 * Task : jsLibs
 *		- Get and move JS libs
 *		- Create a minified version
 *******************************************/
gulp.task('jsLibs', function()
{
	return gulp.src(jsFilesConfig.libs)
		.pipe(gulp.dest(dir.jsLibs))
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(dir.jsLibs));
});


/**
 * Task : jsPlugins
 *		- Get and move JS Plugins
 *		- Create a minified version
 *******************************************/
gulp.task('jsPlugins', function()
{
	return gulp.src(jsFilesConfig.plugins)
		.pipe(gulp.dest(dir.jsPlugins))
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(dir.jsPlugins));
});


/**
 * Task : jsPluginsCompile
 * 		- Concat JS Plugins
 *		- Create a minified version
 *******************************************/
gulp.task('jsPluginsCompile', function()
{
	return gulp
		.src([dir.jsPlugins+'*.js', '!'+dir.jsPlugins+'*.min.js'])
		.pipe(plugins.concat('jquery.plugins.js', {newLine: ';\n\n'}))
		.pipe(gulp.dest(dir.js))
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(dir.js));
});

/**
 * Tâche : jsComponentsCompile
 * 		- Concaténation des comosants JS maison
 * 		- Minification des JS
 *******************************************/
gulp.task('jsComponentsCompile', function()
{
	return gulp
		.src(jsFilesConfig.main)
		.pipe(plugins.concat('main.js', {newLine: ';\n\n'}))
		.pipe(gulp.dest(dir.js))
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(dir.js));
});



/**
 * Task : browserSync
 * 		- Synchronize all browsers and devices
 *******************************************/
gulp.task('browserSync', ['less'], function()
{
	browserSync.init({proxy: 'localhost:8888'});

	// less Compilation
	gulp.watch(files.less, ['less']);

	// Pages auto refresh
	gulp.watch([files.pages]).on('change', browserSync.reload);
});

/**
 * Task : less
 * 		- Less compilation (+ error notification)
 * 		- CSS3 autoprefixing
 * 		- CSS minification
 *******************************************/
gulp.task('less', function()
{
	return gulp
		.src(files.lessCompile)
		.pipe(plugins.plumber({errorHandler: plugins.notify.onError("Error: <%= error.message %>") }))
		.pipe(plugins.less())
		.pipe(plugins.autoprefixer(
		{
			browsers: ['last 2 versions', 'IE 9'],
		}))
		.pipe(gulp.dest(dir.css))
		.pipe(browserSync.stream())
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.minifyCss(minCSSConfig))
		.pipe(gulp.dest(dir.css))
		.pipe(browserSync.stream());
});


/**
 * Task : sass
 * 		- SASS compilation (+ error notification)
 * 		- CSS3 autoprefixing
 * 		- CSS minification
 *******************************************/
gulp.task('sass', function()
{
	return gulp
		.src(files.sassCompile)
		.pipe(plugins.plumber({errorHandler: plugins.notify.onError("Error: <%= error.message %>") }))
		.pipe(plugins.sass({errLogToConsole: true}).on('error', plugins.sass.logError))
		.pipe(plugins.autoprefixer(
		{
			browsers: ['last 2 versions', 'IE 9'],
		}))
		.pipe(gulp.dest(dir.css))
		.pipe(browserSync.stream())
		.pipe(plugins.rename({suffix: '.min'}))
		.pipe(plugins.minifyCss(minCSSConfig))
		.pipe(gulp.dest(dir.css))
		.pipe(browserSync.stream());
});


/**
 * Task : iconfont
 * 		- SVG to Font conversion
 *		- SASS classes generation
 *******************************************/
gulp.task('iconfont', function()
{
	return gulp
		.src(files.svgIcons)
		
		.pipe(plugins.iconfont(
		{
			fontName: 'iconfont',
			formats: ['ttf', 'eot', 'woff', 'svg'],
			appendUnicode: true,
			normalize: true
		}))

		.on('glyphs', function(glyphs, options)
		{
			// SASS classes
			gulp.src(files.sassIconfontTpl)
				.pipe(plugins.consolidate('lodash',
				{
					glyphs: glyphs,
					fontName: 'iconfont',
					fontPath: dir.fonts,
					className: 'icon'
				}))
				.pipe(plugins.rename('_iconfont.scss'))
				.pipe(gulp.dest(dir.sass));
		})

		.pipe(gulp.dest(dir.fonts));
});

/**
 * Task : zip
 * 		- Site zip export
 *******************************************/
gulp.task('zip', function()
{
	var d = new Date;

	var month = (d.getMonth() < 10) ? "0" + (d.getMonth() + 1) : (d.getMonth() + 1);
	var day = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
	var hours = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
	var mins = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();

	var date = d.getFullYear() + "-" + month + "-" + day + "-" + hours + "h" + mins;

	var zipFiles =
	[
		'**/*',
		'!*.zip',
		'!*.json',
		'!'+dir.bower+'/*',
		'!'+dir.nodeModules+'/*',
		'!'+dir.config+'/*',
		'!'+dir.fontsSVG+'/*',
		'!'+dir.fontsSVGOriginals+'/*'
	];

	return gulp
		.src(zipFiles)
		.pipe(plugins.zip('poc-'+date+'.zip'))
		.pipe(gulp.dest('.'));
});