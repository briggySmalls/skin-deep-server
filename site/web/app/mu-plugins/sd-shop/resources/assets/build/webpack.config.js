const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const rootPath = process.cwd()

module.exports = {
  entry: {
    // Donation widget
    'donation/widget': "./resources/assets/scripts/donation/widget.js",
    'donation/admin': "./resources/assets/scripts/donation/admin.js",
  },
  module: {
    rules:[
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
