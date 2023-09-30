<?php
/**
 * Project:    ChefBy : Common Class
 * File:       class.common.php
 * @copyright 2017 
 * @package ChefBy
 * @version 1.0.0
 */
require_once(MAIN_COMMON_PATH."class.ExtentedDB.php");
class CommonClass extends extendsClassDB
{	
	var $Request;
	var $SQLArray;

	function __construct()
	{
		$this->Request 	= array();
		$this->SQLArray	= array();
		$this->extendsClassDB1();		
	}
	function getSelectQuery($strSQL, $uSelect=0, $queryType=false)
	{
		$this->dbSetQuery($strSQL,"select",$uSelect);
		return $this->MakeStripSlashes($this->dbSelectQuery());
	}
	function ExecuteQry($strSQL, $strSQLType = "update")
	{
		global $objSmarty;
 		$this->dbSetQuery($strSQL, $strSQLType);
		$this->dbExecuteQuery();
	}
	function AddInfoToDB($objArray, $Prefix, $TableName)
	{
		$counter = 0;
		foreach ($objArray as $key=>$value) {
			$pos = strpos($key, $Prefix);
			if (!is_integer($pos)) {
			} else {
				$key = str_replace($Prefix,"",$key);
				$insertArray[$counter]["Field"] = $key;
				$insertArray[$counter]["Value"] = stripslashes($value);
				$counter++;
			}
		}
		$insert_id = $this->doInsert($TableName,$insertArray);
		return $insert_id;
	}
	function doInsert($strTableName, $objFieldsArray)
	{
		global $objSmarty;
		if (is_array($objFieldsArray)) {
			$strInsertFields = "";
			$strInsertValues = "";
			for ($i=0; $i<count($objFieldsArray); $i++) {
				$strInsertFields.= $objFieldsArray[$i]["Field"];
				$strInsertValues.= "'".str_replace("?","",mb_convert_encoding(addslashes($objFieldsArray[$i]["Value"]), "ASCII"))."'";
				if ($i<count($objFieldsArray)-1) {
					if ($objFieldsArray[$i]["Field"]!="") {
						$strInsertFields.=", ";
						$strInsertValues.=", ";
					}
				}
			}
			$strInsertQry = "INSERT INTO $strTableName($strInsertFields) VALUES($strInsertValues)";
 			$this->ExecuteQry($strInsertQry);
			$InsertId = mysqli_insert_id($this->dbLink);
			return $InsertId;
		} else {
			$objSmarty->assign("strErrorMsg","Error while adding new Data, Fields array is empty");
			return false;
		}
	}
	function UpdateInfoToDB($objArray, $Prefix, $TableName, $Where)
	{

		$counter = 0;
		foreach ($objArray as $key => $value) {
			$pos = strpos($key, $Prefix);
			if (!is_integer($pos)) {
			} else {
				$key = str_replace($Prefix,"",$key);
				$UpdateArray[$counter]["Field"] = $key;
				$UpdateArray[$counter]["Value"] = stripslashes($value);
				$counter++;
			}
		}
		$res =$this->doUpdate($TableName,$UpdateArray,$Where);

		return $res;
	}
	function doUpdate($strTableName, $objFieldsArray, $WhereClause)
	{
		// printArray($objFieldsArray); exit;
 		if (is_array($objFieldsArray)) {
			$strUpdateFields = "";
			for ($i=0; $i<count($objFieldsArray); $i++) {
				$strUpdateFields.= $objFieldsArray[$i]["Field"]."="."'".str_replace("?","",mb_convert_encoding(addslashes($objFieldsArray[$i]["Value"]), "ASCII"))."'";
				if ($i<count($objFieldsArray)-1) {
					if ($objFieldsArray[$i]["Field"]!="") {
						$strUpdateFields.=", ";
					}
				}
			}
			$strUpdateQry = "UPDATE $strTableName SET $strUpdateFields $WhereClause";
			
  			$this->ExecuteQry($strUpdateQry);
			return true;
		} else {
			return false;
		}
	}	
	function getBrowserType() 
	{
		$ua = $_SERVER[HTTP_USER_AGENT]; 
		if (strpos($ua,'MSIE')>0) {
		  $B_Name="MSIE";
		  $B_Name1=1;
		} else if (strpos($ua,'Netscape')>0) {
		  $B_Name="Netscape";
		  $B_Name1=2;
		} else if (strpos($ua,'Safari')>0) {
		  $B_Name="Safari";
		  $B_Name1=2;
		} else {
		  $B_Name="Firefox";
		  $B_Name1=2;
		}
		return $B_Name;
	}	
	/**
		 * Apply stripslashes function for array of values 
		 * @param 	ToStripslash (array)			
		 * @return  Stripped array
	*/
	function MakeStripSlashes($array, $replaceValue='', $replaceValueTo='') 
	{
		if ($array) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$value=$this->MakeStripSlashes($value);
					if($replaceValue==''&&$replaceValueTo=='')
						$array_temp[$key]=str_replace("#AMP#","",$value);
					else
						$array_temp[$key]=str_replace($replaceValue,$replaceValueTo,$value);                      
				} else {
					$array_temp[$key]=stripslashes($value);
				}
			}    
			return $array_temp;   
		}   
		
	}		
	function createthumb($input_file_name, $output_filename, $new_w, $new_h='')
	{
		if (preg_match("/(jpg|jpeg)$/i",$input_file_name)) {
			$src_img = imagecreatefromjpeg($input_file_name);
		} else if (preg_match("/png$/i",$input_file_name)) {
			$src_img = imagecreatefrompng($input_file_name);
		} else if (preg_match("/bmp$/i",$input_file_name)) {
			 $src_img = imagecreatefromjpeg($input_file_name);
		} else if (preg_match("/gif$/i",$input_file_name)) {
			$src_img = imagecreatefromgif($input_file_name);
		} else {
			throw(new Exception("ERROR: Cant work with file $input_file_name becuase its an unsupported file type for this function."));
		}
	
		if ($src_img == false ) {
			throw(new Exception("ERROR: Unabel to open image file $input_file_name"));
		}
	
		$old_x = imageSX($src_img);
		$old_y = imageSY($src_img);
	
		if ($new_h == 0 ) {
			$thumb_w = $new_w;
					$thumb_h = $old_y * ($new_w / $old_x);
	
		} else if ($new_w == 0 ) {
			$thumb_h = $new_h;
			$thumb_w = $old_x * ($new_h / $old_y);
		} else {
			if ($old_x > $old_y) {
				$thumb_w = $new_w;
				$thumb_h = $old_y * ($new_h/$old_x);
			} else if ($old_x < $old_y) {
				$thumb_w = $old_x * ($new_w/$old_y);
				$thumb_h = $new_h;
			} else if ($old_x == $old_y) {
				$thumb_w = $new_w;
				$thumb_h = $new_h;
			}
		}
	
		$dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	
		if (preg_match("/png$/i",$input_file_name)){
			imagepng($dst_img,$output_filename); 
		} else {
			imagejpeg($dst_img,$output_filename); 
		}
	    return $output_filename;
		imagedestroy($dst_img); 
		imagedestroy($src_img); 
	}	
	function delTree($dir)
	{
		$files = glob( $dir . '*', GLOB_MARK );
		foreach ( $files as $file ) {
			if( substr( $file, -1 ) == '/' )
				delTree( $file );
			else
				unlink( $file );
		}
		if (is_dir($dir)) rmdir( $dir );
	} 	
	function slugName($string)
	{
		$string = preg_replace("`\[.*\]`U","",$string);
		$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
		$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
		return strtolower(trim($string, '-'));
	}		
	function msort($array, $id="id", $sort_ascending=true) 
	{
        $temp_array = array();
        while (count($array)>0) {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) {
                if (isset($item[$id])) {
					if ($array[$lowest_id][$id]) {
						if (strtolower($item[$id]) < strtolower($array[$lowest_id][$id])) {
							$lowest_id = $index;
						}
                    }
				}
				$index++;
			}
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
		if ($sort_ascending) {
			return $temp_array;
		} else {
			return array_reverse($temp_array);
		}
	}		
	function deGetMinutes()
	{ 
		$start=strtotime('00:00');
		$end=strtotime('24:00');

		for ($i=$start; $i<=$end; $i = $i + 15*60) {
			$strTime = date('g:i A',$i).'<br>';
		}
 	}
 	/** Common Queries :: START **/
	function doGetTableListing($table_name, $where=NULL, $select='*', $group_by=NULL, $order_by=NULL)
	{
		$sql 		=	"SELECT $select FROM ".$table_name." $where $group_by $order_by";
		//echo $sql."<BR><BR>";
		$strResults =	$this->getSelectQuery($sql);
		return $strResults;
	}		
	function doGetTableSingleRecord($table_name, $where=NULL, $select='*', $isPrint=NULL)
	{
		$sql 		=	"SELECT $select FROM ".$table_name." $where ";
		$strResults =	$this->getSelectQuery($sql);
		return $strResults[0] ?? [];
	}	
	function doGetTableSingleColumn($table_name, $where=NULL, $select=NULL)
	{
		$sql 		=	"SELECT $select FROM ".$table_name." $where ";
		$strResults =	$this->getSelectQuery($sql);
		return $strResults[0][$select];
	}
	function doTotalRecordCnt($table_name, $where=NULL, $select='*')
	{
		$sql 		=	"SELECT count($select) as Cnt FROM ".$table_name." ".$where.""; 
		$result 	=	$this->getSelectQuery($sql);
		return $result[0]['Cnt'];
	} 
	function doDeleteById($table_name,$field_name, $field_value)
	{
		$sql		=	"DELETE FROM ".$table_name." WHERE ".$field_name." = '".$field_value."'"; 
		//printArray($sql); exit;
		$this->ExecuteQry($sql);
		// echo $sql;exit;
	}
	function doDeleteRecords($table_name, $where=NULL)
	{
		$sql		=	"DELETE FROM ".$table_name." $where ";
		$this->ExecuteQry($sql);
	}
	function doDeleteBydate($table_name,$field_name, $field_value)
	{
		$current_date 	=	doConvertLocalToSingapore();
		$sql			=	"UPDATE ".$table_name." SET  is_deleted ='yes', deleted_date= '".$current_date."' WHERE ".$field_name." = '".$field_value."'";
		$this->ExecuteQry($sql);
	} 	
	/** Common Functions :: END **/	
}
?>