FROM node:22-alpine AS base

WORKDIR /app

RUN npm install -g pnpm && pnpm config set store-dir /app/.pnpm-store

FROM base AS dev
COPY ./app/client .
CMD ["pnpm", "dev"]
EXPOSE 5173