<?php
	function printArray($str)
	{
		print "<pre>";
			print_r($str);
		print "</pre>";
	}
	function redirect($url)
	{
		header("Location:".$url);
		exit;
	} 	

    function getRandomNumbers($length=16)
    {
        srand(date("s")); 
        $possible_charactors = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
        $string = ""; 
        while (strlen($string)<$length) { 
            $string .=substr($possible_charactors, rand()%((strlen($possible_charactors))),1); 
        } 
        return(uniqid($string)); 
    } 	
	function get_extension($filename)
	{
        $str=explode('/',$filename);
        $len=count($str);
        $str2=explode('.',$str[($len-1)]);
        $len2=count($str2);
        $ext=$str2[($len2-1)];
        return $ext;
    }	
?>