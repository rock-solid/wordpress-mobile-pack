var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    replace = require('gulp-batch-replace'),
    stripCssComments = require('gulp-strip-css-comments'),
    hex2rgb = require('hex2rgb'),
    concat = require('gulp-concat');

var colorVariables = [
    {
        label: "Headlines and primary texts",
        name: "$base-text-color",
        hex: "#ff0001"
    },
    {
        label: "Article background",
        name: "$shape-bg-color",
        hex: "#ff0002"
    },
    {
        label: "Article border",
        name: "$article-border-color",
        hex: "#ff0003"
    },
    {
        label: "Secondary texts",
        name: "$extra-text-color",
        hex: "#ff0004"
    },
    {
        label: "Category label color",
        name: "$category-color",
        hex: "#ff0005"
    },
    {
        label: "Category text color",
        name: "$category-text-color",
        hex: "#ff0006"
    },
    {
        label: "Buttons	",
        name: "$buttons-color",
        hex: "#ff0007"
    },
    {
        label: "Menu",
        name: "$menu-color",
        hex: "#ff0008"
    },
    {
        label: "Forms",
        name: "$form-color",
        hex: "#ff0009"
    },
    {
        label: "Cover text color",
        name: "$cover-text-color",
        hex: "#ff0101"
    }
];

function buildReplacementList(){

    var arrReplacements = [
        ['$paragraph-font', '$paragraphs-font']
    ];

    for (var i=0; i < colorVariables.length; i++) {
        var item = colorVariables[i];
        var name = item.name;
        var hex = item.hex;
        var rgb = hex2rgb(hex).rgb;

        arrReplacements.push(
            [hex, name],
            [rgb.join(', '), name],
            [rgb.join(','), name]
        );
    }

    arrReplacements.push([/wbz\-custom\:(|\s| )(\'|\")/g, ""]);
    return arrReplacements;
}

gulp.task('replace-reds', function(){

    var arrReplacements = buildReplacementList();

    return gulp.src(['files/phone.scss'])
        .pipe(replace(arrReplacements))
        .pipe(stripCssComments())
        .pipe(gulp.dest('../frontend/themes/app1/scss/'));
});


// Build arrays with the different combinations for compiling the themes
var mergeCssFiles = {};
var compileCssFiles = {};

for (var colors = 1; colors <= 3; colors++){
    for (var fonts = 1; fonts <= 3; fonts++){

        mergeCssFiles['mergeScss' + String(colors) + '_' + String(fonts)] = {
            'colors': colors,
            'fonts': fonts
        };

        compileCssFiles['compileScss' + String(colors) + '_' + String(fonts)] = {
            'colors': colors,
            'fonts': fonts
        };
    }
}

// Create one merged scss file for each color scheme / fonts option
var mergeTasks = Object.keys(mergeCssFiles);

mergeTasks.forEach(function(taskName) {
    gulp.task(taskName, function() {

        var colorScheme = String(mergeCssFiles[taskName].colors);
        var fonts = String(mergeCssFiles[taskName].fonts);

        return gulp.src(['files/_variables_colors' + colorScheme + '.scss', 'files/_variables_fonts' + fonts + '.scss', '../frontend/themes/app1/scss/phone.scss'])
            .pipe(concat({ path: 'colors-' + colorScheme + '-fonts-' + fonts + '.scss', stat: { mode: 0666 }}))
            .pipe(gulp.dest('merged/'));

    });
});

gulp.task('merge-scss', mergeTasks);

// Compile each color scheme / fonts option
var compileTasks = Object.keys(compileCssFiles);

compileTasks.forEach(function(taskName) {
    gulp.task(taskName, function() {

        var colorScheme = String(compileCssFiles[taskName].colors);
        var fontsScheme = String(compileCssFiles[taskName].fonts);

        return sass('merged/colors-' + colorScheme + '-fonts-' + fontsScheme + '.scss', { style: 'compressed' })
            .on('error', sass.logError)
            .pipe(gulp.dest('../frontend/themes/app1/css/'));
    });
});

gulp.task('compile-scss', compileTasks);