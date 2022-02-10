uglifyjs --compress --mangle --mangle-props -o webroot/js/studio.soft.build.js -- webroot/js/studio/studio.js


uglifyjs --compress -o webroot/js/studio.vendors.build.js -- webroot/js/studio/vue.min.js webroot/js/vendor/jquery.min.js webroot/js/vendor/popper.min.js webroot/bootstrap4/js/bootstrap.min.js webroot/js/studio/html2canvas.min.js webroot/js/studio/vue-color.js webroot/js/studio/polyfills.js