# Project Follower

**Project Follower** is a saved version of a final project for my collage classes - utilizing PHP and JavaScript.

---

##  Requirements

- **PHP Server** version **8.5.1
- **PostgreSQL** version **8

or

- **Docker**

---

## Running the application

### Using Docker

1. Enter **docker compose --build** in the app folder
2. Enter **docker cp docker/initdb/dump_fin3.sql projectfollower-db:/dump.sql**
3. Enter **docker exec -it projectfollower-db psql -U postgres -d projectfollower -f /dump.sql**

After this the site should be available at: **http://localhost:8080/public/login.html**

---

###  Using PHP Developer Server & PostgreSQL

For this to work PHP Server MUST have PDO enabled - otherwise the communication between it and Database will not work.

1. Enter **psql -U [user_name] -d [database_name] -f dump_fin3.sql**
2. Turn on the PHP server using: **php -S localhost:[port]**

After this the site should be available at: **http://localhost:[chosen_port]/public/login.html**

---

### Aditional Info:

The passwords and logins for users are located in 'loginy.txt' file.
