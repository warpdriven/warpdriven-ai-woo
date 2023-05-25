git pull

composer upgrade
cp devops/stg/WDEnv.php vendor/warp-driven/php-sdk/src/

cd ../wd-woo-plugin-gpt-vue/

rm -rf ./assets
git pull
npm install
npm run build

cp -R dist ../wd-woo-plugin-nlp-php/assets/

cd ../wd-woo-plugin-nlp-php

mkdir -p ./target/stg
rm ./target/stg/*.zip

zip -r  ./target/stg/warpdriven-gpt-copywriting.zip . -x "devops/**" "node_modules/**" "target/**" ".*"
