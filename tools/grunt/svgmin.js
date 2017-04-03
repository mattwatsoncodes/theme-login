// SVG Min Task - https://github.com/sindresorhus/grunt-svgmin
// ----------------------------------------------------------------------------
module.exports = {
    options: {
        plugins: [
        {
            removeTitle: true
        },
        {
            removeDimensions: true
        },
        {
            removeStyleElement: true
        },
        {
            sortAttrs: true
        }]
    },
    svg: {
        files: [{
            expand: true,
            cwd: '<%= siteInfo.assets_path_raw %>/<%= siteInfo.img_dir %>',
            src: ['**/*.svg'],
            dest: '<%= siteInfo.assets_path %>/<%= pluginInfo.img_dir %>'
        }]
    }
};
