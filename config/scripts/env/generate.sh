#!/bin/sh
set -e

echo "Starting..."

# --- Read environment variables passed from Task ---
ROOT_DIR="${ROOT_DIR}"
ENV_TYPE="${ENV_TYPE:-prod}"
DEV_MYSQL_DATABASE="${DEV_MYSQL_DATABASE}"
DEV_MYSQL_USER="${DEV_MYSQL_USER}"
DEV_MYSQL_PASSWORD="${DEV_MYSQL_PASSWORD}"
DEV_MYSQL_ROOT_PASSWORD="${DEV_MYSQL_ROOT_PASSWORD}"
DEV_CLIENT_URL="${DEV_CLIENT_URL}"
DEV_API_URL="${DEV_API_URL}"
# -------------------------------------------------
echo "Received ENV_TYPE: '$ENV_TYPE'"

# Check if ROOT_DIR is set
if [ -z "$ROOT_DIR" ]; then
  echo "Error: ROOT_DIR environment variable is not set." >&2
  exit 1
fi

ROOT_ENV_FILE="${ROOT_DIR}/.env"
SOURCE_ENV_FILE="${ROOT_DIR}/.env.example"

echo "Checking for source file: ${SOURCE_ENV_FILE}"
# Check if source file exists
if [ ! -f "$SOURCE_ENV_FILE" ]; then
  echo "Error: Source file not found: '${SOURCE_ENV_FILE}'." >&2
  exit 1
fi
echo "Source file found."

# If .env does not exist, create it from .env.example
if [ ! -f "$ROOT_ENV_FILE" ]; then
  echo "Creating '$ROOT_ENV_FILE' from '$SOURCE_ENV_FILE'..."
  cp "$SOURCE_ENV_FILE" "$ROOT_ENV_FILE"
  if [ $? -ne 0 ]; then
    echo "Error: Failed to copy '$SOURCE_ENV_FILE' to '$ROOT_ENV_FILE'. Check permissions?" >&2
    exit 1
  fi
  echo "Created '$ROOT_ENV_FILE'."
fi

# Utility function: replace only if value is empty
replace_if_empty() {
  VAR_NAME="$1"
  VAR_VALUE="$2"
  sed -i "s|^${VAR_NAME}=$|${VAR_NAME}=${VAR_VALUE}|" "$ROOT_ENV_FILE"
}

# Apply dev defaults ONLY if ENV_TYPE is dev
if [ "$ENV_TYPE" = "dev" ]; then
  echo "Applying development defaults (ENV_TYPE=dev)..."
  replace_if_empty "APP_ENV" "dev"
  replace_if_empty "MYSQL_DATABASE" "$DEV_MYSQL_DATABASE"
  replace_if_empty "MYSQL_USER" "$DEV_MYSQL_USER"
  replace_if_empty "MYSQL_PASSWORD" "$DEV_MYSQL_PASSWORD"
  replace_if_empty "MYSQL_ROOT_PASSWORD" "$DEV_MYSQL_ROOT_PASSWORD"
  replace_if_empty "CLIENT_URL" "$DEV_CLIENT_URL"
  replace_if_empty "API_URL" "$DEV_API_URL"
  echo "Development defaults applied."
else
  replace_if_empty "APP_ENV" "prod"
  echo "Skipping development defaults (ENV_TYPE='$ENV_TYPE')."
fi

echo "Finished."
# Note: APP_SECRET generation is handled by generate-secret.sh
