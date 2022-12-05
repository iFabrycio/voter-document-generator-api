# Voter Document Generator API

An API to generate documents for voters using PANAGORA api

## How to Deploy
The deploy is automated, just push code on Main branch and will run all tests and release the nem version into server, once finished, the changes are ready in production

### Deployment details
The Deployment uses a default ssh conection to enter into server and then gives a `git pull` to update the code, run `docker compose up -d` to restart the container

The production server is a Docker in an Ubuntu 22.02 server hosted into an Digital ocean server

Simples as that.

## tries
I tried to set up a reverse proxy with nginx that points two urls, onde for docs and another one for the API, but at this time was failed for some unknown reason.

## Development notes
The API is built with Laravel in its latest version, there's no Database beacuse its not necessary for the purpose os this task.
The Documentation server is a NODE js server application running swagger UI inside the project (but in separated containers), from there you can use all the API.

### Particularities
I like to separared the responsability of the things, so i created a new folder called Consumers that hosts the PANAGORAConsumer.php and its responsible to manage the request for thirds parties.

In the PanagoraConsumer.php you will find two functions `makeRequest` and `makeConcurrentRequests` and i only use the last one because of is agility to handle many requests, using the `makeRequest` will create a queue of requests that will raise up the request time.


Any questions, just call me.
