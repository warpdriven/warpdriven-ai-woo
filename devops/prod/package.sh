git pull

composer upgrade
cp devops/prod/WDEnv.php vendor/warp-driven/php-sdk/src/

npm install
npm run build

mkdir -p ./target/prod
rm ./target/prod/*.zip
zip -r  ./target/prod/wd-gpt-copywriting.zip . -x "devops/**" "node_modules/**" "target/**" ".*"