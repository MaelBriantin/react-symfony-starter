ARG UID=1000

FROM node:20-alpine AS base

RUN adduser -D --uid $UID --home /home/user --gecos "" user || true
RUN corepack enable pnpm

WORKDIR /var/www

RUN chown ${UID}:node /var/www
USER ${UID}

FROM base AS dev
COPY --chown=${UID}:node ./app/react/package*.json ./
COPY --chown=${UID}:node ./app/react/pnpm-lock.yaml ./

RUN mkdir -p node_modules && chown ${UID}:node node_modules

RUN pnpm install

ENV PATH /var/www/node_modules/.bin:$PATH

COPY --chown=${UID}:node ./app/react .

EXPOSE 5173
CMD ["pnpm", "run", "dev"]


