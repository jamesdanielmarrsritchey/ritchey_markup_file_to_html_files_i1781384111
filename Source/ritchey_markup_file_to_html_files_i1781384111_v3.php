<?php
# Meta
/*
Name: Ritchey Markup File To HTML Files i1781384111 v3
Description: Convert text (marked using a custom markup language) to HTML files. Returns "TRUE" on success. Returns "FALSE" on failure.
Notes:
- Optional arguments can be "NULL" to skip them in which case they will use default values.
- The HTML document(s) produced do not follow common design practices, because they are intended for viewing as local documents, not for serving as a website. They will still work, but aren't well optimized for such use.
- Any images used in the document need to be present in the destination_folder.
Argument List:
- source_file (required) is the file to read from. The folder it is in is also used as the base for copying any local image files to the destination folder.
- destination_folder (required) the path of where to write the HTML files. 
- css_file (optional) is a path to a css file import into the HTML. 
- preserve_empty_lines (optional) specifies whether to preserve empty lines, or ignore them. 
- overwrite (optional) specifies whether it's okay to write over the destination files, if they already exist. 
- display_errors (optional) indicates if errors should be displayed.
*/
# Extractable Information
/*
"
source_file: file, required
destination_folder: path, required
css_file: file, optional
preserve_empty_lines: bool, optional
overwrite: bool, optional
display_errors: bool, optional
"
*/
# Content
if (function_exists('ritchey_markup_file_to_html_files_i1781384111_v3') === FALSE){
function ritchey_markup_file_to_html_files_i1781384111_v3($source_file, $destination_folder, $css_file = NULL, $preserve_empty_lines = NULL, $overwrite = NULL, $display_errors = NULL){
	$errors = array();
	$var_fb0366f4_location_str = realpath(dirname(__FILE__));
	$var_fb0366f4_n_num = 1;
	$var_fb0366f4_base_path_str = realpath(dirname(__FILE__, $var_fb0366f4_n_num));
	$var_fb0366f4_source_path_str = $var_fb0366f4_base_path_str . '/Source';
	while (is_dir($var_fb0366f4_source_path_str) !== TRUE) {
		$var_fb0366f4_n_num++;
		$var_fb0366f4_base_path_str = realpath(dirname(__FILE__, $var_fb0366f4_n_num));
		$var_fb0366f4_source_path_str = $var_fb0366f4_base_path_str . '/Source';
		if ($var_fb0366f4_n_num > 50){
			exit(1);
		}
	}
	if (@is_file($source_file) === FALSE){
		$errors[] = "source_file";
	}
	if (@is_dir($destination_folder) === FALSE){
		$errors[] = 'destination_folder';
	}
	if ($css_file === NULL){
		$css_file = FALSE;
	} else if (@is_file($css_file) === TRUE){
		// Do nothing
	} else {
		$errors[] = "css_file";
	}
	if ($preserve_empty_lines === NULL){
		$preserve_empty_lines = TRUE;
	} else if ($preserve_empty_lines === TRUE){
		// Do nothing
	} else if ($preserve_empty_lines === FALSE){
		// Do nothing
	} else {
		$errors[] = "preserve_empty_lines";
	}
	if ($overwrite === NULL){
		$overwrite = FALSE;
	} else if ($overwrite === TRUE){
		// Do nothing
	} else if ($overwrite === FALSE){
		// Do nothing
	} else {
		$errors[] = "overwrite";
	}
	if ($display_errors === NULL){
		$display_errors = FALSE;
	} else if ($display_errors === TRUE){
		// Do nothing
	} else if ($display_errors === FALSE){
		// Do nothing
	} else {
		$errors[] = "display_errors";
	}
	## Task
	if (@empty($errors) === TRUE){
		### Import text
		$data = array();
		$handle = @fopen($source_file, 'r');
		while (@feof($handle) !== TRUE) {
			// Get line from file
			$line = @fgets($handle);
			$line = rtrim($line, "\n\r\v");
			$data[] = $line;
		}
		@fclose($handle);
		### Deletions
		// Remove commented lines
		foreach ($data as $key => $value) {
			// When encountering a commented line, remove it.
			if (substr($value, 0, 2) === '//'){
				unset($data[$key]);
			}
		}
		### Empty Lines
		if ($preserve_empty_lines === TRUE){
			foreach ($data as &$value){
			// When encountering an empty line mark it.
				if (trim($value) === ''){
					$value = '// Empty Line Entry Start';
				}
			}
			unset($value);
		}
		### Add more markup so replacements are easier.
		// Pages
		$markup_1 = '// Page Start';
		$markup_2 = '// Page End';
		array_unshift($data, $markup_1);
		foreach ($data as &$value){
			// When encountering end of page markup, close the page, and open another.
			if (rtrim($value) === '--'){
				$value = $value . PHP_EOL . $markup_2 . PHP_EOL . $markup_1;
			}
		}
		unset($value);
		// Close last page.
		$data[] = $markup_2;
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 0
		$markup_1 = '// Section 0 Start';
		$markup_2 = '// Section 0 End';
		foreach ($data as &$value){
			// When encountering start of page, open section
			if (rtrim($value) === '// Page Start'){
				$value = $value . PHP_EOL . $markup_1;
			}
			// When encountering end of page, close section
			else if (rtrim($value) === '// Page End'){
				$value = $markup_2 . PHP_EOL . $value;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 1
		$switch = FALSE;
		$markup_1 = '// Section 1 Start';
		$markup_2 = '// Section 1 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 2) === '# ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S1, close it
			else if (substr($value, 0, 2) === '# ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 2
		$switch = FALSE;
		$markup_1 = '// Section 2 Start';
		$markup_2 = '// Section 2 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 3) === '## ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S2, close it
			else if (substr($value, 0, 3) === '## ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S1 start/end, close it
			else if (substr($value, 0, 12) === '// Section 1' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 3
		$switch = FALSE;
		$markup_1 = '// Section 3 Start';
		$markup_2 = '// Section 3 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 4) === '### ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S3, close it
			else if (substr($value, 0, 4) === '### ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S2 start/end, close it
			else if (substr($value, 0, 12) === '// Section 2' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S1 start/end, close it
			else if (substr($value, 0, 12) === '// Section 1' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 4
		$switch = FALSE;
		$markup_1 = '// Section 4 Start';
		$markup_2 = '// Section 4 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 5) === '#### ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S4, close it
			else if (substr($value, 0, 5) === '#### ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S3 start/end, close it
			else if (substr($value, 0, 12) === '// Section 3' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S2 start/end, close it
			else if (substr($value, 0, 12) === '// Section 2' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S1 start/end, close it
			else if (substr($value, 0, 12) === '// Section 1' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 5
		$switch = FALSE;
		$markup_1 = '// Section 5 Start';
		$markup_2 = '// Section 5 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 6) === '##### ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S5, close it
			else if (substr($value, 0, 6) === '##### ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S4 start/end, close it
			else if (substr($value, 0, 12) === '// Section 4' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S3 start/end, close it
			else if (substr($value, 0, 12) === '// Section 3' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S2 start/end, close it
			else if (substr($value, 0, 12) === '// Section 2' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S1 start/end, close it
			else if (substr($value, 0, 12) === '// Section 1' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Section 6
		$switch = FALSE;
		$markup_1 = '// Section 6 Start';
		$markup_2 = '// Section 6 End';
		foreach ($data as &$value){
			// When encountering start of section, open it
			if (substr($value, 0, 7) === '###### ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $value;
				$switch = TRUE;
			}
			// When encountering another S6, close it
			else if (substr($value, 0, 7) === '###### ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S5 start/end, close it
			else if (substr($value, 0, 12) === '// Section 5' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S4 start/end, close it
			else if (substr($value, 0, 12) === '// Section 4' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S3 start/end, close it
			else if (substr($value, 0, 12) === '// Section 3' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S2 start/end, close it
			else if (substr($value, 0, 12) === '// Section 2' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S1 start/end, close it
			else if (substr($value, 0, 12) === '// Section 1' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
			// When encountering an S0 start/end, close it
			else if (substr($value, 0, 12) === '// Section 0' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Blockquotes
		$switch = FALSE;
		$markup_1 = '// Blockquote Start';
		$markup_2 = '// Blockquote End';
		foreach ($data as &$value){
			// When encountering start of blockquote, open it.
			if (rtrim($value) === '"' && $switch === FALSE){
				$value = $markup_1; // This replaces the "
				$switch = TRUE;
			}
			// When encountering end of blockquote, close it.
			else if (rtrim($value) === '"' && $switch === TRUE){
				$value = $markup_2; // This replaces the "
				$switch = FALSE;
			}
			// Add blockers
			else if ($switch === TRUE){
				$value = "\\\\ {$value}";
			}
		}
		unset($value);
		// Blockmessages
		$switch = FALSE;
		$markup_1 = '// Blockmessage Start';
		$markup_2 = '// Blockmessage End';
		foreach ($data as &$value){
			// When encountering start of blockmessage, open it.
			if (rtrim($value) === '(' && $switch === FALSE){
				$value = $markup_1; // This replaces the =
				$switch = TRUE;
			}
			// When encountering end of blockmessage, close it.
			else if (rtrim($value) === ')' && $switch === TRUE){
				$value = $markup_2; // This replaces the =
				$switch = FALSE;
			}
			// Add blockers
			else if ($switch === TRUE){
				$value = "\\\\ {$value}";
			}
		}
		unset($value);
		// Dot Lists
		$switch = FALSE;
		$markup_1 = '// Dot List Start';
		$markup_2 = '// Dot List End';
		$markup_3 = '// Dot List Entry Start';
		$markup_4 = '// Dot List Entry End';
		foreach ($data as &$value){
			// When encountering start of dot list, open it.
			if (substr(ltrim($value), 0, 2) === '- ' && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $markup_3 . PHP_EOL . $value . PHP_EOL . $markup_4;
				$switch = TRUE;
			}
			// When encountering dot list entry, open and close it.
			else if (substr(ltrim($value), 0, 2) === '- ' && $switch === TRUE){
				$value = $markup_3 . PHP_EOL . $value . PHP_EOL . $markup_4;
				$switch = TRUE;
			}
			// When encountering end of dot list, close it.
			else if (substr(ltrim($value), 0, 2) !== '- ' && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Numbered Lists
		$switch = FALSE;
		$markup_1 = '// Numbered List Start';
		$markup_2 = '// Numbered List End';
		$markup_3 = '// Numbered List Entry Start';
		$markup_4 = '// Numbered List Entry End';
		foreach ($data as &$value){
			// When encountering start of numbered list, open it.
			if (preg_match('/^\d+\. +/', ltrim($value)) === 1 && $switch === FALSE){
				$value = $markup_1 . PHP_EOL . $markup_3 . PHP_EOL . $value . PHP_EOL . $markup_4;
				$switch = TRUE;
			}
			// When encountering numbered list entry, open and close it.
			else if (preg_match('/^\d+\. +/', ltrim($value)) === 1 && $switch === TRUE){
				$value = $markup_3 . PHP_EOL . $value . PHP_EOL . $markup_4;
				$switch = TRUE;
			}
			// When encountering numbered of dot list, close it.
			else if (preg_match('/^\d+\. +/', ltrim($value)) !== 1 && $switch === TRUE){
				$value = $markup_2 . PHP_EOL . $value;
				$switch = FALSE;
			}
		}
		unset($value);	
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Remove blockers "\\ ". They were needed for block messages, and block quotes
		foreach ($data as &$value){
			// When encountering, remove.
			if (substr($value, 0, 3) === '\\\\ '){
				$value = substr($value, 3);
			}
		}
		unset($value);
		// Blockstyled
		$switch = FALSE;
		$markup_1 = '// Blockstyled Start';
		$markup_2 = '// Blockstyled End';
		foreach ($data as &$value){
			// When encountering start of blockstyled, open it.
			if (rtrim($value) === '[' && $switch === FALSE){
				$value = $markup_1; // This replaces the ~
				$switch = TRUE;
			}
			// When encountering end of blockstyled, close it.
			else if (rtrim($value) === ']' && $switch === TRUE){
				$value = $markup_2; // This replaces the ~
				$switch = FALSE;
			}
		}
		unset($value);
		### Replace markup with HTML (Multi-line Items)
		// Pages
		$markup_1 = '// Page Start';
		$markup_2 = '// Page End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "page{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='page_outter' id='page_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='page_inner' id='page_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='page_meta' id='page_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ <span class='page_number'>{$number}</span>
\\\\ </div>
\\\\ <div class='page_content' id='page_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>
\\\\ <!-- Page Break -->
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);
		// Section 0
		$markup_1 = '// Section 0 Start';
		$markup_2 = '// Section 0 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_0_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_0_outter' id='section_0_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_0_inner' id='section_0_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_0_meta' id='section_0_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_0_content' id='section_0_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);
		// Section 1
		$markup_1 = '// Section 1 Start';
		$markup_2 = '// Section 1 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_1_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_1_outter' id='section_1_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_1_inner' id='section_1_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_1_meta' id='section_1_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_1_content' id='section_1_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);
		// Section 2
		$markup_1 = '// Section 2 Start';
		$markup_2 = '// Section 2 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_2_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_2_outter' id='section_2_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_2_inner' id='section_2_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_2_meta' id='section_2_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_2_content' id='section_2_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);	
		// Section 3
		$markup_1 = '// Section 3 Start';
		$markup_2 = '// Section 3 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_3_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_3_outter' id='section_3_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_3_inner' id='section_3_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_3_meta' id='section_3_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_3_content' id='section_3_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);			
		// Section 4
		$markup_1 = '// Section 4 Start';
		$markup_2 = '// Section 4 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_4_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_4_outter' id='section_4_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_4_inner' id='section_4_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_4_meta' id='section_4_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_4_content' id='section_4_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);			
		// Section 5
		$markup_1 = '// Section 5 Start';
		$markup_2 = '// Section 5 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_5_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_5_outter' id='section_5_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_5_inner' id='section_5_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_5_meta' id='section_5_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_5_content' id='section_5_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);			
		// Section 6
		$markup_1 = '// Section 6 Start';
		$markup_2 = '// Section 6 End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "section_6_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='section_6_outter' id='section_6_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='section_6_inner' id='section_6_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='section_6_meta' id='section_6_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='section_6_content' id='section_6_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);			
		// Blockquotes
		$markup_1 = '// Blockquote Start';
		$markup_2 = '// Blockquote End';
		$number = 1;
		$switch = FALSE;
		foreach ($data as &$value){
			$uuid = hash('md5', "blockquote_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='blockquote_outter' id='blockquote_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='blockquote_inner' id='blockquote_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='blockquote_meta' id='blockquote_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='blockquote_content' id='blockquote_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
				$switch = TRUE;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
				$switch = FALSE;
			}
			// If value is the content of the block, add blockers to prevent other replacements running on that line.
			else if ($switch === TRUE){
				if ($value === '// Empty Line Entry Start'){
					$value = '\\\\ <br>';
				} else {
					$value = "\\\\ {$value}<br>";
				}
			}
		}
		unset($value);
		// Blockmessages
		$markup_1 = '// Blockmessage Start';
		$markup_2 = '// Blockmessage End';
		$number = 1;
		$switch = FALSE;
		foreach ($data as &$value){
			$uuid = hash('md5', "blockmessage_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='blockmessage_outter' id='blockmessage_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='blockmessage_inner' id='blockmessage_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='blockmessage_meta' id='blockmessage_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='blockmessage_content' id='blockmessage_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
			// If value is the content of the block, add blockers to prevent other replacements running on that line.
			else if ($switch === TRUE){
				if ($value === '// Empty Line Entry Start'){
					$value = '\\\\ <br>';
				} else {
					$value = "\\\\ {$value}<br>";
				}
			}
		}
		unset($value);	
		// Blockstyled
		$markup_1 = '// Blockstyled Start';
		$markup_2 = '// Blockstyled End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "blockstyled_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='blockstyled_outter' id='blockstyled_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='blockstyled_inner' id='blockstyled_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='blockstyled_meta' id='blockstyled_{$number}_meta' data-uuid='{$uuid}_3'>
\\\\ </div>
\\\\ <div class='blockstyled_content' id='blockstyled_{$number}_content' data-uuid='{$uuid}_4'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);	
		// Dot Lists
		// Create the outter portion of the dot list
		$markup_1 = '// Dot List Start';
		$markup_2 = '// Dot List End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "dotlist_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='dotlist_outter' id='dotlist_{$number}_outter' data-uuid='{$uuid}_1'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);	
		// Create the entry portion of the dot list
		$markup_1 = '// Dot List Entry Start';
		$markup_2 = '// Dot List Entry End';
		$level = 1;
		foreach ($data as &$value){
			$html_1 = <<<HTML1
\\\\ <div class='dotlist_entry'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
			// Add level
			else if (substr(ltrim($value), 0, 2) === '- '){
				$level = intval(strpos($value, '-')) + 1;
				$value = "\\\\e001 <span class='dot_{$level}'>&bull;</span> " . preg_replace('/^\s*-\s*/', '', $value);
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Numbered Lists
		// Create the outter portion of the numbered list
		$markup_1 = '// Numbered List Start';
		$markup_2 = '// Numbered List End';
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "numberedlist_{$number}");
			$html_1 = <<<HTML1
\\\\ <div class='numberedlist_outter' id='numberedlist_{$number}_outter' data-uuid='{$uuid}_1'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
		}
		unset($value);	
		// Create the entry portion of the numbered list
		$markup_1 = '// Numbered List Entry Start';
		$markup_2 = '// Numbered List Entry End';
		$level = 1;
		foreach ($data as &$value){
			$html_1 = <<<HTML1
\\\\ <div class='numberedlist_entry'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>		
HTML2;
			// When encountering start, replace with start.
			if ($value === $markup_1){
				$value = $html_1;
				$number++;
			}
			// When encountering end, replace with end.
			else if ($value === $markup_2){
				$value = $html_2;
			}
			// Add level
			else if (preg_match('/^\d+\. +/', ltrim($value)) === 1){
				$level = preg_match('/\d/', $value, $matches, PREG_OFFSET_CAPTURE);
				$level = $matches[0][1];
				$level = intval($level) + 1;
				$bullet_number = preg_replace('/^\s*(\d+\.)\s.*$/', '$1', $value);
				$value = "\\\\e001 <span class='bullnum_{$level}'>{$bullet_number}</span> " . preg_replace('/^\s*\d+\.\s+/', '', $value);
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		### Replace markup with HTML (Single-line Items)
		// Empty Lines
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "br_{$number}");
			$html_1 = <<<HTML1
\\\\ <br class='br_content' id='br_{$number}_content' data-uuid='{$uuid}_1'>
HTML1;
			// When encountering start, replace with start.
			if ($value === '// Empty Line Entry Start'){
				$value = $html_1;
				$number++;
			}
		}
		unset($value);
		// Heading 1
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h1_outter' id='h1_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h1_inner' id='h1_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h1 class='h1_content' id='h1_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h1>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 2) === '# '){
				$value = $html_1 . substr($value, 2) . $html_2;
				$number++;
			}
		}
		unset($value);	
		// Heading 2
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h2_outter' id='h2_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h2_inner' id='h2_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h2 class='h2_content' id='h2_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h2>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 3) === '## '){
				$value = $html_1 . substr($value, 3) . $html_2;
				$number++;
			}
		}
		unset($value);	
		// Heading 3
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h3_outter' id='h3_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h3_inner' id='h3_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h3 class='h3_content' id='h3_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h3>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 4) === '### '){
				$value = $html_1 . substr($value, 4) . $html_2;
				$number++;
			}
		}
		unset($value);
		// Heading 4
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h4_outter' id='h4_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h4_inner' id='h4_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h4 class='h4_content' id='h4_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h4>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 5) === '#### '){
				$value = $html_1 . substr($value, 5) . $html_2;
				$number++;
			}
		}
		unset($value);
		// Heading 5
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h5_outter' id='h5_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h5_inner' id='h5_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h5 class='h5_content' id='h5_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h5>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 6) === '##### '){
				$value = $html_1 . substr($value, 6) . $html_2;
				$number++;
			}
		}
		unset($value);
		// Heading 6
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', $value);
			$html_1 = <<<HTML1
\\\\ <div class='h6_outter' id='h6_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='h6_inner' id='h6_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <h6 class='h6_content' id='h6_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
</h6>
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 7) === '###### '){
				$value = $html_1 . substr($value, 7) . $html_2;
				$number++;
			}
		}
		unset($value);
		// Page break (hr)
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "{$number}{$value}");
			$html_1 = <<<HTML1
\\\\ <div class='hr_outter' id='hr_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='hr_inner' id='hr_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <hr class='hr_content' id='hr_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>	
HTML2;
			// When encountering start, replace with start.
			if (substr($value, 0, 2) === '--'){
				$value = $html_1 . PHP_EOL . $html_2;
				$number++;
			}
		}
		unset($value);
		// Tabs
		foreach ($data as &$value){
			$html_1 = <<<HTML1
<span class='tab_outter'>
HTML1;
			$html_2 = <<<HTML2
</span>
HTML2;
			// When encountering start, replace with start.
			if (strpos($value, "\t") === 0){
				$value = $html_1 . $html_2 . substr($value, 1);
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Labels
		function regex_callback_for_labels_v1($matches) {
			$uuid = hash('md5', $matches[0]);
			$html_1 = <<<HTML1
<span class='label_outter' data-uuid='{$uuid}_1'><span class='label_text' data-uuid='{$uuid}_2'>
HTML1;
			$html_2 = <<<HTML2
</span><span class='label_end' data-uuid='{$uuid}_3'>:</span></span>
HTML2;		
			$return = $html_1 . ucwords(strtolower($matches[1])) . $html_2;
    		return $return;
		}
		foreach ($data as &$value){
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\' && substr($value, 0, 3) !== '\\\\e'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (strpos($value, '<') === FALSE || substr($value, 0, 6) === '\\\\e001'){ // So it doesn't run in lines that have HTML elements, unless they've got a blocker with an exception
				if (preg_match('/(?<!\S)([^a-z]*?):/', $value) === 1){
					$value = preg_replace_callback('/(?<!\S)([^a-z]*?):/', 'regex_callback_for_labels_v1', $value, 1);
				}
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Tags
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "{$number}{$value}");
			$html_1 = <<<HTML1
\\\\ <div class='tags_outter' id='tags_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='tags_inner' id='tags_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='tags_content' id='tags_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>	
HTML2;
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering tags start, replace with start, and end
			else if (substr($value, 0, 1) === '[' && substr($value, -1) === ']'){
				// Add individual tags
				$value = explode('][', substr($value, 1, -1));
				foreach ($value as &$value_2){
					$value_2 = "\\\\ <div class='tag_entry'>{$value_2}</div>";
				}
				unset($value_2);
				$value = implode(PHP_EOL, $value);
				// Add tags element
				$value = $html_1 . PHP_EOL . $value . PHP_EOL . $html_2;
				$number++;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Flat-lists (made as single row tables)
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "{$number}{$value}");
			$html_1 = <<<HTML1
\\\\ <div class='flatlist_outter' id='flatlist_{$number}_outter' data-uuid='{$uuid}_1'>
\\\\ <div class='flatlist_inner' id='flatlist_{$number}_inner' data-uuid='{$uuid}_2'>
\\\\ <div class='flatlist_content' id='flatlist_{$number}_content' data-uuid='{$uuid}_3'>
HTML1;
			$html_2 = <<<HTML2
\\\\ </div>
\\\\ </div>
\\\\ </div>	
HTML2;
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering start, replace with start, and end
			else if (substr($value, 0, 2) === '| '){
				// Add individual entries
				$value = explode(' | ', substr($value, 2));
				foreach ($value as &$value_2){
					$value_2 = "\\\\ <div class='flatlist_entry'>{$value_2}</div>";
				}
				unset($value_2);
				$value = implode(PHP_EOL, $value);
				// Add list element
				$value = $html_1 . PHP_EOL . $value . PHP_EOL . $html_2;
				$number++;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Special comments (e.g., "(SOMETHING)").
		function regex_callback_for_special_comments_v1($matches) {
			$uuid = hash('md5', $matches[0]);
			$html_1 = <<<HTML1
<span class='special_comment_outter' data-uuid='{$uuid}_1'><span class='special_comment_text_text' data-uuid='{$uuid}_2'>
HTML1;
			$html_2 = <<<HTML2
</span></span>
HTML2;		
			$return = $html_1 . '(' . ucwords(strtolower($matches[1])) . ')' . $html_2;
    		return $return;
		}
		foreach ($data as &$value){
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\' && substr($value, 0, 3) !== '\\\\e'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (strpos($value, '(') !== FALSE){ // This will run on lines with HTML elements, because otherwise some lines that have spans wouldn't get processed.
				if (preg_match('/\(([^a-z\n]*?)\)/', $value) === 1){
					$value = preg_replace_callback('/\(([^a-z\n]*?)\)/', 'regex_callback_for_special_comments_v1', $value);
				}
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Hyperlinks
		function regex_callback_for_hyperlinks_v1($matches) {
			$uuid = hash('md5', $matches[0]);
			$html_1 = <<<HTML1
<span class='hyperlink_outter' data-uuid='{$uuid}_1'><span class='hyperlink_inner' data-uuid='{$uuid}_2'><a class='hyperlink_content' data-uuid='{$uuid}_3'
HTML1;
			$html_2 = <<<HTML2
</a></span></span>
HTML2;		
			$return = $html_1 . "href='" . $matches[2] . "'>" . $matches[1] . $html_2;
    		return $return;
		}
		foreach ($data as &$value){
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\' && substr($value, 0, 3) !== '\\\\e'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (strpos($value, '{') !== FALSE){ // This will run on lines with HTML elements, because otherwise some lines that have spans wouldn't get processed.
				if (preg_match('/\{([^{}]+)\}\s*\(\s*((https?:|file:|ftp:)[^()\s]+)\s*\)/i', $value) === 1){
					$value = preg_replace_callback('/\{([^{}]+)\}\s*\(\s*((https?:|file:|ftp:)[^()\s]+)\s*\)/i', 'regex_callback_for_hyperlinks_v1', $value);

				}
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Styled Text
		function regex_callback_for_styledtext_v1($matches) {
			$uuid = hash('md5', $matches[0]);
			$html_1 = <<<HTML1
<span class='styledtext_outter' data-uuid='{$uuid}_1'><span class='styledtext_inner' data-uuid='{$uuid}_2'>
HTML1;
			$html_2 = <<<HTML2
</span></span>
HTML2;
			$return = $html_1 . "<span class='" . strtolower($matches[2]) . "'>" . $matches[1] . "</span>" . $html_2;
    		return $return;
		}
		foreach ($data as &$value){
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (strpos($value, '{') !== FALSE){ // This will run on lines with HTML elements, because otherwise some lines that have spans wouldn't get processed.
				if (preg_match('/\{([^\r\n{}]+?)\} \((Bold|Italic|Underline|Strikethrough)\)/i', $value) === 1){
					$value = preg_replace_callback('/\{([^\r\n{}]+?)\} \((Bold|Italic|Underline|Strikethrough)\)/i', 'regex_callback_for_styledtext_v1', $value);

				}
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Images
		function regex_callback_for_images_v1($matches) {
			$uuid = hash('md5', $matches[0]);
			$html_1 = <<<HTML1
<div class='image_outter' data-uuid='{$uuid}_1'><div class='image_inner' data-uuid='{$uuid}_2'><img class='image_content' data-uuid='{$uuid}_3' src='
HTML1;
			$html_2 = <<<HTML2
' alt="image"></div></div>
HTML2;
			$return = $html_1 . trim($matches[1]) . $html_2;
    		return $return;
		}
		foreach ($data as &$value){
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (strpos($value, '<') === FALSE){ // So it doesn't run in lines that have HTML elements
				if (preg_match('/\(([^()]+\.(png|jpg|jpeg|webp))\)/i', $value) === 1){
					$value = preg_replace_callback('/\(([^()]+\.(png|jpg|jpeg|webp))\)/i', 'regex_callback_for_images_v1', $value);
					// Copy relative images (e.g., 'file.jpg') to destination folder
					require_once $var_fb0366f4_source_path_str . '/Custom Dependencies/get_image_filename_i1_v0.1.php';
					$image_path = fun_a4e2c8b7_get_image_filename_i1_v0_1($value);
					if ($image_path !== FALSE){
						$image_path1 = realpath(dirname($source_file)) . '/' . $image_path;
						$image_path2 = $destination_folder . '/' . basename($image_path);
						copy($image_path1, $image_path2);
					}
				}
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		// Paragraphs. Anything that isn't blocked yet, should be treated as a paragraph in order to help preserve line endings.
		$number = 1;
		foreach ($data as &$value){
			$uuid = hash('md5', "{$number}{$value}");
			// Check for blockers
			if (substr($value, 0, 2) === '\\\\'){
				// Do nothing, because lines are blocked by \\
			}
			// When encountering, replace.
			else if (substr($value, 0, 2) !== '\\\\'){ // So it runs on all non-blocked lines. This could have merely been an else statement.
				$value = "<p id='p_{$number}_content' data-uuid='{$uuid}_1'>{$value}</p>";
				$number++;
			}
		}
		unset($value);
		$data = implode(PHP_EOL, $data);
		$data = explode(PHP_EOL, $data);
		### Remove blockers with exceptions "\\e001 ". Each exception code used must be stated.
		foreach ($data as &$value){
			// When encountering e001, remove.
			if (substr($value, 0, 7) === '\\\\e001 '){
				$value = substr($value, 7);
			}
		}
		unset($value);
		### Remove blockers "\\ "
		foreach ($data as &$value){
			// When encountering, remove.
			if (substr($value, 0, 3) === '\\\\ '){
				$value = substr($value, 3);
			}
		}
		unset($value);	
		### Break array into separate values for each page
		$data = implode(PHP_EOL, $data);
		$var_delimiter = "<!-- Page Break -->";
		$pagenated_data = preg_split(
    		'/(?=' . preg_quote($var_delimiter, '/') . ')/',
    		$data
		);
		$data = $pagenated_data;
		### Remove empty trailing page caused by extra page break
		foreach ($data as &$data_page){
			//var_dump($data_page);
			if (trim($data_page) === '<!-- Page Break -->'){
				$data_page = '';
			}
		}
		unset($data_page);
		$data = array_filter($data);
		### Add Concatenation buffers to each page. This way if text combines, the buffer print characters will be inbetween. This is not needed for HTML documents, and can be hidden with CSS. It is useful if printing HTML to PDF that might have text copied/pasted from it.
		foreach ($data as &$data_page){
			$data_page = "<div class='concatenation_buffer_top'> ; </div>" . PHP_EOL . $data_page . PHP_EOL . "<div class='concatenation_buffer_bottom'> ; </div>";
		}
		unset($data_page);
		### Add Page HTML, CSS, & Javascript to each page
		foreach ($data as &$data_page){
			// Top HTML + CSS (this helps create a valid HTML page)
			if (is_file($css_file) === TRUE){
				$css = file_get_contents($css_file);
			} else {
				$css = '';
			}
			$title = hash('sha3-256', implode($data));
			$top_html = <<<HEREDOC
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<style>
{$css}
</style>
</head>
<body>
HEREDOC;
		// Javascript
		$javascript_content = <<<HEREDOC
<script>
console.log('Javascript is running!');

function fun_b7e14f92_set_body_background_from_class_i1_v0_2(
    var_b7e14f92_target_class_str = "page_outter"
) {

    var var_b7e14f92_target_element_obj =
        document.querySelector("." + var_b7e14f92_target_class_str);

    if (var_b7e14f92_target_element_obj === null) {
        return false;
    }

    var var_b7e14f92_computed_style_obj =
        window.getComputedStyle(
            var_b7e14f92_target_element_obj
        );

    var var_b7e14f92_background_colour_str =
        var_b7e14f92_computed_style_obj.backgroundColor;

    if (
        var_b7e14f92_background_colour_str === "" ||
        var_b7e14f92_background_colour_str === "transparent" ||
        var_b7e14f92_background_colour_str === "rgba(0, 0, 0, 0)"
    ) {
        return false;
    }

    document.body.style.backgroundColor =
        var_b7e14f92_background_colour_str;

    return true;

}

// Makes body of page match colour of page div, so if there's extra space after the content, it is not white when the page is a different colour
fun_b7e14f92_set_body_background_from_class_i1_v0_2('page_outter');

</script>
HEREDOC;	
		$data_page = $data_page . PHP_EOL . $javascript_content;
		// Bottom HTML (this helps create a valid HTML page)
		$bottom_html = <<<HEREDOC
</body>
</html>
HEREDOC;
		$data_page = $top_html . PHP_EOL . $data_page . PHP_EOL . $bottom_html;
		}
		unset($data_page);
		### Write data to file(s)
		$n2 = 0;
		$switch2 = TRUE;
		$file_prefix = hash('sha3-256', implode($data));
		foreach ($data as &$page){
			$n2++;
			if (file_exists("{$destination_folder}/{$file_prefix}-{$n2}.html") === TRUE){
				if ($overwrite !== TRUE){
					$switch2 = FALSE;
				}
			}
			if ($switch2 === TRUE){
				file_put_contents("{$destination_folder}/{$file_prefix}-{$n2}.html", $page);
			}
		}
		unset($page);
	}
	result:
	## Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_markup_file_to_html_files_i1781384111_v3_format_error') === FALSE){
				function ritchey_markup_file_to_html_files_i1781384111_v3_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_markup_file_to_html_files_i1781384111_v3_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	## Return
	if (@empty($errors) === TRUE){
		return TRUE;
	} else {
		return FALSE;
	}
}
}
?>