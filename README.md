# React Symfony Starter

This is a starter template for building a React application with Symfony as the backend. It provides a basic structure and configuration to help you get started quickly.

## Development Environment

### Requirements

To use this starter template, you need to have the following installed on your machine:
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Taskfile](https://taskfile.dev/installation/)

### Getting Started

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
    - create a `.env` file from the `.env.example` file with the needed environment variables (you don't need to change anything)
    - build the Docker containers for the Symfony backend and the React frontend (MYSQL, Node, PHP, Caddy)
    - start the Docker containers
    - install the PHP dependencies using `composer`
    - install the Node dependencies using `pnpm`
    - run the Caddy server via Docker

3. Access the application:
   - Symfony backend: [https://api.localhost](https://api.localhost)
   - React frontend: [https://localhost](http://localhost)

> **ℹ️ Note**  
> As **https** is used in the local environment, you'll need to accept the self-signed certificate in your browser.  
> Click on **Advanced** and then **Proceed to localhost (unsafe)**.
