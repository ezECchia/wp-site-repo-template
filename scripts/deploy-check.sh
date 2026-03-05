#!/usr/bin/env bash
set -e

echo "=== Deploy Safety Check ==="

echo
echo "Checking staging files..."

BAD=$(find . \
  -name "stg-*.php" \
  -o -name "*.off" \
  -o -name "*.bak*" )

if [ -n "$BAD" ]; then
  echo
  echo "⚠️  WARNING: staging files detected"
  echo "$BAD"
  echo
  echo "Deploy aborted."
  exit 1
fi

echo
echo "Checking large debug files..."

BIG=$(find . -type f -size +20M ! -name "Dictionary.json")

if [ -n "$BIG" ]; then
  echo
  echo "⚠️  Large files detected (>20MB)"
  echo "$BIG"
fi

echo
echo "✔ Deploy safety check passed"
