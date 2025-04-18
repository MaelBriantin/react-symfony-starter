FROM node:22-alpine AS base

# Define arguments for user and group IDs, defaulting to 1000
ARG UID=1000
ARG GID=1000
ARG USERNAME=appuser

# Create a non-root user and group with specified IDs and name
# But since the node alpine image already has a node user, we need to remove it first
# This is a workaround to avoid conflicts with the existing node user and group
RUN deluser node > /dev/null 2>&1 || true \
    && delgroup node > /dev/null 2>&1 || true \
    && addgroup -g ${GID} ${USERNAME} \
    && adduser -u ${UID} -G ${USERNAME} -s /bin/sh -D ${USERNAME}

WORKDIR /app

RUN npm install -g pnpm

RUN chown ${USERNAME}:${USERNAME} /app

USER ${USERNAME}

RUN mkdir -p /home/${USERNAME}/.pnpm-store && chown ${USERNAME}:${USERNAME} /home/${USERNAME}/.pnpm-store

RUN pnpm config set store-dir /home/${USERNAME}/.pnpm-store


FROM base AS deps
ARG USERNAME=appuser # Ensure USERNAME is available in this stage

COPY --chown=${USERNAME}:${USERNAME} ./app/client/package.json ./app/client/pnpm-lock.yaml* ./

RUN pnpm install --frozen-lockfile


FROM deps AS dev
ARG USERNAME=appuser # Ensure USERNAME is available in this stage

COPY --chown=${USERNAME}:${USERNAME} ./app/client .

EXPOSE 5173

CMD ["pnpm", "dev"]