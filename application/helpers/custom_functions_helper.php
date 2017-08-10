<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('is_login')) {
    function is_login($role = NULL) {
		$CI =& get_instance();
		// We need to use $CI->session instead of $this->session
		$valid = $CI->session->userdata('validate_user');	
		if(!$valid) {
			redirect('/login');
		}
		else {
			if($role != NULL) {
				$exp_role = explode(',', $role);
				$login_user_role = $CI->session->userdata('role_id');
				if( !in_array($login_user_role, $exp_role) ) {
					show_404();
					exit();
				}
			}
		}
	}   
}

if ( ! function_exists('checkPermission')) {
	function checkPermission() {
		$CI =& get_instance();
		$valid = $CI->session->userdata('validate_user');	
		if(!$valid) {
			redirect('/login');
		}
		else {
			$login_user = $CI->session->userdata('login_user');
			$return = 1;
			if($CI->session->userdata('role_id') != 1) { // For Admin
				$permissions = $CI->session->userdata('permissions'); //print_out($permissions);exit();
				$current_controller = $CI->router->class;
				$current_method = $CI->router->method;
				//echo $current_controller . ' - ' . $current_method; exit();
				if(substr($current_method, 0, 3) != 'aj_') {
					//Check Permisssion
					if(isset($permissions[$current_controller])) {
						$exp_perm_methods = explode(', ', $permissions[$current_controller]['permission']);
						if( !in_array($current_method, $exp_perm_methods) )  {
							$return = 0;	
						}
					}
					else {
						$return = 0;
					}
				}
			}

			if(!$return) {
				$isAjax = $CI->input->post('ajax');
				if($isAjax) {
					$ret = array(
						'status' => 'fail',
						'msg'	 => "Sorry, you have no permission for this action."
					);
					echo json_encode($ret);
				}
				else {
					redirect('/pv_redirect');
				}
				exit();	
			}

		}
	}
}

if ( ! function_exists('send_email')) {
	function send_email($fromname, $from, $to, $subject, $message , $cc = NULL, $bcc = NULL) {
      	$CI =& get_instance();
      	$CI->load->library('email');
      	$from_email = $CI->session->userdata('email'); //Use Login user email
      	$config['mailtype'] = 'html';
      	$CI->email->initialize($config);
      	$CI->email->from($from_email, $fromname);
		$CI->email->to($to); 

		if($cc != NULL) {
			$CI->email->cc($cc);
		}

		if($bcc != NULL) {
			$CI->email->bcc($bcc); 
		}
		$CI->email->subject($subject);
		$CI->email->message($message);	
		$CI->email->send(); 
    }     
}

/*
 *
 * Just print out for array and object
 */
if ( ! function_exists('print_out')) {
    function print_out($arr) {
       print "<pre>";
       print_r($arr);
       print "</pre>";
    }   
}
if ( ! function_exists('get_timestamp')) {
	function get_timestamp($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]); 
		//return mktime(0,0,0,$dateparts[0],$dateparts[1],$dateparts[2]); 
	}
}

if ( ! function_exists('get_earliesttimestamp')) {
	function get_earliesttimestamp($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
		// return mktime(0,0,0,$dateparts[0],$dateparts[1],$dateparts[2]);
	}
}

if ( ! function_exists('get_latesttimestamp')) {
	function get_latesttimestamp($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(23,59,59,$dateparts[1],$dateparts[0],$dateparts[2]);
		// return mktime(23,59,59,$dateparts[0],$dateparts[1],$dateparts[2]);
	}
}

if ( ! function_exists('get_timestamp_month')) {
	function get_timestamp_month($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(23,59,59,$dateparts[1],$dateparts[0],$dateparts[2]);
		// return mktime(0,0,0,$dateparts[1], '1',$dateparts[0]);
	}
}

if ( ! function_exists('get_earliesttimestamp_month')) {
	function get_earliesttimestamp_month($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
		// return mktime(0,0,0,$dateparts[1], '1',$dateparts[0]);
	}
}

if ( ! function_exists('get_latesttimestamp_month')) {
	function get_latesttimestamp_month($date, $symbol) {
		$dateparts = explode($symbol, $date);
		return mktime(23,59,59,$dateparts[1],$dateparts[0],$dateparts[2]);
		// return mktime(23,59,59,$dateparts[1], '1',$dateparts[0]);
	}
}

