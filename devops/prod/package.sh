git pull

composer upgrade
cp devops/prod/WDEnv.php vendor/warp-driven/php-sdk/src/

cd ../wd-woo-plugin-gpt-vue/
git pull
npm install
npm run build

cp -R dist ../wd-woo-plugin-nlp-php/assets/

cd ../wd-woo-plugin-nlp-php

mkdir -p ./target/prod
rm ./target/prod/*.zip
zip -r  ./target/prod/wd-woo-plugin-nlp-php.zip . -x "devops/**" "node_modules/**" "target/**" ".*"