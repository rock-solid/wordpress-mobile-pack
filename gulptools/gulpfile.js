/**
 *
 * Usage:
 * For all apps: "gulp compile-default-theme --theme=2" -> compile css
 *
 */
var gulp = require('gulp'),
  minify = require('gulp-minify');
/**
 *
 * Minify admin JS files
 *
 */
gulp.task('default', function () {
  gulp
    .src(['./../admin/js/**/*.js', '!./../admin/js/**/*.min.js'])
    .pipe(
      minify({
        ext: {
          min: '.min.js'
        },
        noSource: true
      })
    )
    .pipe(gulp.dest('./../admin/js/'));
});
