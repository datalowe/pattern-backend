# SCTR backend
This repository is part of a group project done for the ['pattern' course](https://www.bth.se/utbildning/program-och-kurser/kurser/20232/BR4QJ/) at Blekinge Institute of Technology.

This repository mainly consists of code for building a Docker container running a Laravel REST API server with a database. It includes a submodule 'pattern-db', based on [the SCTR database repository](https://github.com/joki20/pattern-db).

Note that, in order to include the submodule's code, when cloning the repository you need to run `git clone --recurse-submodules https://github.com/datalowe/pattern-backend`.

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

# depending on your host OS, you might need/prefer to use `docker compose up` instead
docker-compose up
```
Note that this command runs in 'attached' mode, meaning the terminal will show output logs from both the database and the Laravel/backend containers. This is useful to keep track of when both containers (especially the database) have finished loading and you should be able to connect. If you want to run in detached mode instead, use `docker-compose -d up`.

To interact with the Laravel application, go to (in your browser, on the host computer) e.g. `localhost:8080` or `localhost:8080/api/scooters`.

To bring containers down and delete them, run (after hitting 'ctrl+C'/'Cmd+C' if you ran in 'attached mode')
```bash
docker-compose down
```

If you want to tidy up and remove the created images as well, run
```bash
docker-compose down --rmi all
```

To force image rebuilds (to make app updates be reflected in Docker containers), run
```bash
docker-compose build
```

## Updating database migration files
Note that the pattern-db submodule is the single source of truth for what the database should 'really' be like, and as mentioned above, it's created and spun up separately from Laravel. However, it's useful to have Laravel database migration files for enabling database setup when testing. To this end, the [Laravel Migrations Generator](https://github.com/kitloong/laravel-migrations-generator) package is used to generate migrations files _based on the already existing MySQL (pattern-db) database_.

If pattern-db is updated in a manner which makes the migrations files outdated, follow these steps to update them:

1. Delete all non-default Laravel migrations files (ie files whose names start with a later date than '2019_12_14') from 'laravel/database/migrations'.
2. `cd` into the 'laravel' directory.
3. Run `php artisan migrate:generate`.

You should now see that new files have been generated in 'laravel/database/migrations'.

## References
Much of this project is likely to be inspired by [this guide on building REST API's with Laravel](https://www.toptal.com/laravel/restful-laravel-api-tutorial?utm_source=learninglaravel.net) by André Castelo.
