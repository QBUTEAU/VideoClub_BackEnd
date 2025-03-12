# WR506D - Advanced Backend Development | QBUTEAU

### Welcome to the repository for my submission for resource R5.DWeb-DI.06!
This repository corresponds to a project closely related to resource R5.DWeb-DI.05 (Advanced Frontend Development) and is a back-end API for managing actors, movies, and movie genres (**CRUD**). This API is connected to a database and uses **API Platform** to expose all resources in a **RESTful** manner.

## Prerequisites
- **PHP** (> 7.4) 
- **Composer**
- **Symfony CLI**
- A **web server** (Apache or Nginx)
- A **DBMS** (MySQL)
- **Node.js** or **npm/yarn**
- **Git**

## Installation
### 1. Clone the repository
```
git clone https://github.com/QBUTEAU/VideoClub_WR506D.git
cd VideoClub_WR506D
```

### 2. Install dependencies
```
composer require
```

### 3. Configure the environment
Duplicate your `.env` file at the root of your folder to a file named `.env.local` and add this line (or uncomment one of the existing lines) if you are using a MySQL database:
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=10.8.8-MariaDB&charset=utf8mb4"
```
Replace `db_user` with your username, `db_password` with your password and `db_name` with your database name. 

### 4. Create your database
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

### 5. Load dummy data
Using **FakerCinemaProviders** in the project ([See the repository](https://github.com/JulienRAVIA/FakerCinemaProviders)), you can run this command to add new data to your database:
```
php bin/console doctrine:fixtures:load
```

## Start
You can now start your local Symfony server with this command:
```
symfony server:start
```

Make sure you have proper redirection through Apache on your machine.<br>
Wishing you good development!

## Developer & Teacher
- Quentin Buteau - B.U.T.3 MMI Student at IUT de Troyes
- Romain Delon - Teacher and Head of the MMI department at IUT de Troyes


### &copy; 2024 - Quentin BUTEAU | All rights reserved
