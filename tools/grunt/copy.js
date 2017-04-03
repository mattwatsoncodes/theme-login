// Copy Task - https://github.com/gruntjs/grunt-contrib-copy
// ----------------------------------------------------------------------------
module.exports = {
	sourcemaps: {
		expand: true,
		cwd: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/',
		src: [ '*.map' ],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>'
	},
	unminified_css: {
		expand: true,
		cwd: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/',
		src: [ '*.css', '!*.min.css', '!*.css.map' ],
		dest: '<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>'
	},
	grunticon: {
		files: [
			{
				src: ['<%= siteInfo.assets_path %>/<%= pluginInfo.icons_dir %>/grunticon-loader.js'],
				dest: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.js_dir %>/lib/_grunticon-loader.js'
			}
		]
	}
};
