<?php

echo '<pre>';



$str = 'a{b{c}}';


print_r(split_bracket($str));exit;




$str = file_get_contents('origin_str.txt');

$arr = explode(";", $str);

//先把;号拆分成一行行
$new = array();
$pre_line = '';  //前一行
$len = count($arr);
for($i=0; $i<$len; $i++){

	$pre_line .= trim($arr[$i]); //注意！这句要放在最前面赋值
	
	if(!($pre_line == '}')){
		$pre_line .= ';';
	}
	
	$dy = substr_count($pre_line, '\''); //单引号个数
	$sy = substr_count($pre_line, '"');	 //双引号个数
	//如果是偶数说明闭合，把前一行拼接好的添加到数组并清空$pre_line变量
	if(is_even($dy + $sy)){
		array_push($new, $pre_line);
		$pre_line = '';
	}
	//echo $sy.'<br/>';
	
}
$arr = $new;
//*****************************************************

$str = implode("\n", $arr);
//echo $str;exit;

//然后把括号右边的另起一行，比如if(1){ $a=5;   思路是分别查找每一行左括号和右括号的位置，如果右括号的位置大于左括号，说明是闭合的，否则另起下一行
$new = array();
$len = count($arr);
for($i=0; $i<$len; $i++){
	
	//如果含有左边的{号就拆分
	if(substr_count($arr[$i], '{') > 0){
		
		$sub_arr = explode("{", $arr[$i]);
	//print_r($sub_arr);exit;
		$sub_arr[0] .= '{';
		array_push($new, $sub_arr[0]);
		
		
	} else {
		array_push($new, $arr[$i]);
	}
}

print_r($new);





function is_even($n){
	return ($n%2) == 0;
}


function split_bracket($str){
	static $level = 0;
	static $arr = array();
	//static $left = array();
	//static $right = array();
	
	$left_position = strpos($str, '{');  
	$right_position = strpos($str, '}');

	if($left_position == 0 && $right_position == 0){
		return $arr;
	}
	
	//左括号、右括号
	if($left_position < $right_position){
				
	//echo '{'.$level."\n";	exit;
		$temp_arr = explode("{", $str, 2);
		
		$level++;
		$tab = str_repeat("\t",$level);
		array_push($arr, $tab.$temp_arr[0].'{');
print_r($arr);
echo $temp_arr[1]."\n";
		split_bracket($temp_arr[1]);
		
	} else {

		$temp_arr = explode("}", $str, 2);
		
		$tab = str_repeat("\t",$level);
		array_push($arr, $tab.$temp_arr[0]);
		
		$level--;
	//echo '}'.$level."\n";		
		$tab = str_repeat("\t",$level);
		array_push($arr, $tab."}");

		split_bracket($temp_arr[1]);
		
		
	}
	
}








//echo preg_replace('/\'\'/',";\n",$origin_str);