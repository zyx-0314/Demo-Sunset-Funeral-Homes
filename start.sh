#!/bin/bash
set -e

echo "🚀 Starting Docker Compose stack..."

# Ensure Docker Compose is available
if ! command -v docker compose &> /dev/null
then
  echo "⚠️ Docker Compose not found. Installing..."
  apt-get update -y && apt-get install -y docker-compose
fi

# Build and start the services in detached mode
docker compose up

# Follow logs of the main app service (optional)
echo "📜 Attaching to app logs..."
docker compose logs -f php
