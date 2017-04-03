// Notify Task - https://github.com/dylang/grunt-notify
// ----------------------------------------------------------------------------
module.exports = {
	// Notify once the project is rebuilt.
	// -------------------------------------
	build: {
		options: {
			title: 'Rebuild',
			message: '<%= siteInfo.fancy_name %> is ready to rock!'
		}
	},
	// Notify once plugins are linted.
	// -------------------------------------
	code_plugins: {
		options: {
			title: 'PHP',
			message: '<%= siteInfo.fancy_name %> plugin PHP is error free!'
		}
	},
	// Notify once theme is linted.
	// -------------------------------------
	plugin: {
		options: {
			title: 'PHP',
			message: '<%= siteInfo.fancy_name %> theme PHP is error free!'
		}
	},
	// Notify once scripts are concatenated
	// and uglified.
	// -------------------------------------
	scripts: {
		options: {
			title: 'Scripts',
			message: '<%= siteInfo.fancy_name %> scripts processed!'
		}
	},
	// Notify once styles are processed and
	// minified.
	// -------------------------------------
	styles: {
		options: {
			title: 'Styles',
			message: '<%= siteInfo.fancy_name %> styles processed!'
		}
	},
	// Notify once images are minified.
	// -------------------------------------
	images: {
		options: {
			title: 'Images',
			message: '<%= siteInfo.fancy_name %> images processed!'
		}
	},
	// Notify once all documentation has
	// been generated.
	// -------------------------------------
	docs: {
		options: {
			title: 'Docs',
			message: '<%= siteInfo.fancy_name %> docs generated!'
		}
	},
	// Notify once all code has been linted.
	// -------------------------------------
	linting: {
		options: {
			title: 'Linting',
			message: '<%= siteInfo.fancy_name %> files linted!'
		}
	},
	// Notify once all images have been
	// minified.
	// -------------------------------------
	images: {
		options: {
			title: 'Images',
			message: '<%= siteInfo.fancy_name %> images minified!'
		}
	}
};
