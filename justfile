set shell := ["bash", "-cu"]
set windows-shell := ["powershell"]

node_bin := "node_modules/.bin/"
prettier := node_bin + "prettier"

composer := "$(which composer.phar)"

vendor_bin := "vendor/bin/"
phpstan := vendor_bin + "phpstan"
phpcs := vendor_bin + "phpcs"
psalm := vendor_bin + "psalm"
phpunit := vendor_bin + "phpunit"

example_php := "examples/php/index.php"
example_slim := "examples/slim/index.php"

# Default action
_:
    just lint
    just fmt
    just test

# Install dependencies
i:
    pnpm install
    {{composer}} install

# Setup the project
setup:
    brew install ls-lint typos-cli
    just i

# Lint code
lint:
    ls-lint
    typos
    ./{{phpcs}}
    ./{{phpstan}} --memory-limit=-1
    ./{{psalm}} --no-cache

# Format code
fmt:
    ./{{prettier}} ./src/* --write
    ./{{prettier}} ./examples/* --write
    ./{{prettier}} ./test/* --write

# Run tests
test:
    ./{{phpunit}} ./test/*

# Start example server
example:
    php -S localhost:4001 ./{{example_php}}

# Start Slim example server
example-slim:
    php -S localhost:4001 ./{{example_slim}}

# Clean modules
clean:
    rm -rf ./node_modules
    rm -rf ./vendor
