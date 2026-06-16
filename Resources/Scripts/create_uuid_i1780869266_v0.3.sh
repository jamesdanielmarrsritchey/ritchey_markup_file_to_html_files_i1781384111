#!/bin/bash
# Name: Create UUID
# Description: Creates a UUID
# Usage: ./create_uuid_i1780869266_v0.3.sh
# Dependency Installation: sudo apt-get update && sudo apt-get install uuid-runtime

count_var="${1:-1}"

# Validate that the argument is a positive integer
if ! [[ "$count_var" =~ ^[0-9]+$ ]] || [ "$count_var" -lt 1 ]; then
    echo "Error: Parameter must be a positive integer."
    exit 1
fi

output_var=""

for ((i=1; i<=count_var; i++)); do
    uuid_var=$(uuidgen -r | tr -d '-')
    output_var="${output_var}${uuid_var}"
done

# Generate decimal CRC32
crc32_decimal_var=$(printf "%s" "$output_var" | cksum | awk '{print $1}')

# Convert to 8-character lowercase hexadecimal
crc32_hex_var=$(printf "%08x" "$crc32_decimal_var")

echo "$crc32_hex_var"