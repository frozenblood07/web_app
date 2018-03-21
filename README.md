# Web App
No framework framework PHP Web App. I chose to do this assignment without using any framework to show my coding and architectural capabilites. It is preferrable to run it in a docker

## System Requirements
- docker
- docker-compose
- composer

## Requirement Installations
- [Docker](https://docs.docker.com/install/)
- [Docker-compose](https://docs.docker.com/compose/install/)
- [Composer](https://getcomposer.org/download/)

## Initial Project Setup

### Clone the repository
```
git clone https://github.com/frozenblood07/web_app.git
```
### Setting the app

 **Please free your port 80 and 6379 if they are in use.**

```
docker-compose build
```
This will build the docker container with all the neccessary requirements.(This set will take sometime)

There are bash files to run and stop the project 


```sh dev_start.sh //runs the project in dev mod```

```sh stop.sh  //stops the project```


I have created the necessary config files and scripts from development.
If we need to add more environments the project is extendable to support all different environments and configurations based on the environments. 
We just have to add the config files for other modes.

After this your project should be up and running and you should be able to access the app on localhost

```http://localhost```


Now we need to populate the database from the csv file which is present in the data/ folder. So go the url

```http://localhost/populate```

You should be able to see a message saying db is populated

The inventory listing url is below you should be able to use the navigate to other urls from here.

```http://localhost/inventory```

 **If by any chance the docker is not working properly. Then follow the following steps**
 - Go to web/ directory in root folder and run the command 
 ``` php -S localhost //This will start a server on your local system ```
 - Go to the **config/redis/redis_development.ini** file and enter your systems redis configuration.
 
   Then you can the above mentioned urls and traverse the system
 

### -Libraries Used

- filp/whoops - error handler
- patricklouys/http - Http component
- nikic/fast-route-  request router
- rdlowrey/auryn - Dependency Injector
- predis/predis - Redis client
- katzgrau/klogger - logger
- mustache/mustache - rendering engine
- twig/twig - rendering engine

Dev Dependencies
- squizlabs/php_codesniffer - codesniffer
- phpunit/phpunit -testing framework
- guzzlehttp/guzzle - http client for testing

### JS Code Question
My answer to the JS Question provided in the assignment is in the File **JS_Code_Points.md**

