# Research Database User Web Interface

## Prerequisites
- Docker Desktop installed and running
- Port 8080 free on your machine

## To run locally:
I am using a Docker container to host the webpage & SQL DB for live updates & testing.
To build & run a container using Docker, run in terminal:

    docker build -t researchdb .
    docker run -p 8080:80 -v $(pwd):/var/www/html researchdb

Then visit:
    http://localhost:8080/login.php


## To rebuild after major changes:
    docker build -t researchdb . --no-cache
    docker run -p 8080:80 -v $(pwd):/var/www/html researchdb

## Project structure:
    Webapp/
    ├── Dockerfile
    ├── login.php
    └── includes/
        ├── header.php
        ├── footer.php
        ├── database-connection.php
        └── session.php

**NOTE:** This container was built using MacOS. If any of these steps 
don't work, update this file with a workaround.