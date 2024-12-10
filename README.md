# PHP-Apache-MySQL Development Environment  

This repository provides a Dockerized setup for a PHP-Apache-MySQL development stack. It includes:  
- A PHP environment running on Apache.  
- A MySQL database for data storage.  
- phpMyAdmin for managing the MySQL database.  

This setup is perfect for local development and testing.  

---

## Features  
- PHP 7+ running on Apache.  
- MySQL database configured with customizable credentials.  
- phpMyAdmin for an easy-to-use web interface to manage the database.  
- Volume mappings for persistent development data.  

---

## Prerequisites  
Ensure you have the following installed:  
- [Docker](https://www.docker.com/products/docker-desktop)  
- [Docker Compose](https://docs.docker.com/compose/install/)  

---

## Setup Instructions  

1. **Clone the Repository**  
   ```
   git clone <repository-url>  
   cd <repository-folder>  
   ```
2. **Configure Environment Variables**
    Open the docker-compose.yml file and set the following environment variables for the MySQL database:

    - ```MYSQL_ROOT_PASSWORD```: Set a secure root password.
    - ```MYSQL_DATABASE```: Define the database name.
    - ```MYSQL_USER```: Specify a user for the database.
    - ```MYSQL_PASSWORD```: Set the password for the database user.

3. **Build and Start the Services** 
    Run the following command to build and start the containers:
    ```
    docker-compose up --build
    ```

4. **Access the Services**
   - PHP application: http://localhost
   - phpMyAdmin: http://localhost/phpmyadmin

