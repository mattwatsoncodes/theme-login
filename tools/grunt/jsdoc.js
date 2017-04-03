// JSDoc Task - https://github.com/krampstudio/grunt-jsdoc
// ----------------------------------------------------------------------------
module.exports = {
	// Generate documentation for our JS.
	// -------------------------------------
	jsdoc: {
		src: [ '<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/**/*.js' ],
		dest: '<%= siteInfo.docs_path %>/js'
	}
};
