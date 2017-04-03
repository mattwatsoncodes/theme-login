// PHPdoc Task - https://github.com/chrisklaussner/grunt-phpdoc
// ----------------------------------------------------------------------------
module.exports = {
	// Generate theme and plugin
	// documentation.
	// -------------------------------------
	all: {
		src: [
			'<%= wpPlugins %>',
			''
		],
		dest: '<%= siteInfo.docs_path %>/php'
	},
	// Generate theme documentation.
	// -------------------------------------
	theme: {
		src: [ '' ],
		dest: '<%= siteInfo.docs_path %>/php'
	},
	// Generate plugin documentation.
	// -------------------------------------
	plugins: {
		src: [
			'<%= pluginInfo.wp_content %>/plugins/index.php', // hack to ensure phpdoc doesn't fail if no plugins are specified
			'<%= wpPlugins %>'
		],
		dest: '<%= siteInfo.docs_path %>/php'
	}
}
