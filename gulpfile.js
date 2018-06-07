var gulp = require("gulp");
var sass = require("gulp-sass");
var autoprefixer = require("gulp-autoprefixer");
// var log = require('fancy-log')

gulp.task('sass', function () {
    return gulp.src('resume.scss')
      .pipe(sass().on('error', sass.logError))
      .pipe(autoprefixer("last 20 version", "safari 5", "ie 8", "ie 9"))
      .pipe(gulp.dest('.'));
});

gulp.task('watch', function () {
    gulp.watch('resume.scss', ['sass']);
});

// function sass() {
//     // log("Generating css");
  
//     gulp.src('./*.scss')
//         .pipe(sass().on('error', sass.logError))
//         .pipe(autoprefixer("last 3 version","safari 5", "ie 8", "ie 9"))
//         // .pipe(concat('resume.css'))
//         .pipe(gulp.dest("./css"))
// }

// function watch() {
//     // log("Watching scss files for modifications");
//     gulp.watch('*.scss', sass);
//   }

// exports.sass = sass;
// exports.watch = watch;

// gulp.task("build", [sass]);
// gulp.task('default', build);

