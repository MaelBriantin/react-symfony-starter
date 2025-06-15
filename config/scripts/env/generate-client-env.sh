#!/bin/bash
ENV_FILE=".env"
CLIENT_ENV_FILE="app/client/.env"
DEFAULT_VARS=($CLIENT_ENV_VARS)
NOTES="#\n# This file is auto-generated at build time.\n# You can add your own variables here, prefixed with VITE_ if you want them to be available in the client with 'import.meta.env'.\n# You can also add any of the root .env variables here with 'task env:generate:client-env -- <var>'\n#\n"

ALL_VARS=("${DEFAULT_VARS[@]}" "$@")

VARS=($(printf "%s\n" "${ALL_VARS[@]}" | awk '!seen[$0]++'))

touch "$CLIENT_ENV_FILE"
echo -e "$NOTES" > "$CLIENT_ENV_FILE"

for VAR in "${VARS[@]}"; do
  VALUE=$(grep -E "^$VAR=" "$ENV_FILE" | cut -d '=' -f2-)
  if [ -n "$VALUE" ]; then
    if [ "$(uname)" = "Darwin" ]; then
      sed -i '' "/^VITE_${VAR}=.*/d" "$CLIENT_ENV_FILE"
    else
      sed -i "/^VITE_${VAR}=.*/d" "$CLIENT_ENV_FILE"
    fi
    echo "VITE_${VAR}=$VALUE" >> "$CLIENT_ENV_FILE"
  fi
done

echo "Client environment variables generated in '$CLIENT_ENV_FILE'."