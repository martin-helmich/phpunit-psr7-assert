# PSR-7 assertions for PHPUnit

[![Build Status](https://travis-ci.org/martin-helmich/phpunit-psr7-assert.svg?branch=master)](https://travis-ci.org/martin-helmich/phpunit-psr7-assert)
[![Code Climate](https://codeclimate.com/github/martin-helmich/phpunit-psr7-assert/badges/gpa.svg)](https://codeclimate.com/github/martin-helmich/phpunit-psr7-assert)
[![Test Coverage](https://codeclimate.com/github/martin-helmich/phpunit-psr7-assert/badges/coverage.svg)](https://codeclimate.com/github/martin-helmich/phpunit-psr7-assert/coverage)

## Author and copyright

Martin Helmich <kontakt@martin-helmich.de>  
This library is [MIT-licensed](LICENSE.txt).

## Installation

    $ composer require helmich/phpunit-psr7-assert

**Compatibility notice**: [Version 1](https://github.com/martin-helmich/phpunit-psr7-assert/tree/v1) (the `v1` branch) of this library is compatible with PHPUnit 4.8 to 5. [Version 2](https://github.com/martin-helmich/phpunit-psr7-assert/tree/master) (the `master` branch) is compatible with PHPUnit 6 and later. When using `composer require`, Composer should automatically pick the correct version for you.

## Usage

### Using the `Psr7Assertions` trait

Simply use the trait `Helmich\Psr7Assert\Psr7Assertions` in any of your test
cases. This trait offers a set of new `assert*` functions that you can use in
your test cases:

```php
<?php
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit\Framework\Testcase;

class MyTestCase extends Testcase
{
  use Psr7Assertions;

  public function testRequestMatchesExpectations()
  {
    $request = /* build some instance of Psr\Http\Message\MessageInterface */;

    $this->assertRequestHasUri($request, '/foo');
    $this->assertRequestIsPost($request);
    $this->assertMessageHasHeaders($request, [
      'content-type' => 'application/json',
      'expect'       => '202-accepted'
    ]);
    $this->assertMessageBodyMatchesJson($request, [
      '$.username' => 'mhelmich'
    ]);
  }
}
```

Most assertions take a `$message` argument which is expected to be an instance
of the `Psr\Http\Message\MessageInterface` class -- this means that many
assertions work both with request and response messages. These interfaces are
defined by the [psr/http-message][psr7] package.

### Using the functional interface

This package also offers a functional interface that can be used in a more
fluent way than the assertions offered by the `Psr7Assertions` trait. Simply
include the file `src/Functions.php` for your test cases (preferably, using
Composer's [`autoload-dev` setting][composer-autoload]):

```php
public function testRequestMatchesExpectations()
{
  $request = /* build some instance of Psr\Http\Message\MessageInterface */;

  assertThat($request, logicalAnd(
    hasUri('/foo'),
    isPost(),
    hasHeaders([
      'content-type' => 'application/json',
      'expect'       => '202-accepted'
    ]),
    bodyMatchesJson(['$.username' => 'mhelmich'])
  ));
}
```

## Assertion reference

##### `assertRequestHasUri($request, $uri)` / `hasUri($uri)`

Asserts that the request URI of request `$request` is equal to `$uri`

```php
$this->assertRequestHasUri($request, '/foo'));
assertThat($request, hasUri('/foo'));
```

##### `assertMessageHasHeader($message, $header[, $constraint])` / `hasHeader($name[, $constraint])`

Asserts that the header named `$header` is present in the HTTP message. The exact behaviour of this assertion is dependent on the `$constraint` parameter:

1. If no constraint is given, the assertion will match when the header is
   present and not empty.
2. If a primitive value is given as `$constraint`, the assertion will match when
   the header is present and is equal to the specified value
3. If `$constraint` is an instance of the `PHPUnit\Framework\Constraint\Constraint` class,
   the assertion will match when the constraint evaluates to `TRUE`.

    Example:

    ```php
    assertThat($request, hasHeader('content-length', greaterThan(1000)));
    ```

##### `assertMessageHasHeaders($message, $constraints)` / `hasHeaders($constraints)`

Same as `assertMessageHasHeader`, just with multiple headers. `$constraints` is
a key-value array using header names as keys and constraints (see above) as values.

Example:

```php
assertThat($request, hasHeaders([
  'content-type'   => 'application/json',
  'content-length' => greaterThan(1000)
]));
```

##### `assertMessageBodyMatches($message, $constraint)` / `bodyMatches($constraint)`

Asserts that the message body matches the constraint `$constraint`. If
`$constraint` is a primitive value, the assertion will pass when the message
body is equal to the constraint. If `$constraint` is an instance of the
`PHPUnit\Framework\Constraint\Constraint` class, the constraint will be evaluated
as-is.

##### `assertMessageBodyMatchesJson($message, $jsonConstraints)` / `bodyMatchesJson($jsonConstraints)`

This actually asserts several facts:

1. The message must have a `content-type` header that is equal to
   `application/json`
2. The message body must be a valid JSON string (that means decodeable by
   `json_decode`)
3. The encoded JSON object must match all constraints specified in the `$jsonConstraints` array. For this, the [helmich/phpunit-json-assert][json-assert] package will be used.

##### `assertRequestHasMethod($request, $method)` / `hasMethod($method)`

Asserts that the request has the method `$method`. For the most common request
method, shorthand assertions are available:

- `assertRequestIsGet($request)` / `isGet()`
- `assertRequestIsPost($request)` / `isPost()`
- `assertRequestIsPut($request)` / `isPut()`
- `assertRequestIsDelete($request)` / `isDelete()`

#### `assertResponseHasStatus($response, $status)` / `hasStatus($status)`

Asserts that the response status matches a given constraint. If `$status` is a
scalar value, this assertion will check for equality.

```php
assertThat($response, hasStatus(200));
assertThat($response, hasStatus(logicalAnd(greaterThanOrEqual(200), lessThan(400))));
```

For the most common checks, some shorthand assertions are available:

- `assertResponseIsSuccess($response)` / `isSuccess()` -- Status codes 200 to 299
- `assertResponseIsClientError($response)` / `isClientError()` -- Status codes 400 to 499
- `assertResponseIsServerError($response)` / `isServerError()` -- Status codes 500 to 599

#### `assertStringIsAbsoluteUri($uri)` / `isAbsoluteUri()`

Assert that the string `$uri` contains a valid absolute URL (scheme and hostname are required).

#### `assertHasQueryParameter($uriOrRequest, $name[, $value])` / `hasQueryParameter($name[, $value])`

Asserts that an URI contains a query parameter matching the given constraints.
`$name` and `$value` may both be string values as well as instances of the
`PHPUnit\Framework\Constraint\Constraint` interface.

The `$uriOrRequest` value may be

- a string, which will be interpreted as URI
- an instance of the `Psr\Http\Message\UriInterface` interface
- an instance of the `Psr\Http\Message\RequestInterface` interface 

[composer-autoload]: https://getcomposer.org/doc/04-schema.md#autoload-dev
[json-assert]: https://packagist.org/packages/helmich/phpunit-json-assert
[psr7]: https://packagist.org/packages/psr/http-message
