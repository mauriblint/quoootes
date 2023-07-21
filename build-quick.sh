echo "Start creating vendors bundle"
uglifyjs --compress -o webroot/js/qs.vendors.build.js \
     -- webroot/js/studio/vue.min.js \
        webroot/js/studio/filesaver.min.js \
        webroot/js/vendor/jquery.min.js \
        webroot/js/studio/domtoimage.min.js \
        webroot/js/vendor/vue-loading.js \
        webroot/js/vendor/jquery.textfill.js \

echo "Vendors bundle generated: qs.vendors.build.js"

echo ""

echo "Start creating soft bundle"
uglifyjs -o webroot/js/qs.app.build.js \
    -- webroot/js/studio/polyfills.js \
    webroot/js/studio/fonts.js \
    webroot/js/studio/studio.mixin.js \
    webroot/js/studio/studio.quick.js

echo "Soft bundle generated: qs.app.build.js"

echo ""

echo "Start obfuscating soft bundle"
javascript-obfuscator webroot/js/qs.app.build.js \
    --compact true --disable-console-output true \
    --output webroot/js/qs.app.build.js

echo "Soft bundle obfuscated!"

git add webroot/js/studio/studio.quick.js
git add webroot/js/qs.vendors.build.js
git add webroot/js/qs.app.build.js
git add webroot/js/studio/studio.mixin.js

echo "Soft bundle ready to commit!"