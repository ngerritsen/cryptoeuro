'use strict';

const webpack = require('webpack');
const path = require('path');

const config = {
  entry: [
    'whatwg-fetch',
    'es6-promise',
    path.resolve('./client/index.js')
  ],
  output: {
    filename: 'main.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        use: ['babel-loader'],
        exclude: /node_modules/
      }
    ]
  }
};

if (process.env.NODE_ENV === 'production') {
  config.plugins = [
    new webpack.optimize.UglifyJsPlugin()
  ];
} else {
  config.devtool = 'inline-source-map';
}

module.exports = config;
