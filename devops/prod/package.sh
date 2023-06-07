git pull

composer upgrade
cp devops/prod/WDEnv.php src/

cd ../wd-woo-plugin-gpt-vue/

git pull
npm install
npm run build

rm -rf ../wd-woo-plugin-nlp-php/assets/*
cp -R dist ../wd-woo-plugin-nlp-php/assets/

cd ../wd-woo-plugin-nlp-php

mkdir -p ./target/prod
rm ./target/prod/*.zip

zip -r  ./target/prod/plugin-warpdriven-gpt-copywriting.zip . -x "devops/**" "node_modules/**" "target/**" ".*"