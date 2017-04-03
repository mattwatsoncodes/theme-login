// Sass Lint Task - https://github.com/sasstools/grunt-sass-lint
// ----------------------------------------------------------------------------
module.exports = {
	options: {
		configFile: 'config/.sasslint.yml',
		formatter: 'stylish',
		outputFile: '<%= siteInfo.reports_path %>/sasslint.xml'
	},
	target: ['<%= siteInfo.assets_path_raw %>/<%= siteInfo.sass_dir %>/**/*.scss']
};

