<?php

if( ! function_exists('counter'))
{
	function counter() {
		$namaFile = "counter.dat";
		if(!file_exists($namaFile)) {
			file_put_contents($namaFile, "0");
			return 0;
		} else {
			$count = file_get_contents($namaFile);
			if(is_numeric($count)) {
				$count++;
			} else {
				$count = 0;
			}
			file_put_contents($namaFile, $count);
			return $count;
		}
	}
	
	function get_counter()
	{
		$namaFile = "counter.dat";
		if(!file_exists($namaFile))
		{
			return 0;
		}
		else
		{
			return file_get_contents($namaFile);
		}
	}
}