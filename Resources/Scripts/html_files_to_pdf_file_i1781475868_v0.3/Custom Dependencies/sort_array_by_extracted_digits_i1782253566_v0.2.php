<?php

function fun_a7c4d912_sort_array_by_extracted_digits_i1782253566_v0_2(
	array $var_a7c4d912_input_arr,
	string $var_a7c4d912_no_number_handling_str = 'discard'
)
{
	$var_a7c4d912_filtered_arr = array();

	foreach ($var_a7c4d912_input_arr as $var_a7c4d912_item_str)
	{
		$var_a7c4d912_digits_str = preg_replace(
			'/\D+/',
			'',
			(string)$var_a7c4d912_item_str
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
			$var_a7c4d912_no_number_handling_str
		)
		{
			$var_a7c4d912_digits_a_str = preg_replace(
				'/\D+/',
				'',
				(string)$var_a7c4d912_item_a_str
			);

			$var_a7c4d912_digits_b_str = preg_replace(
				'/\D+/',
				'',
				(string)$var_a7c4d912_item_b_str
			);

			$var_a7c4d912_has_number_a_boo =
				($var_a7c4d912_digits_a_str !== '');

			$var_a7c4d912_has_number_b_boo =
				($var_a7c4d912_digits_b_str !== '');

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

				if (
					$var_a7c4d912_no_number_handling_str === 'first'
				)
				{
					return (
						$var_a7c4d912_has_number_a_boo == false
					)
					? -1
					: 1;
				}

				if (
					$var_a7c4d912_no_number_handling_str === 'last'
				)
				{
					return (
						$var_a7c4d912_has_number_a_boo == false
					)
					? 1
					: -1;
				}
			}

			$var_a7c4d912_number_a_num = intval(
				$var_a7c4d912_digits_a_str
			);

			$var_a7c4d912_number_b_num = intval(
				$var_a7c4d912_digits_b_str
			);

			return
				$var_a7c4d912_number_a_num
				<=>
				$var_a7c4d912_number_b_num;
		}
	);

	return $var_a7c4d912_filtered_arr;
}

?>