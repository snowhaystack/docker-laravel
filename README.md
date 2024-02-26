## Task Description

This project started from the following exercise:

1. Create 2 minimal microservices in Laravel, PHP.
   - Service A calls Service B in REST with 2 different payloads.
   - Service B validates the input.
   - The first payload receives a 200 and is saved to the database by Service B.
   - The second payload fails validation and returns an API error with the validation error.

**Replace `<repository_url>`, `<project_receiver_container_id>`, `<project-forward-host>`, and `<mysql_container_id>` with the appropriate values for your project. This README provides instructions for setting up the Docker containers, using the API, and checking the database as per the specifications provided.**

# Docker Laravel Project

## Setup Instructions

1. Clone this repository:
   ```bash
   git clone <repository_url>
   ```

2. Navigate to the root of the project:
   ```bash
   cd docker-laravel-project
   ```

3. Launch the Docker containers using Docker Compose:
   ```bash
   docker-compose up -d
   ```

4. Wait for all containers to be up and running.

5. Once all containers are up, enter into the `project-receiver` container's command line interface:

   ```bash
   docker exec -it <project_receiver_container_id> /bin/bash
   ```

6. Inside the container, run the Laravel migration command to set up the database:
   ```bash
   php artisan migrate
   ```

## API Usage

To use the API, make a GET request to the following endpoint:
### Curl example
```
curl --location 'http://<project-forward-host>:8001/public/api/validate' \
--header 'Accept: application/json'
```
----

```
http://<project-forward-host>:8001/public/api/validate
```

You will receive a response in the following format:

### Correct payload
```json
{"ip": "192.168.32.1", "agent": "PostmanRuntime/7.36.3", "log_uuid": "26a1c245-e369-4c85-93bb-3bfa8bea19a2"}
```
### Wrong payload
```json
{"ip": "192.168.32.1", "agent": "PostmanRuntime/7.36.3", "log_uuid": "123124qwe234"}
```

```json
{
   "message": [
      {
         "message": {
            "message": "valid-request-saved"
         },
         "status": 200
      },
      {
         "message": {
            "message": "The log_uuid must be a valid UUID.",
            "errors": {
               "log_uuid": [
                  "The log_uuid must be a valid UUID."
               ]
            }
         },
         "status": 422
      }
   ]
}
```

If you want to make an API call to the receiver container directly, you can make this API call:

```
curl --location 'http://project-receiver:8002/public/api/analyze' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
"log_uuid":"123e4567-e89b-12d3-a456-426655440000",
"ip" : "192.168.1.0",
"agent" : "MOZILLA"
}'
```

with the structure:

```json
{
"log_uuid" : "required|uuid",
"ip" : "required|ip",
"agent" : "required|string"
}
```


## Checking Database

To check the saved data in the database, follow these steps:

1. List all running Docker containers:
   ```bash
   docker ps
   ```

2. Note the container ID of the MySQL container.

3. Access the MySQL container's command line interface:
   ```bash
   docker exec -it <mysql_container_id> mysql -u project-receiver-u -p project-receiver-db
   ```

4. Enter the password when prompted (`project-receiver-p`).

5. Once logged in, switch to the `project-receiver-db` database:
   ```sql
   USE project-receiver-db;
   ```

6. Run a SELECT query to view the contents of the `payloads` table:
   ```sql
   SELECT * FROM payloads;
   ```
| id | payload_data | created_at          | updated_at          |
|----|--------------|---------------------|---------------------|
|  1 | {"ip": "192.168.32.1", "agent": "PostmanRuntime/7.36.3", "log_uuid": "26a1c245-e369-4c85-93bb-3bfa8bea19a2"} | 2024-02-26 10:35:13 | 2024-02-26 10:35:13 |

