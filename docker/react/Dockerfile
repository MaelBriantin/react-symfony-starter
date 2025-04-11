# Use Node.js 18 with Alpine Linux as the base image for a lightweight container
FROM node:22-alpine AS base

# Set the working directory inside the container
WORKDIR /app

# Install pnpm package manager globally and configure its store directory for better caching
RUN npm install -g pnpm && pnpm config set store-dir /app/.pnpm-store

FROM base AS dev
# Copy the rest of the application code into the container
COPY ./app/react .
# Set the default command to run the development server
CMD ["pnpm", "dev"]
# Expose port 5173 which will be used by the Vite development server
EXPOSE 5173