if ( ! function_exists('get_earliest_currentmonth_timestamp')) {
	function get_earliest_currentmonth_timestamp() {
		$dateparts = explode('-', date('m-01-Y',strtotime('this month')));
		return mktime(0,0,0,$dateparts[0],$dateparts[1],$dateparts[2]); 
	}
}

if ( ! function_exists('get_latest_currentmonth_timestamp')) {
	function get_latest_currentmonth_timestamp() {
		$dateparts = explode('-', date('m-t-Y',strtotime('this month')));
		return mktime(23,59,59,$dateparts[0],$dateparts[1],$dateparts[2]); 
	}
}

/**
 *
 *	get date from timestamp
 */
if ( ! function_exists('get_date')) {
	function get_date($timestamp) {
		return date("d/m/Y", $timestamp);
	}
}

/**
 *
 *	get time from timestamp
 */
if ( ! function_exists('get_time')) {
	function get_time($timestamp) {
		return date("H:i", $timestamp);
	}
}

/**
 *
 *	get timestamp by given date and time
 */
if ( ! function_exists('get_datetimestamp')) {
	function get_datetimestamp($date, $time, $datesymbol, $timesymbol) {
		$dateparts = explode($datesymbol, $date);
		$timeparts = explode($timesymbol, $time);
		return mktime($timeparts[0],$timeparts[1],0,$dateparts[1],$dateparts[0],$dateparts[2]);
	}
}

/**
 *
 *  Prepare config for bootstrap style pagination  
 */
if(! function_exists('create_pagination_config')) {
	function create_pagination_config($base_url, $total_rows, $per_page, $uri_segment) {
		$config = array(
			'base_url' 	  	 => $base_url,
			'total_rows'  	 => $total_rows,
			'per_page'	  	 => $per_page,
			'uri_segment'	 => $uri_segment,
			'full_tag_open'	 => '<ul class="pagination pagination-sm">', 
			'full_tag_close' => '</ul>', 
			'num_tag_open' 	 => '<li>',
			'num_tag_close'  => '</li>', 
			'cur_tag_open' 	 => '<li class="active"><span>',
			'cur_tag_close'  => '<span class="sr-only">(current)</span></span></li>', 
			'prev_tag_open'  => '<li>', 
			'prev_tag_close' => '</li>', 
			'next_tag_open'  => '<li>', 
			'next_tag_close' => '</li>',
			'first_link' 	 => '&laquo;first', 
			'prev_link' 	 => '&lsaquo;', 
			'last_link' 	 => 'last&raquo;', 
			'next_link' 	 => '&rsaquo;', 
			'first_tag_open' => '<li>',
			'first_tag_close'=> '</li>', 
			'last_tag_open'  => '<li>',
			'last_tag_close' => '</li>', 
			'additional_param' => '?pcp=88'
		);
		return $config;
	}
}

if ( ! function_exists('logActivity')) {
	function logActivity($action, $description, $record_id) {
		$CI =& get_instance();
		$desc = $CI->session->userdata('name') . ' [' . $CI->session->userdata('login_user') . '] - ' . $description;
		$CI->activity_model->log($action, $desc, $record_id);
	}
}


if ( ! function_exists('getBase64')) {
	function getBase64($base30imgstring) {
		$CI =& get_instance();
		$CI->load->library('jSignature_Tools_Base30');

		$converter = new jSignature_Tools_Base30();
		$raw = $converter->Base64ToNative($base30imgstring);
		
		$im = imagecreatetruecolor(800, 200);

	    // Save transparency for PNG
	    imagesavealpha($im, true);

	    // Fill background with transparency
	    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
	    imagefill($im, 0, 0, $trans_colour);

	    // Set pen thickness
	    imagesetthickness($im, 2);

	    // Set pen color to blue
	    $black = imagecolorallocate($im, 0, 0, 0);

	    // Loop through array pairs from each signature word
	    for ($i = 0; $i < count($raw); $i++) {
	        // Loop through each pair in a word
	        for ($j = 0; $j < count($raw[$i]['x']); $j++) {
	            // Make sure we are not on the last coordinate in the array
	            if ( ! isset($raw[$i]['x'][$j]) or ! isset($raw[$i]['x'][$j+1])) break;
	            // Draw the line for the coordinate pair
	            imageline($im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $raw[$i]['x'][$j+1], $raw[$i]['y'][$j+1], $black);
	        }
	    }

	    ob_start();
       		imagepng($im);
	      	$out = ob_get_contents();
	    ob_end_clean();

	    return base64_encode($out);
	}
}


