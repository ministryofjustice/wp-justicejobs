const mix = require('laravel-mix');
const dist = 'dist/';

require('laravel-mix-imagemin');
mix.setPublicPath('./dist/');

mix.js([
        'src/js/accessible-carousel.js',
        'src/js/modal.js',
        'src/js/job-search-results.js',
        'src/js/job-search-form.js',
        'src/js/map.js',
        'src/js/main.js'
    ], dist + 'js/main.min.js')
    .js('src/js/jj-gtm.js', dist + 'js/jj-gtm.min.js')
    .sass('src/scss/style.scss', dist + 'css/main.min.css')
    .sass('src/scss/errors/404.sass', dist + 'css/404.css')
    .sass('src/scss/errors/error-page.sass', dist + 'css/error-page.css')
    .copy('src/video/*', dist + 'video')
    .copy('src/fonts/*', dist + 'fonts')
    .copy('node_modules/jquery/dist/jquery.min.js', dist + 'js/')
    .copy('node_modules/rellax/rellax.min.js', dist + 'js/')
    .imagemin([
            'img/**.*',
            'img/icons/**.*'
        ],
        {context: './src/'},
        {
            optipng: {optimizationLevel: 5},
            jpegtran: null,
            plugins: [
                require('imagemin-mozjpeg')({
                    quality: 82,
                    progressive: true,
                })
            ]
        }
    )
    .copy('src/img/*.svg', dist + 'img')
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version()
} else {
    mix.sourceMaps()
}
