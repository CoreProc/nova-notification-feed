let mix = require('laravel-mix')

mix.setPublicPath('dist')
  .js('resources/js/notification_feed.js', 'js')
