<?php

/*

主要关心四点

1、;分号 
2、{左括号 
3、{右括号 
4、是引号内的字符不进行处理

为了处理引号内的字符，需要反向引用，以识别开头的那个引号


*/



echo '<pre>';

$str = file_get_contents('origin_str.txt');
$char = str_split($str);

$indent = 0;

$len = count($char);
$pre_locat = 0;
$quotes_num = 0;
$quotes_type = '';
for($i=0; $i<$len; $i++){

	if(($char[$i] == '\'' || $char[$i] == '"') && $char[$i-1] != '\\'){ //引号（单引或者双引），且不是转义的引号
	
		//是左边的引号，计数器加一
		if($quotes_num == 0){
			$quotes_type = $char[$i];
			$quotes_num++;	
			
		}
		//是右边引号闭合，计数器减一
		else if($char[$i] == $quotes_type && $quotes_num == 1){
			$quotes_type = '';
			$quotes_num--;
		}			
		
		//echo $quotes_type;exit;
	} 
	
	//如果当前在引号内（单引或者双引），则不检查里面的内容，跳过
	if($quotes_num == 1){
		continue;
	}
	
	if($char[$i] == ';'){
		
		$i++;
		$new[] = cut_line($str, $pre_locat, $i - $pre_locat);
		$pre_locat = $i;
			
	}  elseif ($char[$i] == '{'){

		$i++;
		$new[] = cut_line($str, $pre_locat, $i - $pre_locat);
		$pre_locat = $i;
		$indent++;
		
		
	} elseif ($char[$i] == '}'){
		
		$i++;
		$indent--;
		$new[] = cut_line($str, $pre_locat, $i - $pre_locat);
		$pre_locat = $i;		
	} 	
}

print_r($new);exit;

function cut_line($str, $start, $length){
	global $indent;
	
	$s2 = substr($str, $start, $length);
	$s1 = str_repeat("\t",$indent);
	return $s1.$s2;
}












