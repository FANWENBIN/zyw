var gulp = require("gulp");
var gutil = require("gulp-util");//工具：颜色
var uglify = require("gulp-uglify");//压缩js
var minifycss = require('gulp-minify-css') //压缩css
var watchPath = require("gulp-watch-path");//解决侦测具体哪个文件变动
var combiner = require('stream-combiner2');//解决报错
var sourcemaps = require('gulp-sourcemaps'); //压缩文件错误调试
var autoprefixer = require("gulp-autoprefixer");//前缀处理
var sass = require("gulp-ruby-sass");//sass编译



//报错信息处理
var handleError = function (err) {
    var colors = gutil.colors;
    console.log('\n')
    gutil.log(colors.red('Error!'))
    gutil.log('fileName: ' + colors.red(err.fileName))
    gutil.log('lineNumber: ' + colors.red(err.lineNumber))
    gutil.log('message: ' + err.message)
    gutil.log('plugin: ' + colors.yellow(err.plugin))
}

//监控 压缩js
gulp.task('watchjs', function () {
    gulp.watch('src/js/**/*.js', function (event) {
        var paths = watchPath(event, 'src/', 'dist/')
        /*
        paths
            { srcPath: 'src/js/log.js',
              srcDir: 'src/js/',
              distPath: 'dist/js/log.js',
              distDir: 'dist/js/',
              srcFilename: 'log.js',
              distFilename: 'log.js' }
        */
        gutil.log(gutil.colors.green(event.type) + ' ' + paths.srcPath)
        gutil.log('Dist ' + paths.distPath)

        var combined = combiner.obj([
          gulp.src(paths.srcPath),
          uglify(),
          sourcemaps.write('./'),
          gulp.dest(paths.distDir)
        ])
        combined.on("error",handleError)
    })
})

//手动压缩js
gulp.task('uglifyjs', function () {
    var combined = combiner.obj([
        gulp.src('src/js/**/*.js'),
        sourcemaps.init(),
        uglify(),
        sourcemaps.write('./'),
        gulp.dest('dist/js/')
    ])
    combined.on('error', handleError)
})


//监控 压缩css 添加前缀
gulp.task('watchcss', function () {
    gulp.watch('src/css/**/*.css', function (event) {
        var paths = watchPath(event, 'src/', 'dist/')

        gutil.log(gutil.colors.green(event.type) + ' ' + paths.srcPath)
        gutil.log('Dist ' + paths.distPath)

        gulp.src(paths.srcPath)
            .pipe(sourcemaps.init())
            .pipe(autoprefixer({
              browsers: "last 2 versions"
            }))
            .pipe(minifycss())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(paths.distDir))
    })
})
//手动压缩css 添加前缀
gulp.task('minifycss', function () {
    gulp.src('src/css/**/*.css')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer({
          browsers: 'last 2 versions'
        }))
        .pipe(minifycss())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('dist/css/'))
})
//监控 编译sass 压缩css 添加前缀
gulp.task('watchsass',function () {
    gulp.watch('src/sass/**/*', function (event) {
        var paths = watchPath(event, 'src/sass/', 'dist/css/')

        gutil.log(gutil.colors.green(event.type) + ' ' + paths.srcPath)
        gutil.log('Dist ' + paths.distPath)
        sass(paths.srcPath)
            .on('error', function (err) {
                console.error('Error!', err.message);
            })
            .pipe(sourcemaps.init())
            .pipe(minifycss())
            .pipe(autoprefixer({
              browsers: 'last 2 versions'
            }))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(paths.distDir))
    })
})

//手动 编译sass 压缩css 添加前缀

gulp.task('sasscss', function () {
        sass('src/sass/')
        .on('error', function (err) {
            console.error('Error!', err.message);
        })
        .pipe(sourcemaps.init())
        .pipe(minifycss())
        .pipe(autoprefixer({
          browsers: 'last 2 versions'
        }))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('dist/css'))
})



//监控
gulp.task('default', ['watchjs', 'watchsass'])
//手动处理
gulp.task("init",["uglifyjs","sasscss"])
