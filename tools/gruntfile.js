module.exports = function(grunt) {
	require('load-grunt-config')(grunt, {
		init: true,
		jitGrunt: {
			jitGrunt: true,
			// -------------------------------------
			// These static mappings help Grunt play
			// nicely with certain plugins.
			// -------------------------------------
			staticMappings: {
				sasslint:    'grunt-sass-lint',
				sprite:      'grunt-spritesmith',
				maxfilesize: 'grunt-max-filesize',
			}
		},
		// -----------------------------------------------------------------------------
		// Anything you define within the main 'data' object can be accessed
		// both in the Gruntfile and in the individual task configurations e.g.
		// <%= pluginInfo.theme_name %>, <%= siteInfo.assets_path_raw %> etc.
		// -----------------------------------------------------------------------------
		data: {
			// -------------------------------------
			// Project specific settings.
			// -------------------------------------
			siteInfo: {
				// -------------------------------------
				// The 'fancy' name for your project
				// e.g. 'My First Website'.
				// -------------------------------------
				fancy_name: 'Front End Login',

				// -------------------------------------
				// Documentation path relative to the
				// project root - NO trailing slash.
				// -------------------------------------
				docs_path: 'tools/docs',

				// -------------------------------------
				// Reports path relative to the project
				// root - NO trailing slash.
				// -------------------------------------
				reports_path: 'tools/reports',

				// -------------------------------------
				// Assets path relative to the project
				// root - NO trailing slash.
				// -------------------------------------
				assets_path_raw: 'assets/src',
				assets_path: 'assets',

				// -------------------------------------
				// Image assets directory.
				// -------------------------------------
				img_dir: 'img',

				// -------------------------------------
				// Javascript assets directory.
				// -------------------------------------
				js_dir: 'js',

				// -------------------------------------
				// Sass assets directory.
				// -------------------------------------
				sass_dir: 'sass',

				// -------------------------------------
				// CSS assets directory.
				// -------------------------------------
				css_dir: 'css',

				// -------------------------------------
				// Font assets directory.
				// -------------------------------------
				fonts_dir: 'fonts',

				// -------------------------------------
				// Icon assets directory
				// -------------------------------------
				icons_dir: 'ico',

				// -------------------------------------
				// Name of your main Sass file and
				// consequent CSS file.
				// -------------------------------------
				sass_file: 'plugin'
			},

			// -------------------------------------
			// Plugin specific settings
			// -------------------------------------
			pluginInfo: {
				// -------------------------------------
				// Theme assets directory.
				// -------------------------------------
				assets_dir: 'assets',

				// -------------------------------------
				// Theme images directory.
				// -------------------------------------
				img_dir: 'img',

				// -------------------------------------
				// Theme Javascript directory.
				// -------------------------------------
				js_dir: 'js',

				// -------------------------------------
				// Theme CSS directory.
				// -------------------------------------
				css_dir: 'css',

				// -------------------------------------
				// Theme fonts directory.
				// -------------------------------------
				fonts_dir: 'fonts',

				// -------------------------------------
				// Theme icons directory
				icons_dir: 'ico'
				// -------------------------------------
			},

			// -------------------------------------
			// Array of paths to Javascript files
			// for PUBLIC enqueues.
			// -------------------------------------
			concatPublic: [
				'<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/plugin.js',
				'<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/components/_alerts.js'
			],

			// -------------------------------------
			// Array of paths to Javascript files
			// for ADMIN enqueues.
			// -------------------------------------
			concatAdmin: [
				'<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/plugin-admin.js'
			],

			// -------------------------------------
			// Array of paths to Javascript files
			// for CUSTOMIZER enqueues.
			// -------------------------------------
			concatCustomizer: [
				'<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/customizer.js'
			],

			// -------------------------------------
			// Array of objects that have Bower `src`
			// and theme `dest` paths to facilitate
			// syncing of files and/or folders.
			//
			// There is no need for `bower_components`
			// in the `src` if you specify `cwd` in
			// the object. This is useful if you wish
			// to define multiple `src` paths.
			//
			// If you need to sync a directory in
			// its entirety, append `/**` to the
			// path to the source directory.
			//
			// Finally, remember the `dest` path is
			// relative to the project root, not the
			// `cwd` if specified.
			// -------------------------------------
			syncAssets: [
				// -------------------------------------
				// Example to use as basis for any new
				// Bower folder/file syncing.
				//
				// {
				//     cwd: 'bower_components',
				//     src: ['path/**'],
				//     dest: 'dest/'
				// }
				// -------------------------------------
				{
					src: ['<%= siteInfo.assets_path_raw %>/<%= siteInfo.fonts_dir %>/**'],
					dest: '<%= siteInfo.assets_path %>/<%= pluginInfo.fonts_dir %>/'
				}
			]
		}
	});
	// -----------------------------------------------------------------------------
	// Provides a summary of the time tasks have taken.
	// -----------------------------------------------------------------------------
	require('time-grunt')(grunt);

	grunt.file.setBase( '../' );

	// -----------------------------------------------------------------------------
	// Silences grunt-newer.
	// https://github.com/tschaub/grunt-newer/issues/52#issuecomment-59397284
	// -----------------------------------------------------------------------------
	var origLogHeader = grunt.log.header;
	grunt.log.header = function( msg ) {
		if ( !/newer(-postrun)?:/.test( msg ) ) {
			origLogHeader.apply( this, arguments );
		}
	};
};
