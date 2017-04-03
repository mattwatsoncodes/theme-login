// Grunticon Task - https://github.com/filamentgroup/grunticon
// ----------------------------------------------------------------------------
module.exports = {
	icons: {
		files: [{
			expand: true,
			cwd: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.icons_dir %>/',
			src: ['*.svg', '*.png'],
			dest: '<%= siteInfo.assets_path %>/<%= pluginInfo.icons_dir %>',
		}],
		options: {
			enhanceSVG: true,
			compressPNG: true,
			loadersnippet: 'grunticon-loader.js',
			pngpath: 'assets'
		}
	}
}
