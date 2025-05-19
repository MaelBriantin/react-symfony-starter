# React Symfony Starter

This is a starter template for building a React application with Symfony as the backend. It provides a basic structure and configuration to help you get started quickly.

## Table of Contents

- [Setting Up the Development Environment](#setting-up-the-development-environment)
  - [Requirements](#requirements)
  - [Installation](#installation)
- [API Documentation](#api-documentation)
   - [Hexagonal Architecture](#hexagonal-architecture)
   - [Authentication](#authentication)
      - [Basics](#basics)
      - [Authentication Flow](#authentication-flow)
      - [Configuring JWT Authentication](#configuring-jwt-authentication)



## Setting Up the Development Environment

### Requirements

To use this starter template, you need to have the following installed on your machine:
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/) (version 2 or higher)
- [Taskfile](https://taskfile.dev/installation/)

### Installation

To get started with the development environment, follow these steps:

1. Clone the repository:
   ```bash
   git clone MaelBriantin/react-symfony-starter.git
   cd react-symfony-starter
   ```

2. Run the development setup task:
   ```bash
    task dev:setup
    ```

    This command will run the following tasks:
    - create a `.env` file from the `.env.example` file with the needed environment variables
    - build the Docker containers for the Symfony backend and the React frontend (MYSQL, Node, PHP, Caddy)
    - start the Docker containers
    - install the PHP dependencies using `composer`
    - install the Node dependencies using `pnpm`
    - run the Caddy server via Docker

3. Access the application:
   - Symfony backend: [http://api.localhost](http://api.localhost)
   - React frontend: [http://localhost](http://localhost)

### Customizing the Environment

All environment variables are automatically generated when you run the `task dev:setup` command. But, if you want custom values, you can can generate a new `.env` file with the command `task env:generate:dev` first, change the values in the `.env` file, and then run the `task dev:setup` after that.

Some task commands are also available to help you with the creation of a custom `.env` file.
- `task env:generate:app-secret`: 
  Generates a new `APP_SECRET` value in the `.env` file.
- `task env:generate:jwt`: 
  Generates a new JWT passphrase, public key, and private key in the `.env` file.
- `task env:generate:db-url`: 
  Generates a new `DATABASE_URL` value in the `.env` file based on the current `MYSQL_DATABASE` and `MYSQL_USER` values.

## API Documentation

### Hexagonal Architecture

The Symfony backend follows a hexagonal architecture pattern, which separates the application logic from the infrastructure. The main components of the architecture are:
- **Domain**: Contains the core business logic and entities of the application.
- **Application**: Contains the application services and use cases that orchestrate the domain logic.
- **Infrastructure**: Contains the implementation details, such as database access, external APIs, and other technical concerns.

### Authentication

#### Basics

The Symfony backend uses the [Symfony Security component](https://symfony.com/doc/current/security.html) and [LexikJWTAuthenticationBundle](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html) for JWT authentication. 

#### Authentication Flow

The authentication process involves the following steps:
1. The user sends a POST request to the `<api-url>/login_check` endpoint with their credentials (username and password).
2. If the credentials are valid, the server responds with a JWT token.
3. The client stores the token and includes it in the `Authorization` header for subsequent requests to protected endpoints.
4. The server verifies the token and grants access to the requested resource if the token is valid.

#### Configuring JWT Authentication

To configure JWT authentication, you need to set the following environment variables in your `.env` file:
```dotenv
JWT_PASSPHRASE=your_jwt_passphrase
JWT_PUBLIC_KEY=your_jwt_public_key_path
JWT_PRIVATE_KEY=your_jwt_private_key_path
JWT_TOKEN_LIFETIME=jwt_token_lifetime_in_seconds
```

> **NOTE:** Needed environment variables and JWT keys and passphrase are automatically generated when you run the `task dev:setup` command. In production, you can generate the keys and passphrase with the command `task env:init:jwt` after the creation of your `.env` file. This command will generate the keys and passphrase in the `.env` file and create the keys in the `config/jwt` directory.
