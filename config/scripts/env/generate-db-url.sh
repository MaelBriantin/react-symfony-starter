#!/bin/sh
set -e

echo "Starting..."

# --- Read environment variables passed from Task ---
ROOT_DIR="${ROOT_DIR}"
DOCKER_DB_HOST="${DOCKER_DB_HOST:-mysql}"
DOCKER_DB_PORT="${DOCKER_DB_PORT:-3306}"
DB_SERVER_VERSION="${DB_SERVER_VERSION:-8.0}"
DB_CHARSET="${DB_CHARSET:-utf8mb4}"
# -------------------------------------------------
echo "ROOT_DIR='${ROOT_DIR}'"

ROOT_ENV_FILE="${ROOT_DIR}/.env"

echo "Checking for file: ${ROOT_ENV_FILE}"
if [ ! -f "$ROOT_ENV_FILE" ]; then
  echo "Error: File not found: '${ROOT_ENV_FILE}'. Run 'task tools:generate-env' first." >&2
  exit 1
fi
echo "File found."

# Check if DATABASE_URL already exists and is set
echo "Checking if DATABASE_URL exists and is set..."
if grep -q '^DATABASE_URL=' "$ROOT_ENV_FILE"; then
  DATABASE_URL_VALUE=$(grep '^DATABASE_URL=' "$ROOT_ENV_FILE" | cut -d '=' -f2- | tr -d '"')
  if [ -n "$DATABASE_URL_VALUE" ]; then
    echo "DATABASE_URL already exists and is set."
  fi
fi
echo "DATABASE_URL does not exist or is empty."

# Read values from the .env file (using MYSQL_* names)
echo "Reading MYSQL_* variables from .env..."
MYSQL_USER=$(grep '^MYSQL_USER=' "$ROOT_ENV_FILE" | cut -d '=' -f2)
MYSQL_PASSWORD=$(grep '^MYSQL_PASSWORD=' "$ROOT_ENV_FILE" | cut -d '=' -f2)
MYSQL_DATABASE=$(grep '^MYSQL_DATABASE=' "$ROOT_ENV_FILE" | cut -d '=' -f2)
MYSQL_ROOT_PASSWORD_LINE=$(grep '^MYSQL_ROOT_PASSWORD=' "$ROOT_ENV_FILE")

# Check if essential variables were found in .env
if [ -z "$MYSQL_USER" ] || [ -z "$MYSQL_PASSWORD" ] || [ -z "$MYSQL_DATABASE" ]; then
  echo "Warning: Could not find all required MYSQL_* variables (USER, PASSWORD, DATABASE) in .env to generate DATABASE_URL." >&2
  echo "Please ensure you have edited .env with your credentials." >&2
  exit 1 # Exit with error if variables are missing
fi
echo "Required MYSQL_* variables found."

# Check if the anchor line for insertion exists
echo "Checking for anchor line 'MYSQL_ROOT_PASSWORD='..."
if [ -z "$MYSQL_ROOT_PASSWORD_LINE" ]; then
  echo "Warning: Could not find anchor line 'MYSQL_ROOT_PASSWORD=' in .env to insert DATABASE_URL after." >&2
  exit 1
fi
echo "Anchor line found."

if [ "$(uname)" = "Darwin" ]; then
  sed -i '' '/^DATABASE_URL=/d' "$ROOT_ENV_FILE"
else
  sed -i '/^DATABASE_URL=/d' "$ROOT_ENV_FILE"
fi

# Construct the URL using environment variables
echo "Constructing DATABASE_URL..."
DATABASE_URL="mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@${DOCKER_DB_HOST}:${DOCKER_DB_PORT}/${MYSQL_DATABASE}?serverVersion=${DB_SERVER_VERSION}&charset=${DB_CHARSET}"
DATABASE_URL_ESCAPED_SED=$(echo "$DATABASE_URL" | sed -e 's/[&\/]/\\&/g' -e 's/"/\\"/g')

if [ "$(uname)" = "Darwin" ]; then
  sed -i '' "s|^DATABASE_URL=.*$|DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"|" "$ROOT_ENV_FILE"
  sed -i '' "s|^DATABASE_URL=$|DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"|" "$ROOT_ENV_FILE"
  sed -i '' "s|^DATABASE_URL=\".*\"###< MYSQL configuration ###$||" "$ROOT_ENV_FILE"
  sed -i '' "/^###< MYSQL configuration ###/i\\
DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"\\
" "$ROOT_ENV_FILE"
else
  sed -i "s|^DATABASE_URL=.*$|DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"|" "$ROOT_ENV_FILE"
  sed -i "s|^DATABASE_URL=$|DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"|" "$ROOT_ENV_FILE"
  sed -i "s|^DATABASE_URL=\".*\"###< MYSQL configuration ###$||" "$ROOT_ENV_FILE"
  sed -i "/^###< MYSQL configuration ###/i DATABASE_URL=\"${DATABASE_URL_ESCAPED_SED}\"\\" "$ROOT_ENV_FILE"
fi

echo "Finished."
