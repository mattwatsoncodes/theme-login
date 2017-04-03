// Sync Task - https://github.com/tomusdrw/grunt-sync
// ----------------------------------------------------------------------------
module.exports = {
	// Sync local or Bower assets into the
	// theme at the start of each re-build.
	// -------------------------------------
	sync_assets: {
		files: '<%= syncAssets %>',
	},
}
