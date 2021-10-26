<?php
	function test_input($data) {
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		return $data;
	}
	
	function min_to_hour_min($value){
		$hours_minutes = null;
		if($value == 1){
			$hours_minutes = $value ." minut";
		}	elseif($value <60){
				$hours_minutes = $value ." minutit";
		} else {
				$hours = floor($value / 60);
				$minutes = $value % 60;
				if($hours == 1){
					$hours_minutes = $hours ." tund";
				} else {
					$hours_minutes = $hours ." tundi";
				}
				if($minutes > 0){
					$hours_minutes .= " ja " .$minutes;
					if($minutes == 1){
						$hours_minutes .=" minut";
					} else {
						$hours_minutes .=" minutit";
					}	
				}	
		}
		return $hours_minutes;
	}

	function date_format_est($value){
		$temp_date = new DateTime($value);
		return $temp_date->format("d.m.Y");
	}	