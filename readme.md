# PHP Full Stack Engineer project
___

## The challenge

Tic Tac Toe game in PHP where application permits to create a lot of rounds and allow 2 people per gameplay. The game identify the winner and list available matches where is the round don't has a winner yet.

## How to play

- Create a new match and join it
- Invite another friend to join
- Playing and be the winner :)


## Running the application

The application run with docker, so please make a chmod 777 in storage folder from laravel for that application can run without problems.

[Install docker and docker-compose](https://docs.docker.com/compose/install/)

Then run
```bash
./setup
```
This will take a few minutes. It will create the containers and setup them for use.

When finished, run the application with:
```bash
./up
```

And the application will be running in [http://localhost:8080](http://localhost:8080).

**Note:** If the port 8080 is already in use, change the configuration in the docker-compose.yml file. For example, to use the port 9090 instead:
```yaml
ports:
  - 9090:80
``` 