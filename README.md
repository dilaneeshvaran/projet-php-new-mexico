# New Mexico 🌄

Roadtrips & Adventures with your friends !

## Requirements

- [GNU/Bash](https://www.gnu.org/software/bash/)
- [GNU/Make]()
- Composer
- Node.js
- Docker

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

Watch mode scss+js

```bash
npm run watch
```

## Other helpful commands

run migrations

```bash
make migrate
```

access the db (this will login to your database using credentials from .env.)

```bash
make database
```

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
