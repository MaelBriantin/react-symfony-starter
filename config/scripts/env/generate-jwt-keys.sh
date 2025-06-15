#!/bin/bash
set -e

# --- Set paths for JWT keys ---
PRIVATE_KEY_PATH="$(pwd)/app/api/config/jwt/private.pem"
PUBLIC_KEY_PATH="$(pwd)/app/api/config/jwt/public.pem"

# --- Read environment variables passed from Task ---
ROOT_DIR="${ROOT_DIR}"
ENV_FILE="${ROOT_DIR}/.env"
JWT_SECRET_KEY="${JWT_SECRET_KEY}"
JWT_PUBLIC_KEY="${JWT_PUBLIC_KEY}"
JWT_TOKEN_LIFETIME="${JWT_TOKEN_LIFETIME}"

# --- If JWT keys are not set, generate them 
if [ ! -f "$PRIVATE_KEY_PATH" ] || [ ! -f "$PUBLIC_KEY_PATH" ]; then
  mkdir -p "$(dirname $PRIVATE_KEY_PATH)"
  openssl genrsa -out "$PRIVATE_KEY_PATH" 4096
  openssl rsa -pubout -in "$PRIVATE_KEY_PATH" -out "$PUBLIC_KEY_PATH"
  echo "JWT keys generated at $PRIVATE_KEY_PATH and $PUBLIC_KEY_PATH."
else
  echo "JWT keys already exist at $PRIVATE_KEY_PATH and $PUBLIC_KEY_PATH."
fi

# Generate a passphrase for JWT
JWT_PASSPHRASE=$(openssl rand -hex 32)

JWT_SECRET_KEY_ESCAPED=$(echo "$JWT_SECRET_KEY" | sed -e 's/[&\/]/\\&/g')
JWT_PUBLIC_KEY_ESCAPED=$(echo "$JWT_PUBLIC_KEY" | sed -e 's/[&\/]/\\&/g')
JWT_PASSPHRASE_ESCAPED=$(echo "$JWT_PASSPHRASE" | sed -e 's/[&\/]/\\&/g')
JWT_TOKEN_LIFETIME_ESCAPED=$(echo "$JWT_TOKEN_LIFETIME" | sed -e 's/[&\/]/\\&/g')

# Update JWT keys and passphrase in .env file
update_env_variable() {
  VAR_NAME="$1"
  VAR_VALUE="$2"
  TEMP_FILE="$ENV_FILE.tmp"
  if grep -q "^${VAR_NAME}=" "$ENV_FILE"; then
    sed -i '' "s|^${VAR_NAME}=.*$|${VAR_NAME}=${VAR_VALUE}|" "$ENV_FILE"
  else
    echo "${VAR_NAME}=${VAR_VALUE}" >> "$ENV_FILE"
  fi
}

update_env_variable "JWT_SECRET_KEY" "$JWT_SECRET_KEY_ESCAPED"
update_env_variable "JWT_PUBLIC_KEY" "$JWT_PUBLIC_KEY_ESCAPED"
update_env_variable "JWT_PASSPHRASE" "$JWT_PASSPHRASE_ESCAPED"
update_env_variable "JWT_TOKEN_LIFETIME" "$JWT_TOKEN_LIFETIME_ESCAPED"

echo "JWT keys and passphrase updated in $ENV_FILE."

