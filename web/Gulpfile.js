'use strict';

var gulp = require('gulp'),
  sourcemaps = require('gulp-sourcemaps'),
  concat = require('gulp-concat'),
  sass = require('gulp-sass'),
  autoprefixer = require('gulp-autoprefixer');

var DIR = {
  'src': './integration',
  'dest': './dist'
};

/**
 * @task styles
 * Compile sass/scss to unique css file
 */
gulp.task('styles', function () {
  gulp.src(DIR.src + '/scss/**/*.+(scss|sass)')
  .pipe(sourcemaps.init())
  .pipe(sass({
    outputStyle: 'expanded'
  }).on('error', sass.logError))
  .pipe(autoprefixer({
    browsers: ['last 4 versions'],
    cascade: false
  }))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(DIR.dest + '/css/'));
});

/**
 * @task scripts
 * Compile js scripts to unique js file
 */
gulp.task('scripts', function () {
  gulp.src([
    DIR.src + '/js/**/*.js'
  ])
  .pipe(sourcemaps.init())
  .pipe(concat('main.js'))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(DIR.dest + '/js/'));
});

/**
 * @task watch
 * Compile/watch app OTF (dev)
 */
gulp.task('watch', function () {
  gulp.watch(DIR.src + '/scss/**/*.scss', ['styles']);
  gulp.watch(DIR.src + '/js/**/*.js', ['scripts']);
});

/**
 * @task dist
 * Compile entire web app
 */
gulp.task('dist', ['styles', 'scripts'], function () {
  return true;
});

/**
 * @task default
 * Compile/watch app OTF (dev)
 */
gulp.task('default', ['watch'], function () {
  return true;
});