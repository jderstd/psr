set shell := ["bash", "-cu"]
set windows-shell := ["powershell"]

composer := "$(which composer.phar)"

vendor_bin := "vendor/bin/"
mago := vendor_bin + "mago"
phpunit := vendor_bin + "phpunit"

example_php := "examples/php/index.php"
example_slim := "examples/slim/index.php"

# Default action
_:
    just fmt
    just lint
    just analyze
    just test

# Install dependencies
i:
    {{composer}} install

# Setup the project
setup:
    brew install ls-lint typos-cli
    just i

# Format code
fmt:
    ./{{mago}} format

# Lint code
lint:
    ls-lint
    typos
    ./{{mago}} lint --unsafe --fix

# Analyze code
analyze:
    ./{{mago}} analyze --unsafe --fix

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
