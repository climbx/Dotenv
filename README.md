# Dotenv

[![Build Status](https://travis-ci.com/climbx/Dotenv.svg?branch=1.0)](https://travis-ci.com/climbx/Dotenv)
[![Maintainability](https://api.codeclimate.com/v1/badges/95d884d048e91086df7d/maintainability)](https://codeclimate.com/github/climbx/Dotenv/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/95d884d048e91086df7d/test_coverage)](https://codeclimate.com/github/climbx/Dotenv/test_coverage)

Dotenv component parses `.env` files and add them to both `$_ENV` and
`$_SERVER`.

## Install

```
$ composer require climbx/dotenv
```

## Usage

```dotenv
# .env

foo=bar
bar=baz
```

```php
// PHP

use Climbx\Dotenv\Dotenv;

$dotenv = new Dotenv();
$envPath = __DIR__ . '/.env';

/*
 * Loads env vars into $_ENV and $_SERVER
 * without overwriting existing variables
 */
$dotenv->load($envPath);

/*
 * Loads env vars into $_ENV and $_SERVER
 * if a variable already exists, it is overridden.
 */
$dotenv->overload($envPath);
```

## Env files syntax
```dotenv
# Comment line

foo=bar                                 # End line comment
bar='this is a single quotted value'    # allows whitespaces
baz="this is a double quotted value"   
num=1234
```
 
## Variables references

Simple var reference:
```dotenv
foo=bar
baz=$foo        # outputs ['baz' => 'bar']
```

Multiple var references:
```dotenv
foo=1
bar=2

spaced="$foo and $bar"   # outputs ['spaced' => '1 and 2']
collapsed=$one$two       # outputs ['collapsed' => '12']
```

Partial var references:
```dotenv
foo="one"
bar="two"

baz=${foo}/${bar}/three      # outputs ['baz' => 'one/two/three']
```

Escaped var declaration char
```dotenv
foo=bar
echaped1=\$foo   # outputs ['echaped1' => '$foo']
echaped2=\${foo} # outputs ['echaped2' => '${foo}']
```

Missing var reference.  
Will not throw an exception. If a reference is not found, value is 
set to an empty var.
```dotenv
missing1=$foo     # outputs ['missing1' => '']
missing2=\${foo}  # outputs ['missing2' => '']
```


