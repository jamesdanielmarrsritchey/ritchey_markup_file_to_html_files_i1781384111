<?php

function fun_a7c4d912_sort_array_by_extracted_digits_i1782253566_v0_3(
	array $var_a7c4d912_input_arr,
	string $var_a7c4d912_no_number_handling_str = 'discard',
	?string $var_a7c4d912_after_substring_str = '-'
)
{
	$var_a7c4d912_filtered_arr = array();

	foreach ($var_a7c4d912_input_arr as $var_a7c4d912_item_str)
	{
		$var_a7c4d912_sort_source_str = (string)$var_a7c4d912_item_str;

		if ($var_a7c4d912_after_substring_str !== null)
		{
			$var_a7c4d912_after_position_num = strpos(
				$var_a7c4d912_sort_source_str,
				$var_a7c4d912_after_substring_str
			);

			if ($var_a7c4d912_after_position_num !== false)
			{
				$var_a7c4d912_sort_source_str = substr(
					$var_a7c4d912_sort_source_str,
					$var_a7c4d912_after_position_num + strlen($var_a7c4d912_after_substring_str)
				);
			}
		}

		$var_a7c4d912_digits_str = preg_replace(
			'/\D+/',
			'',
			$var_a7c4d912_sort_source_str
		);

		if (
			$var_a7c4d912_digits_str === ''
			&&
			$var_a7c4d912_no_number_handling_str === 'discard'
		)
		{
			continue;
		}

		$var_a7c4d912_filtered_arr[] = $var_a7c4d912_item_str;
	}

	usort(
		$var_a7c4d912_filtered_arr,
		function (
			$var_a7c4d912_item_a_str,
			$var_a7c4d912_item_b_str
		)
		use (
			$var_a7c4d912_no_number_handling_str,
			$var_a7c4d912_after_substring_str
		)
		{
			$var_a7c4d912_sort_source_a_str = (string)$var_a7c4d912_item_a_str;
			$var_a7c4d912_sort_source_b_str = (string)$var_a7c4d912_item_b_str;

			if ($var_a7c4d912_after_substring_str !== null)
			{
				$var_a7c4d912_after_position_a_num = strpos(
					$var_a7c4d912_sort_source_a_str,
					$var_a7c4d912_after_substring_str
				);

				$var_a7c4d912_after_position_b_num = strpos(
					$var_a7c4d912_sort_source_b_str,
					$var_a7c4d912_after_substring_str
				);

				if ($var_a7c4d912_after_position_a_num !== false)
				{
					$var_a7c4d912_sort_source_a_str = substr(
						$var_a7c4d912_sort_source_a_str,
						$var_a7c4d912_after_position_a_num + strlen($var_a7c4d912_after_substring_str)
					);
				}

				if ($var_a7c4d912_after_position_b_num !== false)
				{
					$var_a7c4d912_sort_source_b_str = substr(
						$var_a7c4d912_sort_source_b_str,
						$var_a7c4d912_after_position_b_num + strlen($var_a7c4d912_after_substring_str)
					);
				}
			}

			$var_a7c4d912_digits_a_str = preg_replace(
				'/\D+/',
				'',
				$var_a7c4d912_sort_source_a_str
			);

			$var_a7c4d912_digits_b_str = preg_replace(
				'/\D+/',
				'',
				$var_a7c4d912_sort_source_b_str
			);

			$var_a7c4d912_has_number_a_boo = ($var_a7c4d912_digits_a_str !== '');
			$var_a7c4d912_has_number_b_boo = ($var_a7c4d912_digits_b_str !== '');

			if (
				$var_a7c4d912_has_number_a_boo == false
				||
				$var_a7c4d912_has_number_b_boo == false
			)
			{
				if (
					$var_a7c4d912_has_number_a_boo == false
					&&
					$var_a7c4d912_has_number_b_boo == false
				)
				{
					return 0;
				}

				if ($var_a7c4d912_no_number_handling_str === 'first')
				{
					return ($var_a7c4d912_has_number_a_boo == false) ? -1 : 1;
				}

				if ($var_a7c4d912_no_number_handling_str === 'last')
				{
					return ($var_a7c4d912_has_number_a_boo == false) ? 1 : -1;
				}
			}

			$var_a7c4d912_number_a_num = intval($var_a7c4d912_digits_a_str);
			$var_a7c4d912_number_b_num = intval($var_a7c4d912_digits_b_str);

			return $var_a7c4d912_number_a_num <=> $var_a7c4d912_number_b_num;
		}
	);

	return $var_a7c4d912_filtered_arr;
}
?>