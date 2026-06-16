<?php

function fun_e16a0092_get_recursive_file_list_i1781476833_v0_1(
	string $var_e16a0092_directory_path_str,
	?string $var_e16a0092_file_type_filter_str = NULL
) {

	if (
		is_dir($var_e16a0092_directory_path_str) !== TRUE
		||
		is_readable($var_e16a0092_directory_path_str) !== TRUE
	) {
		return FALSE;
	}

	try {

		$var_e16a0092_file_list_arr = [];

		$var_e16a0092_file_type_filter_lower_str = NULL;

		if ($var_e16a0092_file_type_filter_str !== NULL) {
			$var_e16a0092_file_type_filter_lower_str =
				strtolower($var_e16a0092_file_type_filter_str);
		}

		$var_e16a0092_iterator_obj = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$var_e16a0092_directory_path_str,
				FilesystemIterator::SKIP_DOTS
			)
		);

		foreach ($var_e16a0092_iterator_obj as $var_e16a0092_item_obj) {

			if ($var_e16a0092_item_obj->isFile() !== TRUE) {
				continue;
			}

			$var_e16a0092_file_path_str =
				realpath(
					$var_e16a0092_item_obj->getPathname()
				);

			if ($var_e16a0092_file_path_str === FALSE) {
				return FALSE;
			}

			if ($var_e16a0092_file_type_filter_lower_str !== NULL) {

				$var_e16a0092_file_name_str =
					basename($var_e16a0092_file_path_str);

				if (
					strtolower(
						substr(
							$var_e16a0092_file_name_str,
							-strlen(
								$var_e16a0092_file_type_filter_lower_str
							)
						)
					)
					!==
					$var_e16a0092_file_type_filter_lower_str
				) {
					continue;
				}
			}

			$var_e16a0092_file_list_arr[] =
				$var_e16a0092_file_path_str;
		}

		if (count($var_e16a0092_file_list_arr) === 0) {
			return TRUE;
		}

		return $var_e16a0092_file_list_arr;

	}
	catch (Exception $var_e16a0092_exception_obj) {
		return FALSE;
	}
}