# New Mexico 🌄

Roadtrips & Adventures with your friends !

## Requirements

- [GNU/Bash](https://www.gnu.org/software/bash/)
- [GNU/Make]()

## Setup

```bash

cd projet-php-new-mexico
```

Setup SMTP - Example for gmail:

> Enable 2 factor authentication for your gmail account
> then visit : https://myaccount.google.com/apppasswords

Use the generated password instead of email password in .env to send mail via your app.

Copy env

```bash
cp .env.example .env
```

Install dependencies

```bash
composer install
npm install
```

Make sure docker is running !

```bash
make start
```

visit : localhost:8000 (you can change the port by editing `SERVER_PORT` in the `.env` file.)

Access the db (this will login to your database using credentials from .env.)

```bash
make database
```

> Run the queries from db/migrations to create the necessary tables

Build scss+js (into 'public/assets' folder) & watch mode scss+js

```bash
npm build-style
npm watch-style
```

## Other helpful commands

stops containers

```bash
make stop
```

stop containers then start again.

```bash
make restart
```

access php

```bash
docker compose exec php /bin/sh
```

## With 💕 by EESHVARAN Dilan & TO Vincent & BAI Aissame
