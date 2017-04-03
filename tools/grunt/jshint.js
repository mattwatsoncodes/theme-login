// JSHint Task - https://github.com/gruntjs/grunt-contrib-jshint
// ----------------------------------------------------------------------------
module.exports = {
	options: {
		// Import our JSHint config options.
		// -------------------------------------
		jshintrc: 'grunt/config/jshintrc.json',
		// Output the results to file.
		// -------------------------------------
		reporterOutput: '<%= siteInfo.reports_path %>/jshint.txt',
		reporter: require( 'jshint-stylish' ),
	},
	// Lint our Javascript.
	// -------------------------------------
	scripts: {
		options: {
			// Continue the build regardless of
			// JSHint errors.
			// -------------------------------------
			force: true
		},
		src: [
			'<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/**/*.js',
			'!<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/lib/modernizr-custom.js'
		]
	}
};
