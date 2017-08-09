var gulp = require('gulp');
var stylus = require('gulp-stylus');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('build', function () {
    return gulp.src('./css/style.styl')
    .pipe(sourcemaps.init())
    .pipe(stylus({
        compress: true,
        'include css': true
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./css/'));
});

gulp.task('watch', function() {
    gulp.watch('**/*.styl', ['build'])
});

gulp.task('default', ['build', 'watch']);
