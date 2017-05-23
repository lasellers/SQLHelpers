<?php

class validate {
	
	public function boolean(string $string) {
		// 		return is_bool(filter_var ( $string, FILTER_VALIDATE_BOOLEAN));
		return preg_match("/^(true|1)$/", filter_var ( $string, FILTER_SANITIZE_STRING));
	}
	
	public function integer(string $string) {
		return filter_var ( $string, FILTER_VALIDATE_INT);
	}
	
	public function number(string $string) {
		return filter_var ( $string, FILTER_VALIDATE_FLOAT);
	}
	
	public function email(string $string) {
		return preg_match("/^[a-zA-Z0-9_.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $string);
	}
	
	public function phone(string $string) {
		return preg_match("/^\d{3}[^\d]{0,2}\d{3}[^\d]{0,2}\d{4}$/", $string);
	}
	
	public function url(string $string) {
		return filter_var ( $string, FILTER_VALIDATE_URL);
	}
	
	public function string(string $string) {
		return preg_match("/(.*)(\r|\n)(.*)/", $string)==0;
	}
	
	public function text(string $string) {
		return is_string($string);
	}
	
	public function date(string $string, $format="d-m-Y")
	    {
		// 		$date = date_parse($string);
		$date = date_parse_from_format($format, $string);
		return checkdate($date['month'], $date['day'], $date['year']);
	}
	
	public function postInteger(array $array) {
		$newArray = array_map(function ($item) {
			return $this->integer($item);
		}
		,$array);
		return $newArray;
	}
	
	public function byType($type,$value) {
		switch($type) {
			case 'boolean':
			            return $this->boolean($value);
			
			case 'integer':
			            return $this->integer($value);
			
			case 'number':
			            return $this->number($value);
			
			case 'email':
			            return $this->email($value);
			
			case 'phone':
			            return $this->phone($value);
			
			case 'url':
			            return $this->url($value);
			
			case 'date':
			            return $this->date($value);
			
			case 'string':
			            return $this->string($value);
			
			case 'text':
			            return $this->text($value);
			
			case 'postInteger':
			            return $this->postInteger($value);
		}
	}
	
}
