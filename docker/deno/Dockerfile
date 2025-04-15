FROM denoland/deno:2.2.10 AS base

ARG UID=1000
ARG GID=1000

RUN groupadd --gid $GID user && \
    useradd --uid $UID --gid $GID --create-home --shell /bin/bash user

WORKDIR /app

RUN chown $UID:$GID /app


FROM base AS deps

ARG UID=1000 
ARG GID=1000 

ENV DENO_DIR=/home/user/.cache/deno 

USER $UID:$GID 

WORKDIR /app

RUN mkdir -p $DENO_DIR

USER $UID:$GID

COPY --chown=$UID:$GID ./app/client/deno.json* .
COPY --chown=$UID:$GID ./app/client/src/main.tsx ./src/main.tsx

RUN deno cache --node-modules-dir=auto src/main.tsx


FROM deps AS dev 

ARG UID=1000 
ARG GID=1000 

USER $UID:$GID 

COPY --chown=$UID:$GID ./app/client .

EXPOSE 5173

CMD ["deno", "task", "dev", "--host"]