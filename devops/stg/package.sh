echo "Current directory: $(pwd)"
echo "Contents of ../../src directory: $(ls -R ../../src)"


mkdir -p ./target/

# zip -r  ./target/plugin-warpdriven-ai.zip $(pwd)/../../src/*.php -x "devops/**" "node_modules/**" "target/**" ".*"
zip -r  ./target/plugin-warpdriven-ai.zip $(pwd)/../../src/*.php
