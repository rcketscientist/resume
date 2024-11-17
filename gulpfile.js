const gulp = require("gulp");
const sass = require('gulp-sass')(require('sass'));

gulp.task('sass', function () {
    return gulp.src('*.scss')
      .pipe(sass().on('error', sass.logError))
      .pipe(gulp.dest('.'));
});

gulp.task('watch', function () {
    return gulp.watch('*.scss', gulp.series('sass'));
});
