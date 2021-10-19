const path = require('path');
 
var webpack = require('webpack');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    mode: "development",
    context: path.resolve(__dirname, "assets"),
    output: {
        filename: 'main.bundle.js',
        path: path.resolve(__dirname,"assets/dist")
    },
    plugins: [
        new MiniCssExtractPlugin(),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        }),
    ],
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, "css-loader"],
            }
        ]
    }
}