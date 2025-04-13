FROM denoland/deno:2.2.9 AS base

WORKDIR /app

FROM base AS dev

COPY ./app/client .

RUN deno install

CMD ["deno", "run", "dev", "--host"]

EXPOSE 5173