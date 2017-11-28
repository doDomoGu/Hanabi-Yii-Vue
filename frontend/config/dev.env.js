'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  AXIOS_BASEURL: '"http://api.localhanabi-yii.com/v1"',

})
