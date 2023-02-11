let mix = require('laravel-mix');
const purgeCss = require('@fullhuman/postcss-purgecss');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/themes/' + directory;
const dist = 'public/themes/' + directory;

mix.js(source + '/assets/js/project.js', dist + '/js').vue({ version: 2 });
mix.js(source + '/assets/js/events.js', dist + '/js').vue({ version: 2 });
mix.js(source + '/assets/js/dashboard.js', dist + '/js').vue({ version: 2 });
mix.js(source + '/assets/js/account.js', dist + '/js').vue({ version: 2 });

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .js(source + '/assets/js/app.js', dist + '/js')
    .js(source + '/assets/js/script.js', dist + '/js')

    .copyDirectory(dist + '/css', source + '/public/css')
    .copyDirectory(dist + '/js', source + '/public/js');
