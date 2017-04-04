// CSSMin Task - https://github.com/gruntjs/grunt-contrib-cssmin
// ----------------------------------------------------------------------------
module.exports = {
	// All Styles.
	// -------------------------------------
	all: {
		files: [ {
			expand: true,
			cwd: '<%= siteInfo.assets_path %>/<%= pluginInfo.css_dir %>',
			src: ['*.css'],
			dest: '<%= siteInfo.assets_path %>/<%= pluginInfo.css_dir %>',
			ext: '.min.css'
		} ]
	}
};
