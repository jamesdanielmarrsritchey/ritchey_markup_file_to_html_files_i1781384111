<?php
function fun_a4e2c8b7_get_image_filename_i1_v0_1(
	$var_a4e2c8b7_html_str
)
{
	if (is_string($var_a4e2c8b7_html_str) !== TRUE) {
		return FALSE;
	}

	$var_a4e2c8b7_match_arr = array();

	$var_a4e2c8b7_result_num = preg_match(
		'/<img\b[^>]*\bsrc\s*=\s*(["\'])(.*?)\1/i',
		$var_a4e2c8b7_html_str,
		$var_a4e2c8b7_match_arr
	);

	if ($var_a4e2c8b7_result_num !== 1) {
		return FALSE;
	}

	$var_a4e2c8b7_src_str = trim($var_a4e2c8b7_match_arr[2]);

	/* Reject URLs */
	if (
		preg_match('/^[a-z][a-z0-9+\-.]*:/i', $var_a4e2c8b7_src_str) === 1
	) {
		return FALSE;
	}

	/* Reject paths */
	if (
		strpos($var_a4e2c8b7_src_str, '/') !== FALSE ||
		strpos($var_a4e2c8b7_src_str, '\\') !== FALSE
	) {
		return FALSE;
	}

	/* Reject query strings and fragments */
	if (
		strpos($var_a4e2c8b7_src_str, '?') !== FALSE ||
		strpos($var_a4e2c8b7_src_str, '#') !== FALSE
	) {
		return FALSE;
	}

	/* Must look like a filename with an extension */
	if (
		preg_match(
			'/^[^<>:"\/\\\\|?*]+\.[a-z0-9]{1,10}$/i',
			$var_a4e2c8b7_src_str
		) !== 1
	) {
		return FALSE;
	}

	return $var_a4e2c8b7_src_str;
}
?>