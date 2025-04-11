# Use Node.js 18 with Alpine Linux as the base image for a lightweight container
FROM node:18-alpine

# Set the working directory inside the container
WORKDIR /app

# Install pnpm package manager globally and configure its store directory for better caching
RUN npm install -g pnpm && pnpm config set store-dir /app/.pnpm-store

# Copy only package files first to leverage Docker layer caching for dependencies
COPY ./app/react/package.json ./app/react/pnpm-lock.yaml ./

# Install project dependencies using pnpm with exact versions from lockfile
RUN pnpm install --frozen-lockfile

# Copy the rest of the application code into the container
COPY ./app/react .

# Set the default command to run the development server
CMD ["pnpm", "dev"]

# Expose port 5173 which will be used by the Vite development server
EXPOSE 5173
