var gulp = require("gulp");
var sass = require("gulp-sass");

gulp.task('sass', function () {
    return gulp.src('*.scss')
      .pipe(sass().on('error', sass.logError))
      .pipe(gulp.dest('.'));
});

gulp.task('watch', function () {
    return gulp.watch('*.scss', gulp.series('sass'));
});
