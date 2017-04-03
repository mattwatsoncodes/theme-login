// SassDoc Task - https://github.com/SassDoc/grunt-sassdoc
// ----------------------------------------------------------------------------
module.exports = {
	// Generate documentation for our Sass.
	// -------------------------------------
	options: {
		dest: '<%= siteInfo.docs_path %>/sass'
	},
	sass: {
		src: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.sass_dir %>',
	}
};
