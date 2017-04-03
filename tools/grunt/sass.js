// Sass Task - https://github.com/sindresorhus/grunt-sass
// ----------------------------------------------------------------------------
module.exports = {
	// Generate CSS from our Sass files.
	// -------------------------------------
	sass: {
		options: {
			sourceMap: true,
			sourceMapContents: true,
			sourceMapRoot: 'assets/css',
			style: 'compressed'
		},
		files: [ {
			expand: true,
			cwd: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.sass_dir %>',
			src: [ '*.scss' ],
			dest: '<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>',
			ext: '.css'
		} ]
	}
};
