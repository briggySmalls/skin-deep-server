const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const rootPath = process.cwd()

module.exports = {
  entry: {
    // Preview widget
    'preview/widget': "./resources/assets/scripts/preview/widget.js",
    'preview/admin': "./resources/assets/scripts/preview/admin.js",
    // Slider widget
    'slider/widget': "./resources/assets/scripts/slider/widget.js",
    'slider/admin': "./resources/assets/scripts/slider/admin.js",
    // Donation widget
    'donation/widget': "./resources/assets/scripts/donation/widget.js",
    'donation/admin': "./resources/assets/scripts/donation/admin.js",
    // ACF scripts
    'acf': "./resources/assets/scripts/acf.js",
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
  ],
};
