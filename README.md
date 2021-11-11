# SCTR backend
This repository is part of a group project done for the ['pattern' course](https://www.bth.se/utbildning/program-och-kurser/kurser/20232/BR4QJ/) at Blekinge Institute of Technology.

This repository mainly consists of code for building a Docker container running a Laravel REST API server with a database. It includes a submodule 'pattern-db', based on [the SCTR database repository](https://github.com/joki20/pattern-db).

Note that, in order to include the submodule's code, when cloning the repository you need to run `git clone --recurse-submodules https://github.com/datalowe/pattern-backend`.

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

## References
Much of this project is likely to be inspired by [this guide on building REST API's with Laravel] by André Castelo.