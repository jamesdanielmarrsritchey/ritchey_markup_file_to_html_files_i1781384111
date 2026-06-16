<?php
# Name: Example
$var_7e7480cd_n_num = 1;
$var_7e7480cd_base_path_str = realpath(dirname(__FILE__, $var_7e7480cd_n_num));
$var_7e7480cd_source_path_str = $var_7e7480cd_base_path_str . '/Source';
while (is_dir($var_7e7480cd_source_path_str) !== TRUE) {
	$var_7e7480cd_n_num++;
	$var_7e7480cd_base_path_str = realpath(dirname(__FILE__, $var_7e7480cd_n_num));
	$var_7e7480cd_source_path_str = $var_7e7480cd_base_path_str . '/Source';
	if ($var_7e7480cd_n_num > 50){
		exit(1);
	}
}
require_once $var_7e7480cd_base_path_str . '/Source/ritchey_markup_file_to_html_files_i1781384111_v1.php';
$var_7e7480cd_return_boo = ritchey_markup_file_to_html_files_i1781384111_v1("{$var_7e7480cd_base_path_str}/Temporary/Example Document 1/Input/Markup.txt", "{$var_7e7480cd_base_path_str}/Temporary/Example Document 1/Output", "{$var_7e7480cd_base_path_str}/Source/Assets/minimal-theme-v1.css", TRUE, TRUE, TRUE);
if ($var_7e7480cd_return_boo === TRUE){
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
?>