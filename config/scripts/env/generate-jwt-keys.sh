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

# Update the .env file with the paths to the keys and the passphrase
sed -i "s|^JWT_SECRET_KEY=.*$|JWT_SECRET_KEY=$JWT_SECRET_KEY|" "$ENV_FILE"
sed -i "s|^JWT_PUBLIC_KEY=.*$|JWT_PUBLIC_KEY=$JWT_PUBLIC_KEY|" "$ENV_FILE"
sed -i "s|^JWT_PASSPHRASE=.*$|JWT_PASSPHRASE=$JWT_PASSPHRASE|" "$ENV_FILE"
sed -i "s|^JWT_TOKEN_LIFETIME=.*$|JWT_TOKEN_LIFETIME=$JWT_TOKEN_LIFETIME|" "$ENV_FILE"

echo "JWT keys and passphrase updated in $ENV_FILE."

