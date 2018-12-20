const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const rootPath = process.cwd()

module.exports = {
  entry: {
    // Plugin scripts
    'public': "./resources/assets/scripts/public.js",
    'acf': "./resources/assets/scripts/acf.js",
    'snipcart': "./resources/assets/scripts/snipcart.js",
    // Widget scripts
    'widgets-admin': "./resources/assets/scripts/widgets-admin.js",
    'widgets-public': "./resources/assets/scripts/widgets-public.js",
  },
  externals: {
    jquery: 'jQuery'
  },
  module: {
    rules:[
      {
        enforce: 'pre',
        test: /\.js$/,
        loader: "eslint-loader",
        exclude: /node_modules/,
      },
      {
        test: /\.(s*)css$/,
        use: [
          MiniCssExtractPlugin.loader, // Puts styles in separate files
          "css-loader", // translates CSS into CommonJS
          "sass-loader", // compiles Sass to CSS
        ],
      },
    ],
  },
  output: {
    filename: '[name].js',
    path: path.resolve(rootPath, 'dist'),
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "[id].css",
    }),
    new webpack.EnvironmentPlugin({GOOGLE_TRACKING_ID: 'TRACKING_ID_PLACEHOLDER'}),
  ],
};
