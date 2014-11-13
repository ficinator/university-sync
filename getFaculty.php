<?php
	require 'connect.php';
    include 'stdlib.php';
    $strRet = "";
    $sql = "SELECT * FROM `university` WHERE `name` = '{$_GET['universityName']}'";
    $uni = mysql_fetch_array(mysql_query($sql));
    $sqlFac = "SELECT * FROM `faculty` WHERE `id_university` = '{$uni['id']}'";
    $result = mysql_query($sqlFac);
    $rows = mysql_num_rows($result);
    if ($rows != 0) {
    	$strRet .= "<select name=\"faculty\" id=\"facultySelect\">";
        $strRet .= '<option selected="" disabled="">Vyber fakultu</option>';
	    while ($faculty = mysql_fetch_array($result)) {
	    	$strRet .= "<option value=\"{$faculty['id']}\">{$faculty['name']}</option>";
	    }
	    $strRet .= "</select>";
		echo $strRet;
	}
?>