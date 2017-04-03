// Concat Task - https://github.com/gruntjs/grunt-contrib-concat
// ----------------------------------------------------------------------------
module.exports = {
	options: {
		separator: '\r\n\r\n',
	},
	// Public JS.
	// -------------------------------------
	public: {
		src: ['<%= concatPublic %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/plugin.js',
		nonull: true
	},
	public_min: {
		src: ['<%= concatPublic %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/plugin.tmp.js',
		nonull: true
	},
	// Admin JS.
	// -------------------------------------
	admin: {
		src: ['<%= concatAdmin %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/plugin-admin.js',
		nonull: true
	},
	admin_min: {
		src: ['<%= concatAdmin %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/plugin-admin.tmp.js',
		nonull: true
	},
	// Customizer JS.
	// -------------------------------------
	customizer: {
		src: ['<%= concatCustomizer %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/customizer.js',
		nonull: true
	},
	customizer_min: {
		src: ['<%= concatCustomizer %>'],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.js_dir %>/customizer.tmp.js',
		nonull: true
	}
};
