// PostCSS Task - https://github.com/nDmitry/grunt-postcss
// ----------------------------------------------------------------------------
module.exports = {
	// Run our CSS through pixrem and
	// autoprefixer.
	// -------------------------------------
	options: {
		processors: [
			require( 'autoprefixer' )( {
				browsers: [ '> 5%', 'last 2 versions' ]
			} ),
			require( 'pixrem' )()
		]
	},
	plugin: {
		src: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/<%= siteInfo.sass_file %>.css',
	},
	plugin_admin: {
		src: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/plugin-admin.css',
	},
	plugin_admin_editor: {
		src: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/plugin-admin-editor.css',
	}
}
