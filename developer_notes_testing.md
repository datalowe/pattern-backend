# INSTRUCTIONS FOR TESTING ENVIRONMENT

## Local environment
https://dbwebb.se/uppgift/objektorientering-med-klasser-i-php

You must have Xdebug or PCOV installed (can be installed with e. g. `pecl install xdebug`).

Inside laravel folder, execute:
- make install
- make test

## Overview Laravel testing
https://laravel.com/docs/8.x/testing

Important files and folders:
- phpunit.xml - sets testing environment variables and folders to test
- tests/Unit/ - smaller portions of code
- tests/Feature/ - larger portions, including object interactions and HTTP requests
- tests/CreatesApplication.php - trait CreatesApplication applied to application TestCase class
.env.testing - executes instead of .env file when running PHPUnit tests

Most tests should be feature tests.

## Creating tests
Create a new test case in tests/Feature with:
- php artisan make:test ExampleTest (add --unit if you want unit test)

## Define test methods
Commented example below. You can define setUp / tearDown methods within a test class. Call these methods on the parent class
- parent::setup()
- parent::tearDown()

<!-- class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_basic_request()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
} -->

## Running tests
Before running tests, clear configuration cache:
- php artisan config:clear

Execute tests with either:
- ./vendor/bin/phpunit
- php artisan test
- php artisan test --parallel - if you want to run parallel/sequental tests

## Parallel Testing & Databases
Laravel automatically creates and migrates a test database for each parallel process running your tests. Test databases persistt between calls with the 'php artisan test' command. Eventueally, recreate databases with:
- php artisan test --parallel --recreate-databases

## Examples of testing

### Customizing Request Headers before sent to application
Example commented out below:

<!-- <?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_interacting_with_headers()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/user', ['name' => 'Sally']);

        $response->assertStatus(201);
    }
} -->

### Assertion examples

| Assertion        | Asserts that:                                        | How                                                  |
|------------------|------------------------------------------------------|------------------------------------------------------|
| assertCreated    | Response has 201 HTTP status code (HSC)              | $response->assertCreated();                          |
| assertHeader     | Given header and value is present                    | $response->assertHeader($headerName, $value = null); |
| assertExactJson  | Response contains exact match of given JSON data     | $response->assertExactJson(array $data);             |
| assertJson       | Response contains given JSON data                    | $response->assertJson(array $data, $strict = false); |
| assertOk         | Response has 200 HTTP status code (HSC)              | $response->assertOk();                               |
| assertStatus     | Response has a given HTTP status code                | $response->assertStatus($code);                      |
| assertSuccessful | Response has successful (200-299) HSC                | $response->assertSuccessful();                       |
