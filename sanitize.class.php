<?php

class sanitize {
	
	public function boolean(string $string): bool  {
		return (bool)preg_match("/^(true|1)$/", filter_var ( $string, FILTER_SANITIZE_STRING))>=1;
	}
	
	public function integer(string $string) {
		return filter_var ( $string, FILTER_SANITIZE_NUMBER_INT);
	}
	
	public function number(string $string)  {
		return filter_var ( $string, FILTER_SANITIZE_NUMBER_FLOAT );
	}
	
	public function email(string $string): string {
		return preg_replace("/[^a-zA-Z0-9_.@]+/", '', filter_var ( $string, FILTER_SANITIZE_STRING));
	}
	
	public function phone(string $string): string {
		return preg_replace("/[^0-9]+/", '', filter_var ( $string, FILTER_SANITIZE_STRING));
	}
	
	public function url(string $string): string {
		return filter_var ( filter_var ( $string, FILTER_SANITIZE_STRING), FILTER_SANITIZE_URL);
	}
	
	public function string(string $string): string {
		return filter_var ( $string, FILTER_SANITIZE_STRING);
	}
	
	public function text(string $string): string {
		return filter_var ( $string, FILTER_SANITIZE_STRING);
	}
	
	public function date(string $string, $format="d-m-Y")
	    {
		// 		$date = date_parse($string);
		$date = date_parse_from_format($format, $string);
		if(!checkdate($date['month'], $date['day'], $date['year']))
		        return "";
		
		$time = strtotime($string);
		return $time==0?"":date($format, $time);
	}
	
	public function postInteger(array $array): array {
		$matches = array_map(function ($item) {
			return $this->integer($item);
		}
		,$array);
		return $matches;
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
	
	// 	Note: FILTER_SANITIZE_STRING basically just does a string_tags,
	    // 	but we prefer using it as it is more clear what the intent is,
	    // 	thus, use it here.
	    public function forDisplay(string $string): string {
		return $this->text($string);
	}
	
	public function forDisplayArray(array $array): array {
		$newArray = array_map(function ($item) {
			return $this->text($item);
		}
		,$array);
		return $newArray;
	}
	
}
