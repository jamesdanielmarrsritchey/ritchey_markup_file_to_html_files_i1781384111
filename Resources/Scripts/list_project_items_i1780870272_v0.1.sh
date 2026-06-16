#!/usr/bin/env bash
# Name: List Project Items
# Description: Creates a list of all files, and folders in the project, along with checksums for the files
# Usage: ./list_project_items_i1780870272_v0.1.sh
# Dependency Installation: sudo apt-get update && sudo apt-get install coreutils

fun_a7c91e2b_create_resource_listing_i1778975001_v1() {
	var_a7c91e2b_script_directory_str="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
	var_a7c91e2b_scripts_directory_name_str="$(basename "$var_a7c91e2b_script_directory_str")"
	var_a7c91e2b_resources_directory_str="$(dirname "$var_a7c91e2b_script_directory_str")"
	var_a7c91e2b_resources_directory_name_str="$(basename "$var_a7c91e2b_resources_directory_str")"
	var_a7c91e2b_parent_directory_str="$(dirname "$var_a7c91e2b_resources_directory_str")"

	if [ "$var_a7c91e2b_scripts_directory_name_str" != "Scripts" ] || [ "$var_a7c91e2b_resources_directory_name_str" != "Resources" ]; then
		echo "Error: This script is not in the expected path: .../Resources/Scripts" >&2
		exit 1
	fi

	find "$var_a7c91e2b_parent_directory_str" -print0 | while IFS= read -r -d '' var_a7c91e2b_item_path_str; do
		if [ -d "$var_a7c91e2b_item_path_str" ]; then
			printf 'Type: Directory, Path: %s\n' "$var_a7c91e2b_item_path_str"
		elif [ -f "$var_a7c91e2b_item_path_str" ]; then
			var_a7c91e2b_sha256_checksum_str="$(sha256sum "$var_a7c91e2b_item_path_str" | awk '{print $1}')"
			printf 'Type: File, Path: %s, SHA-256: %s\n' "$var_a7c91e2b_item_path_str" "$var_a7c91e2b_sha256_checksum_str"
		fi
	done
}

fun_a7c91e2b_create_resource_listing_i1778975001_v1