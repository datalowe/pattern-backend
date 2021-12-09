# SCTR backend
[![datalowe](https://circleci.com/gh/datalowe/pattern-backend.svg?style=svg)](https://app.circleci.com/pipelines/github/datalowe/pattern-backend) [![Maintainability](https://api.codeclimate.com/v1/badges/ebb4a9890d243a29d6c8/maintainability)](https://codeclimate.com/github/datalowe/pattern-backend/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/ebb4a9890d243a29d6c8/test_coverage)](https://codeclimate.com/github/datalowe/pattern-backend/test_coverage)

This repository is part of a group project done for the ['pattern' course](https://www.bth.se/utbildning/program-och-kurser/kurser/20232/BR4QJ/) at Blekinge Institute of Technology.

This repository mainly consists of code for building a Docker container running a Laravel REST API server with a database. It includes a submodule 'pattern-db', based on [the SCTR database repository](https://github.com/joki20/pattern-db).

Note that, in order to include the submodule's code, when cloning the repository you need to run `git clone --recurse-submodules git@github.com:datalowe/pattern-backend.git`.

## Developer mode
During development, it's handy to have a Docker container with the database running in the background, but editing Laravel code and immediately checking updates on the host computer.

1. Follow the instructions in 'pattern-db/README.md' for creating and getting a MySQL Docker container up and running, available/exposed on the host computer on port 6666 (the instructions there ensure that port 6666 is used).
2. `cd` to the 'laravel' subdirectory
3. Run `composer install`.
   - This will load composer repositories, then import packages and create the 'vendor' directory with autoload script
4. Copy and rename the file 'laravel/.env.example' to 'laravel/.env'. Note that the 'DB_*' environment variables in 'laravel/.env.example' configure how Laravel talks to the database. The preassigned values are such that they should work with the Docker container started in step 1.
5. (still in the 'laravel' subdirectory) Run `php artisan serve` to start the development server.
6. Connect to localhost:8000 through your browser and check that the server works.

You can find additional developer notes in 'developer_notes.md' here in the root directory.


## Build and run with Docker
```bash
cd /path/to/this/dir

# depending on your host OS, you might need/prefer to use `docker compose up` instead.
# note the '--build' flag which ensures that images are always built, rather than
# containers being run based on pre-existing, possibly outdated, images.
docker-compose up --build
```
Note that this command runs in 'attached' mode, meaning the terminal will show output logs from both the database and the Laravel/backend containers. This is useful to keep track of when both containers (especially the database) have finished loading and you should be able to connect. If you want to run in detached mode instead, use `docker-compose -d up --build`.

To interact with the Laravel application, go to (in your browser, on the host computer) e.g. `localhost:8000` or `localhost:8000/api/scooters`. __Note that the default Laravel development mode port (8000) is used here__, meaning you can't have Laravel running on your host machine and using the Docker containers at the same time.

To bring containers down and delete them, run (after hitting 'ctrl+C'/'Cmd+C' if you ran in 'attached mode')
```bash
docker-compose down
```

If you want to tidy up and remove the created images as well, run
```bash
# this leaves the redis image intact, since it's external and not affected
# by changes in repo code
docker-compose down --rmi local
```

To force image rebuilds (to make app updates be reflected in Docker containers), run
```bash
docker-compose build
```

## Update database migration files
Note that the pattern-db submodule is the single source of truth for what the database should 'really' be like, and as mentioned above, it's created and spun up separately from Laravel. However, it's useful to have Laravel database migration files for enabling database setup when testing. To this end, the [Laravel Migrations Generator](https://github.com/kitloong/laravel-migrations-generator) package is used to generate migrations files _based on the already existing MySQL (pattern-db) database_.

If pattern-db is updated in a manner which makes the migrations files outdated, follow these steps to update them:

1. Delete all Laravel migrations files from 'laravel/database/migrations'.
2. If there isn't already one running, start a MySQL container according to the instructions in 'pattern-db/README.md'.
3. `cd` into the 'laravel' directory.
4. Run `php artisan migrate:generate`.

You should now see that new files have been generated in 'laravel/database/migrations'.

5. (until we remove the view or find an alternate solution) Remove the migration file '...create_v_logg_view.php' because it otherwise causes issues when regenerating the database during feature tests.

## Rest API Details
The following routes are used for this project:

<div class="routesTable">

| Endpoint /api...            | GET                                                                     | POST                                | PUT                                                          |
|-----------------------------|-------------------------------------------------------------------------|-------------------------------------|--------------------------------------------------------------|
| /users                      | Get all users                                                           | Add new user                        |                                                              |
| /users/{id}                 | Get single customer                                                     |                                     | Update single customer i.e. saldo and payment_terms          |
| /users/{id}/logs            | Get all travel logs of single customer                                  |                                     |                                                              |
| /scooters                   | Get all scooters                                                        |                                     |                                                              |
| /scooters/{id}              | Get single scooter                                                      |                                     | Update single scooter i.e. status, användare and batterinivå |
| /stations                   | Get all parking spaces and charge stations including their positions    |                                     |                                                              |
| /stations/{id}              | Get single parking space or charge station including its position       |                                     |                                                              |
| /stations/{id}/scooters     | Get all scooters connected to specific parking space or charge station  |                                     |                                                              |
| /cities                     | Get all cities                                                          |                                     |                                                              |
| /cities{id}                 | Get single city                                                         |                                     |                                                              |
| /cities/{id}/scooters       | Get all scooters belonging to single city                               |                                     |                                                              |
| /cities/{id}/stations       | Get all parking spaces and charging stations belonging to single city   |                                     |                                                              |
| /auth/github/redirect       | Returns a github login url for user                                     | Add new travel log when trip starts |                                                              |
| /auth/github/callback       | Sends user to this callback route endpoint by github                    |                                     |                                                              |
| /auth/github/redirect/admin | Returns a github login url for admin                                    |                                     |                                                              |
| /auth/github/callback/admin | Sends admin to this callback route endpoint by github                   |                                     |                                                              |
| /auth/github/check-usertype | Checks if user is logged in with OAuth, and if so, as admin or customer |                                     |                                                              |

</div>

## References
Some of this project has been inspired by [this guide on building REST API's with Laravel](https://www.toptal.com/laravel/restful-laravel-api-tutorial?utm_source=learninglaravel.net) by André Castelo, but most of all we've made use of the [official Laravel documentation](https://laravel.com/docs/8.x/readme).