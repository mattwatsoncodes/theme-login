// Parker Task - Analyse CSS - https://github.com/leny/grunt-parker
// ----------------------------------------------------------------------------
module.exports = {
	options: {
		file: 'reports/parker.md',
		title: '<%= siteInfo.fancy_name %> Parker Report'
	},
	css: {
		src: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.css_dir %>/<%= siteInfo.sass_file %>.css'
	},
};
