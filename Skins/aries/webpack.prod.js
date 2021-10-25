const path =                    require('path');
const config =                  require('./webpack.config');
const { merge } =               require('webpack-merge');
 
module.exports = merge(config, {
    mode: "production",
    optimization: {
        minimize: true
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, 'assets/dist')
    }
});