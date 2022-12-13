#uglifyjs --compress --mangle --mangle-props -o webroot/js/studio.soft.build.js -- webroot/js/studio/studio.js


#uglifyjs --compress -o webroot/js/studio.vendors.build.js -- webroot/js/studio/vue.min.js webroot/js/vendor/jquery.min.js webroot/js/vendor/popper.min.js webroot/bootstrap4/js/bootstrap.min.js webroot/js/studio/html2canvas.min.js webroot/js/studio/vue-color.js webroot/js/studio/polyfills.js

echo "Start creating vendors bundle"
uglifyjs --compress -o webroot/js/studio.vendors.build.js \
     -- webroot/js/studio/vue.min.js \
        webroot/js/studio/moveable.min.js \
        webroot/js/studio/filesaver.min.js \
        webroot/js/vendor/jquery.min.js \
        webroot/js/vendor/popper.min.js \
        webroot/bootstrap4/js/bootstrap.min.js \
        webroot/js/studio/html2canvas.min.js \
        webroot/js/studio/vue-color.js \
        webroot/js/studio/polyfills.js

echo "Vendors bundle generated: studio.vendors.build.js"

echo ""

echo "Start creating soft bundle"
uglifyjs -o webroot/js/studio.soft.build.js \
    -- webroot/js/studio/polyfills.js \
    webroot/js/studio/fonts.js \
    webroot/js/studio/studio.js

echo "Soft bundle generated: studio.vendors.build.js"

echo ""

echo "Start obfuscating soft bundle"
javascript-obfuscator webroot/js/studio.soft.build.js \
    --compact true --disable-console-output true --debug-protection true \
    --output webroot/js/studio.soft.build.js

echo "Soft bundle obfuscated!"

git add webroot/js/studio/studio.js
git add webroot/js/studio.soft.build.js

echo "Soft bundle ready to commit!"