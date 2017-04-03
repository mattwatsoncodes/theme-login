// Max Filesize Task - https://github.com/alexcorre/grunt-max-filesize
// ----------------------------------------------------------------------------
module.exports = {
	css: {
		options: {
			// Anything over this figure will cause
			// issues in legacy (< 9) IE.
			//  -------------------------------------
			maxBytes: 288000
		},
		src: [
			'<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>/plugin.css',
			'<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>/plugin-admin.css',
			'<%= siteInfo.assets_path %>/<%= siteInfo.css_dir %>/plugin-admin-editor.css',
		]
	}
}
