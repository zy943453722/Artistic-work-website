/**
 * @description vue configuration
 */

module.exports = {
    devServer: {
        proxy: {
            '/api': {
                target: 'http://api.artworks.com',
                changeOrigin: true,
                pathRewrite: {
                    '^/api': ''
                }
            }
        }
    },
    configureWebpack: {
        devtool: 'source-map'
    }
}