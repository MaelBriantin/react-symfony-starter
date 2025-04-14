#!/bin/sh
set -e

echo "Starting..."

ROOT_DIR="${ROOT_DIR}"
ROOT_ENV_FILE="${ROOT_DIR}/.env"

echo "Checking for file: '${ROOT_ENV_FILE}'"
if [ ! -f "$ROOT_ENV_FILE" ]; then
  echo "Error: File not found: '${ROOT_ENV_FILE}'. Cannot generate secret." >&2
  exit 1
fi
echo "File found."

# Check if APP_SECRET is empty
echo "Checking if APP_SECRET is empty..."
if grep -q '^APP_SECRET=$' "$ROOT_ENV_FILE"; then
  echo "APP_SECRET is empty. Generating..."
  SECRET=$(openssl rand -hex 16)
  # Use uname for better cross-platform compatibility
  if [ "$(uname)" = "Darwin" ]; then
    sed -i '' "s/^APP_SECRET=$/APP_SECRET=$SECRET/" "$ROOT_ENV_FILE"
  else
    sed -i "s/^APP_SECRET=$/APP_SECRET=$SECRET/" "$ROOT_ENV_FILE"
  fi
  # Check sed exit status
  SED_EXIT_CODE=$?
  if [ $SED_EXIT_CODE -ne 0 ]; then
      echo "Error: sed command failed with exit code $SED_EXIT_CODE" >&2
      exit 1
  fi
  echo "Generated and added APP_SECRET."
else
   echo "APP_SECRET already set."
fi

echo "Finished."
