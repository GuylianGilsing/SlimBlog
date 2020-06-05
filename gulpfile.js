let gulp = require('gulp');
let sass = require('gulp-sass');
let rename = require('gulp-rename');

sass.compiler = require('node-sass');

gulp.task('sass', () => {
    return gulp.src('./resources/scss/*.scss')
            .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
            .pipe(rename('app.min.css'))
            .pipe(gulp.dest('./public/css'));
});

gulp.task('sass:watch', function(){
    gulp.watch('./resources/scss/**/*.scss', gulp.series(['sass']));
});