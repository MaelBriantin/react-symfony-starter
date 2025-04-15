#!/bin/sh
set -e # Exit immediately if a command exits with a non-zero status.

echo "Starting..."

# --- Read environment variables passed from Task ---
ROOT_DIR="${ROOT_DIR}"
# Use the ENV_TYPE from environment, defaulting to 'dev' if empty/unset
ENV_TYPE="${ENV_TYPE:-prod}" # Changed default back to dev for safety
DEV_MYSQL_DATABASE="${DEV_MYSQL_DATABASE}"
DEV_MYSQL_USER="${DEV_MYSQL_USER}"
DEV_MYSQL_PASSWORD="${DEV_MYSQL_PASSWORD}"
DEV_MYSQL_ROOT_PASSWORD="${DEV_MYSQL_ROOT_PASSWORD}"
DEV_CLIENT_URL="${DEV_CLIENT_URL}"
DEV_API_URL="${DEV_API_URL}"
# -------------------------------------------------
echo "Received ENV_TYPE: '$ENV_TYPE'"

# Check if ROOT_DIR is actually set
if [ -z "$ROOT_DIR" ]; then
  echo "Error: ROOT_DIR environment variable is not set." >&2
  exit 1
fi

ROOT_ENV_FILE="${ROOT_DIR}/.env"
SOURCE_ENV_FILE="${ROOT_DIR}/.env.example" # Always use .env.example

echo "Checking for source file: ${SOURCE_ENV_FILE}"
# Check if source file exists
if [ ! -f "$SOURCE_ENV_FILE" ]; then
  echo "Error: Source file not found: '${SOURCE_ENV_FILE}'." >&2
  exit 1
fi
echo "Source file found."

# Always copy from .env.example, overwriting .env if it exists
echo "Copying/Overwriting '$ROOT_ENV_FILE' from '$SOURCE_ENV_FILE'..."
cp "$SOURCE_ENV_FILE" "$ROOT_ENV_FILE"
if [ $? -ne 0 ]; then
  echo "Error: Failed to copy '$SOURCE_ENV_FILE' to '$ROOT_ENV_FILE'. Check permissions?" >&2
  exit 1
fi
echo "Copied/Overwrote '$ROOT_ENV_FILE'."

# --- Apply dev defaults ONLY if ENV_TYPE is dev ---
if [ "$ENV_TYPE" = "dev" ]; then
  echo "Applying development defaults (ENV_TYPE=dev)..."
  # Use different delimiters for sed to avoid issues with passwords
  # Replace only if the line is exactly MYSQL_VAR= (empty value)
  # Handle sed -i differences between macOS and Linux
  if [ "$(uname)" = "Darwin" ]; then
    sed -i '' "s|^APP_ENV=$|APP_ENV=dev|" "$ROOT_ENV_FILE"
    sed -i '' "s|^MYSQL_DATABASE=$|MYSQL_DATABASE=${DEV_MYSQL_DATABASE}|" "$ROOT_ENV_FILE"
    sed -i '' "s|^MYSQL_USER=$|MYSQL_USER=${DEV_MYSQL_USER}|" "$ROOT_ENV_FILE"
    sed -i '' "s|^MYSQL_PASSWORD=$|MYSQL_PASSWORD=${DEV_MYSQL_PASSWORD}|" "$ROOT_ENV_FILE"
    sed -i '' "s|^MYSQL_ROOT_PASSWORD=$|MYSQL_ROOT_PASSWORD=${DEV_MYSQL_ROOT_PASSWORD}|" "$ROOT_ENV_FILE"
    sed -i '' "s|^CLIENT_URL=$|CLIENT_URL=${DEV_CLIENT_URL}|" "$ROOT_ENV_FILE"
    sed -i '' "s|^API_URL=$|API_URL=${DEV_API_URL}|" "$ROOT_ENV_FILE"
  else
    sed -i "s|^APP_ENV=$|APP_ENV=dev|" "$ROOT_ENV_FILE"
    sed -i "s|^MYSQL_DATABASE=$|MYSQL_DATABASE=${DEV_MYSQL_DATABASE}|" "$ROOT_ENV_FILE"
    sed -i "s|^MYSQL_USER=$|MYSQL_USER=${DEV_MYSQL_USER}|" "$ROOT_ENV_FILE"
    sed -i "s|^MYSQL_PASSWORD=$|MYSQL_PASSWORD=${DEV_MYSQL_PASSWORD}|" "$ROOT_ENV_FILE"
    sed -i "s|^MYSQL_ROOT_PASSWORD=$|MYSQL_ROOT_PASSWORD=${DEV_MYSQL_ROOT_PASSWORD}|" "$ROOT_ENV_FILE"
    sed -i "s|^CLIENT_URL=$|CLIENT_URL=${DEV_CLIENT_URL}|" "$ROOT_ENV_FILE"
    sed -i "s|^API_URL=$|API_URL=${DEV_API_URL}|" "$ROOT_ENV_FILE"
  fi
   echo "Development defaults applied."
else
  sed -i "s|^APP_ENV=$|APP_ENV=prod|" "$ROOT_ENV_FILE"
  echo "Skipping development defaults (ENV_TYPE='$ENV_TYPE')."
fi
# ----------------------------------------------------

echo "Finished."

# Note: APP_SECRET generation is handled by generate-secret.sh
