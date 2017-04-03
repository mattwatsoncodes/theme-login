// Uglify Task - https://github.com/gruntjs/grunt-contrib-uglify
// ----------------------------------------------------------------------------
module.exports = {
	// Uglify all of our JS assets.
	// -------------------------------------
	options: {
		banner: '/*! <%= package.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n',
		// Turning off mangling keeps the original
		// code intact, reducing errors.
		// -------------------------------------
		mangle: false,
		// Generate a sourcemap for each
		// Javascript file.
		// -------------------------------------
		sourceMap: true
	},
	// Public JS.
	// -------------------------------------
	public: {
		files: {
			'<%= siteInfo.assets_path %>/<%= pluginInfo.js_dir %>/plugin.min.js': [ '<%= concat.public.dest %>' ]
		}
	},
	// Admin JS.
	// -------------------------------------
	admin: {
		files: {
			'<%= siteInfo.assets_path %>/<%= pluginInfo.js_dir %>/plugin-admin.min.js': [ '<%= concat.admin.dest %>' ]
		}
	},
	// Customizer JS.
 	// -------------------------------------
	customizer: {
		files: {
			'<%= siteInfo.assets_path %>/<%= pluginInfo.js_dir %>/customizer.min.js': [ '<%= concat.customizer.dest %>' ]
		}
	},

};
