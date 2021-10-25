const path =                        require('path');
const webpack =                     require('webpack');
const MiniCssExtractPlugin =        require("mini-css-extract-plugin");
const ExtractTextPlugin =           require('extract-text-webpack-plugin');
const PurifyCSSPlugin =             require('purifycss-webpack');


module.exports = {
    context: path.resolve(__dirname, "assets"),
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