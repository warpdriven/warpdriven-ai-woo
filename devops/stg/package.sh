git pull

composer upgrade
cp devops/stg/WDEnv.php vendor/warp-driven/php-sdk/src/

npm install
npm run build

mkdir -p ./target/stg
rm ./target/stg/*.zip
zip -r  ./target/stg/wd-gpt-copywriting.zip . -x "devops/**" "node_modules/**" "target/**" ".*"
