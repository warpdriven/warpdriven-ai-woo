git pull

composer upgrade
cp devops/prod/WDEnv.php vendor/warp-driven/php-sdk/src/

cd ../wd-woo-plugin-gpt-vue/

rm -rf ./assets
git pull
npm install
npm run build

cp -R dist ../wd-woo-plugin-nlp-php/assets/

cd ../wd-woo-plugin-nlp-php

mkdir -p ./target/prod
rm ./target/prod/*.zip

zip -r  ./target/prod/WarpDriven-GPT-Copywriting.zip . -x "devops/**" "node_modules/**" "target/**" ".*"