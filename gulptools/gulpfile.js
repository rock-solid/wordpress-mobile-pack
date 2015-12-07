var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    replace = require('gulp-batch-replace'),
    stripCssComments = require('gulp-strip-css-comments'),
    hex2rgb = require('hex2rgb'),
    concat = require('gulp-concat');

var theme = 1;

var colorVariables = {
    'app1': [
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
    ]
};

function buildReplacementList(){

    var arrReplacements = [
        ['$paragraph-font', '$paragraphs-font']
    ];

    for (var i=0; i < colorVariables['app'+String(theme)].length; i++) {
        var item = colorVariables['app'+String(theme)][i];
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

    return gulp.src(['files/app'+String(theme)+'/phone.scss'])
        .pipe(replace(arrReplacements))
        .pipe(stripCssComments())
        .pipe(gulp.dest('../frontend/themes/app'+String(theme)+'/scss/'));
});

gulp.task('merge-scss', function(){

    return gulp.src(['files/app'+String(theme)+'/_variables.scss', '../frontend/themes/app'+String(theme)+'/scss/phone.scss'])
        .pipe(concat({ path: 'phone.scss', stat: { mode: 0666 }}))
        .pipe(gulp.dest('files/app'+String(theme)+'/merged/'));
});

gulp.task('compile-default-theme', function () {
    return sass('files/app'+String(theme)+'/merged/phone.scss', { style: 'compressed' })
        .on('error', sass.logError)
        .pipe(gulp.dest('../frontend/themes/app'+String(theme)+'/css/'));
});