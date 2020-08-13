import {
  src,
  dest,
  watch,
  series,
  parallel
} from 'gulp'
import postcss from 'gulp-postcss'
import sourcemaps from 'gulp-sourcemaps'
import autoprefixer from 'autoprefixer'
import yargs from 'yargs'
import sass from 'gulp-sass'
import cleanCss from 'gulp-clean-css'
import gulpif from 'gulp-if'
import imagemin from 'gulp-imagemin'
import del from 'del'
import webpack from 'webpack-stream'
import wpPot from 'gulp-wp-pot'
import named from 'vinyl-named'
import browserSync from 'browser-sync'
import zip from 'gulp-zip'
import replace from 'gulp-replace'
import svgSprite from 'gulp-svg-sprite'
// import inlinesource from "gulp-inline-source";
import info from './package.json'
const PRODUCTION = yargs.argv.prod

// export const inline = () => {
//   return src("./header.php").pipe(inlinesource()).pipe(dest("./"));
// };

export const copy = () => {
  return src([
    'src/assets/**/*',
    '!src/assets/{images,svg}',
    // '!src/js',
    // '!src/sass'
  ]).pipe(dest('./dist/assets'))
}

export const clean = () => del(['dist', 'bundled'])

// sprite svg call manually
export const svg = () => {
  const config = {
    shape: {
      dimension: {
        // Set maximum dimensions
        maxWidth: 32,
        maxHeight: 32
      },

      dest: 'files/intermediate-svg' // Keep the intermediate files
    },
    mode: {
      view: {
        // Activate the «view» mode
        bust: false,
        render: {
          scss: true // Activate Sass output (with default options)
        }
      },

      symbol: {
        dest: '.',
        example: true,
        sprite: 'main.svg'
      },

      defs: true
    }
  }

  return src('**/*.svg', {
      cwd: 'src/svg'
    })
    .pipe(svgSprite(config))
    .pipe(dest('./dist/assets/svg'))
}

export const styles = () => {
  return (
    src(['src/sass/main.scss', 'src/sass/admin.scss'])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(PRODUCTION, postcss([autoprefixer])))

    // .pipe(gulpif(PRODUCTION, cleanCss({ compatibility: "ie9" })))
    .pipe(gulpif(PRODUCTION, cleanCss({
      compatibility: '*'
    })))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('./dist/css'))

    .pipe(server.stream())
  )
}

const server = browserSync.create()
export const serve = (done) => {
  server.init({
    proxy: {
      target: 'http://mystartertheme.test'
    },
    notify: false,
    scrollThrottle: 100
  })
  done()
}
export const reload = (done) => {
  server.reload()
  done()
}

export const scripts = () => {
  return src(['src/js/*.js'])
    .pipe(named())
    .pipe(
      webpack({
        module: {
          rules: [{
            test: /\.js$/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: []
              }
            }
          }]
        },
        mode: PRODUCTION ? 'production' : 'development',
        devtool: !PRODUCTION ? 'inline-source-map' : false,
        output: {
          filename: '[name].js'
        },
        externals: {
          jquery: 'jQuery'
        }
      })
    )
    .pipe(dest('dist/js'))
}

export const pot = () => {
  return src('**/*.php')
    .pipe(
      wpPot({
        domain: '_themename',
        package: info.name
      })
    )
    .pipe(dest(`languages/${info.name}.pot`))
}

export const images = () => {
  return src('src/assets/images/**/*.{jpg,jpeg,png,gif}')
    .pipe(gulpif(PRODUCTION, imagemin()))
    .pipe(dest('dist/assets/images'))
}

export const watchForChanges = () => {
  watch('src/sass/**/*.scss', styles)
  // watch('src/images/**/*', copy)
  watch('src/svg/*.svg', series(svg, reload))
  watch('src/assets/images/**/*.{jpg,jpeg,png,gif}', series(images, reload))
  // watch(
  //   ["src/**/*", "!src/{images,js,scss}", "!src/{images,js,scss}/**/*"],
  //   series(copy, reload)
  // );
  watch('src/js/**/*.js', series(scripts, reload))
  watch('**/*.php', reload)
}

/* Zip Theme when it is ready to live in the wild... */
export const compress = () => {
  return src([
      '**/*',
      '!node_modules{,/**}',
      '!bundled{,/**}',
      '!src{,/**}',
      '!.babelrc',
      '!.gitignore',
      '!gulpfile.babel.js',
      '!package.json',
      '!package-lock.json'
    ])
    .pipe(
      gulpif(
        (file) => file.relative.split('.').pop() !== 'zip',
        replace('_themename', info.name)
      )
    )
    .pipe(zip(`${info.name}.zip`))
    .pipe(dest('bundled'))
}

export const dev = series(
  clean,
  series(svg, parallel(copy, images, styles, scripts)),
  serve,
  watchForChanges
)
export const build = series(
  clean,
  parallel(copy, styles, svg, images, scripts),
  pot,
  compress
)
export default dev