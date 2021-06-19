var webpack = require('webpack');
var path = require('path');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");

var BUILD_DIR = path.resolve(__dirname, 'public/assets/js');
var APP_DIR = path.resolve(__dirname, 'resources/scripts/watched/');

var config = {
  watch: true,
  entry: {
    index: APP_DIR + '/index.js',
    script: APP_DIR + '/script.js'
  },
  output: {
    path: BUILD_DIR,
    filename: '[name].min.js'
  },
  module : {
    rules : [
      {
        test : /\.jsx?/,
        include : APP_DIR,
        loader : 'babel-loader'
      }
    ]
  },
};

module.exports = config;
