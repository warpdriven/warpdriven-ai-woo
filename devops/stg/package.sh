git pull

cd ../wd-woo-plugin-nlp-php

mkdir -p ./target/stg
rm ./target/stg/*.zip

zip -r  ./target/stg/plugin-warpdriven-ai.zip . -x "devops/**" "node_modules/**" "target/**" ".*"
