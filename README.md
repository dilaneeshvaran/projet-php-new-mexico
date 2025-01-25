# New Mexico 🌄

Roadtrips & Adventures with your friends !

## Requirements

- [GNU/Bash](https://www.gnu.org/software/bash/)
- [GNU/Make]()

## Setup

```bash

cd projet-php-new-mexico
```

Copy env

```bash
cp .env.example .env
```

Make sure docker is running !

```bash
make start
```

visit : localhost:8000 (you can change the port listened by the server by editing `SERVER_PORT` in the `.env` file.)

> Create the necessary tables in db, using the src/models structure

## Other helpful commands

```bash
make database
```

access db (this will login to your database using a command line interface using .env.)

```bash
make stop
```

stops containers

```bash
make restart
```

stop containers then start again.

```bash
docker compose exec php /bin/sh
```

access php

## With 💕 by EESHVARAN Dilan & TO Vincent & BAI Aissame
