let Encore = require('@symfony/webpack-encore');
const path = require('path');
const glob = require('glob');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminWebpWebpackPlugin = require("imagemin-webp-webpack-plugin");
const CompressionPlugin = require('compression-webpack-plugin');
const PurgecssPlugin = require('purgecss-webpack-plugin');

const PATHS = {
    src: path.join(__dirname, 'templates')
};

Encore
// the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    // the public path you will use in Symfony's asset() function - e.g. asset('build/some_file.js')
    .setManifestKeyPrefix('build/')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    // the following line enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('email', './assets/scss/email.scss')
    .splitEntryChunks()
    //.addStyleEntry('css/app', './assets/css/app.scss')

    .enableSingleRuntimeChunk()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    .enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
    .enableVersioning()

    .addPlugin(new CopyWebpackPlugin([
        { from: './assets/static', to: 'static' }
    ]))

    .addPlugin(new PurgecssPlugin({
        paths: glob.sync(`${PATHS.src}/**/*`,  { nodir: true }),
        whitelist: ['hiddendiv', 'show-on-small', 'prefix', 'success', 'error'],
        whitelistPatternsChildren: [/select*/, /dropdown*/, /textarea*/, /btn-*/, /toast*/, /form*/]
    }))

    .addPlugin(new CompressionPlugin())

    .addPlugin(new ImageminWebpWebpackPlugin())
;

if (!Encore.isProduction()) {
    Encore.addPlugin(new CopyWebpackPlugin([
        { from: './assets/fixtures/.', to: '../' }
    ]))
}

const config = Encore.getWebpackConfig();
config.watchOptions = {
    poll: true,
};


module.exports = config;
