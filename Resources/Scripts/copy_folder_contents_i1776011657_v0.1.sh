#!/bin/bash
# Name: Copy Folder Contents
# Description: Copy the contents of a folder to another specified (existing) folder.
# Usage: ./copy_folder_contents_i1776011657_v0.1.sh "/path/source" "/path/destination"

fun_a1b2c3d4_copy_folder_contents_i1776011657_v0_1() {

    local var_a1b2c3d4_source_directory_str="$1"
    local var_a1b2c3d4_destination_directory_str="$2"

    if [ ! -d "$var_a1b2c3d4_source_directory_str" ]; then
        echo "ERROR: Source directory does not exist:"
        echo "$var_a1b2c3d4_source_directory_str"
        return 1
    fi

    if [ ! -d "$var_a1b2c3d4_destination_directory_str" ]; then
        echo "ERROR: Destination directory does not exist:"
        echo "$var_a1b2c3d4_destination_directory_str"
        return 1
    fi

    echo "Copying:"
    echo "  Source      : $var_a1b2c3d4_source_directory_str"
    echo "  Destination : $var_a1b2c3d4_destination_directory_str"

    cp -av \
        "$var_a1b2c3d4_source_directory_str/." \
        "$var_a1b2c3d4_destination_directory_str/"

    local var_a1b2c3d4_exit_code_num=$?

    if [ "$var_a1b2c3d4_exit_code_num" -ne 0 ]; then
        echo "ERROR: cp failed with exit code $var_a1b2c3d4_exit_code_num"
        return 1
    fi

    echo "Copy completed successfully."

    return 0
}

fun_a1b2c3d4_copy_folder_contents_i1776011657_v0_1 "$1" "$2"