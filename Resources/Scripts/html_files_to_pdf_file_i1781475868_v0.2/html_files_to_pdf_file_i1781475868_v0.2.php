<?php
# Name: HTML Files To PDF File
# Description: Modified version of "/Demo/application.php", which creates the HTML files, converts the created HTML files to PDFs, merges the PDFs into a single PDF.
# Dependencies: Chromium (chromium), PHP (php8), PDF Unite (poppler-utils)
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
// Create HTML Files
require_once $var_7e7480cd_base_path_str . '/Source/ritchey_markup_file_to_html_files_i1781384111_v2.php';
$var_7e7480cd_return_boo = ritchey_markup_file_to_html_files_i1781384111_v2("{$var_7e7480cd_base_path_str}/Temporary 2/Example Document 1/Input/Markup.txt", "{$var_7e7480cd_base_path_str}/Temporary 2/Example Document 1/Output", "{$var_7e7480cd_base_path_str}/Source/Assets/ebook-theme-v1.css", TRUE, TRUE, TRUE);
if ($var_7e7480cd_return_boo === TRUE){
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
// Get a list of the HTML files created
require_once $var_7e7480cd_base_path_str . '/Resources/Scripts/html_files_to_pdf_file_i1781475868_v0.2/Custom Dependencies/get_recursive_file_list_i1781476833_v0.1.php';
$var_7e7480cd_html_files_arr = fun_e16a0092_get_recursive_file_list_i1781476833_v0_1("{$var_7e7480cd_base_path_str}/Temporary 2/Example Document 1/Output", ".html");
// Sort the array of files so they are in order by filename number (e.g., "1.html, 2.html, 10.html")
require_once $var_7e7480cd_base_path_str . '/Resources/Scripts/html_files_to_pdf_file_i1781475868_v0.2/Custom Dependencies/sort_array_by_extracted_digits_i1782253566_v0.3.php';
$var_7e7480cd_html_files_arr = fun_a7c4d912_sort_array_by_extracted_digits_i1782253566_v0_3($var_7e7480cd_html_files_arr, 'discard');
// For each HTML file create a version with added printing styles
$var_7e7480cd_html_chromium_files_arr = array();
$n = 0;
foreach ($var_7e7480cd_html_files_arr as &$item){
	$n++;
	$data = file_get_contents($item);
	$addition = <<<EOT
<style>
@media print {
    html, body {
        margin: 0;
        padding: 0;
    }
    @page {
        margin: 0;
        size: auto;
    }
}
</style>
EOT;
	$data = preg_replace('/<\/head>/i', $addition . "\n</head>", $data);
	$dirname = realpath(dirname($item));
	file_put_contents("{$dirname}/{$n}.html", $data);
	$var_7e7480cd_html_chromium_files_arr[] = "{$dirname}/{$n}.html";
}
unset($item);
// For each HTML file convert to PDF
$var_7e7480cd_pdf_files_arr = array();
$n = 0;
foreach ($var_7e7480cd_html_chromium_files_arr as &$item){
	$n++;
	$dirname = realpath(dirname($item));
	exec("chromium --headless --print-to-pdf=\"{$dirname}/{$n}.pdf\" \"{$item}\"");
	$var_7e7480cd_pdf_files_arr[] = "{$dirname}/{$n}.pdf";
}
unset($item);
// Merge all the PDFs
function fun_f8c1d4a2_combine_pdfs_i1_v0_1(
	array $arr_pdf_paths,
	string $str_output_pdf
): bool {

	// Must have at least one input PDF
	if (count($arr_pdf_paths) < 1) {
		return false;
	}

	// Validate input files
	foreach ($arr_pdf_paths as $str_pdf_path) {

		if (
			is_string($str_pdf_path) !== true ||
			file_exists($str_pdf_path) !== true ||
			is_readable($str_pdf_path) !== true
		) {
			return false;
		}
	}

	// Build command
	$str_command = 'pdfunite';

	foreach ($arr_pdf_paths as $str_pdf_path) {
		$str_command .= ' ' . escapeshellarg($str_pdf_path);
	}

	$str_command .= ' ' . escapeshellarg($str_output_pdf);

	// Execute command
	$arr_output = [];
	$int_return_code = 0;

	exec($str_command . ' 2>&1', $arr_output, $int_return_code);

	// Verify success
	if (
		$int_return_code === 0 &&
		file_exists($str_output_pdf) === true &&
		filesize($str_output_pdf) > 0
	) {
		return true;
	}

	return false;
}

$var_7e7480cd_return_boo = fun_f8c1d4a2_combine_pdfs_i1_v0_1($var_7e7480cd_pdf_files_arr, "{$var_7e7480cd_base_path_str}/Temporary 2/Example Document 1/Output/Combined.pdf");
if ($var_7e7480cd_return_boo === TRUE){
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
?>