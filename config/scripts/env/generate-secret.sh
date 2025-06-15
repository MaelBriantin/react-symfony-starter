#!/bin/bash
set -e

echo "Starting..."

# Validate ROOT_DIR
if [ -z "$ROOT_DIR" ]; then
  echo "Error: ROOT_DIR is not set or empty." >&2
  exit 1
fi
ROOT_ENV_FILE="${ROOT_DIR}/.env"

echo "ROOT_DIR: $ROOT_DIR"
echo "ROOT_ENV_FILE: $ROOT_ENV_FILE"

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
  SYSTYPE="$(uname)"
  echo "Replacing APP_SECRET with $SECRET using sed..."

  if [ "$SYSTYPE" = "Darwin" ]; then
    sed -i '' "s|^APP_SECRET=$|APP_SECRET=$SECRET|" "$ROOT_ENV_FILE"
  else
    sed -i "s|^APP_SECRET=$|APP_SECRET=$SECRET|" "$ROOT_ENV_FILE"
  fi

  echo "Generated and added APP_SECRET."
else
  echo "APP_SECRET already set."
fi

echo "Finished."
