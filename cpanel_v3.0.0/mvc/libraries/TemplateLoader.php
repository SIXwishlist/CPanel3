<?php
/*
 *
 */

/**
 * Description of TemplateLoader
 *
 * @author Ahmad
 */
class TemplateLoader {

    public function __construct() {
    }
    
    public static function getFile($file){

        try {

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
    }

    public static function replaceObject($object, $templateFile, $pattern){
        $string = "";
        return $string;
    }

    public static function replaceArray($array, $templateFile, $pattern){
        $string = '<html>
<head>
<title>furniture</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (furniture.psd) -->
<table id="Table_01" width="771" height="651"   cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="32">
			<img src="l-imgs/furniture_01.gif" width="770" height="15" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="15" alt=""></td>
	</tr>
	<tr>
		<td rowspan="26">
			<img src="l-imgs/furniture_02.gif" width="15" height="635" alt=""></td>
		<td colspan="30">
			<img src="l-imgs/furniture_03.gif" width="743" height="12" alt=""></td>
		<td rowspan="26">
			<img src="l-imgs/furniture_04.gif" width="12" height="635" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="6">
			<img src="l-imgs/furniture_05.gif" width="37" height="110" alt=""></td>
		<td colspan="6" rowspan="5">
			<img src="l-imgs/furniture_06.gif" width="195" height="45" alt=""></td>
		<td colspan="21">
			<img src="l-imgs/furniture_07.gif" width="511" height="7" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="7" alt=""></td>
	</tr>
	<tr>
		<td colspan="17">
			<img src="l-imgs/furniture_08.gif" width="460" height="7" alt=""></td>
		<td rowspan="3">
			<img src="l-imgs/furniture_09.gif" width="30" height="26" alt=""></td>
		<td colspan="3" rowspan="5">
			<img src="l-imgs/furniture_10.gif" width="21" height="103" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="7" alt=""></td>
	</tr>
	<tr>
		<td colspan="13" rowspan="3">
			<img src="l-imgs/furniture_11.gif" width="284" height="31" alt=""></td>
		<td colspan="3">
			<img src="l-imgs/furniture_12.gif" width="73" height="13" alt=""></td>
		<td rowspan="4">
			<img src="l-imgs/furniture_13.gif" width="103" height="96" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2">
			<img src="l-imgs/furniture_14.gif" width="73" height="18" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="l-imgs/furniture_15.gif" width="30" height="77" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="12" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="l-imgs/furniture_16.gif" width="48" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_17.gif" width="22" height="65" alt=""></td>
		<td colspan="3">
			<img src="l-imgs/furniture_18.gif" width="115" height="65" alt=""></td>
		<td colspan="3">
			<img src="l-imgs/furniture_19.gif" width="23" height="65" alt=""></td>
		<td colspan="5">
			<img src="l-imgs/furniture_20.gif" width="95" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_21.gif" width="24" height="65" alt=""></td>
		<td colspan="2">
			<img src="l-imgs/furniture_22.gif" width="54" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_23.gif" width="23" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_24.gif" width="71" height="65" alt=""></td>
		<td colspan="2">
			<img src="l-imgs/furniture_25.gif" width="17" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_26.gif" width="40" height="65" alt=""></td>
		<td>
			<img src="l-imgs/furniture_27.gif" width="20" height="65" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="65" alt=""></td>
	</tr>
	<tr>
		<td colspan="30">
			<img src="l-imgs/furniture_28.gif" width="743" height="13" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
	<tr>
		<td colspan="30">
			<img src="l-imgs/furniture_29.gif" width="743" height="3" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="13">
			<img src="l-imgs/furniture_30.gif" width="28" height="386" alt=""></td>
		<td colspan="7" rowspan="4">
			<img src="l-imgs/furniture_31.gif" width="204" height="235" alt=""></td>
		<td colspan="21">
			<img src="l-imgs/furniture_32.gif" width="511" height="14" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="14" alt=""></td>
	</tr>
	<tr>
		<td rowspan="12">
			<img src="l-imgs/furniture_33.gif" width="7" height="372" alt=""></td>
		<td colspan="2" rowspan="9">
			<img src="l-imgs/furniture_34.gif" width="8" height="358" alt=""></td>
		<td colspan="18">
			<img src="l-imgs/furniture_35.gif" width="496" height="130" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="130" alt=""></td>
	</tr>
	<tr>
		<td rowspan="11">
			<img src="l-imgs/furniture_36.gif" width="13" height="242" alt=""></td>
		<td rowspan="4">
			<img src="l-imgs/furniture_37.gif" width="12" height="118" alt=""></td>
		<td colspan="14">
			<img src="l-imgs/furniture_38.gif" width="453" height="9" alt=""></td>
		<td rowspan="4">
			<img src="l-imgs/furniture_39.gif" width="7" height="118" alt=""></td>
		<td rowspan="11">
			<img src="l-imgs/furniture_40.gif" width="11" height="242" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="9" alt=""></td>
	</tr>
	<tr>
		<td rowspan="10">
			<img src="l-imgs/furniture_41.gif" width="2" height="233" alt=""></td>
		<td colspan="3" rowspan="2">
			<img src="l-imgs/furniture_42.gif" width="119" height="99" alt=""></td>
		<td colspan="10" rowspan="10">
			<img src="l-imgs/furniture_43.gif" width="332" height="233" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="82" alt=""></td>
	</tr>
	<tr>
		<td rowspan="9">
			<img src="l-imgs/furniture_44.gif" width="9" height="151" alt=""></td>
		<td colspan="4" rowspan="2">
			<img src="l-imgs/furniture_45.gif" width="178" height="27" alt=""></td>
		<td colspan="2" rowspan="5">
			<img src="l-imgs/furniture_46.gif" width="17" height="121" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="17" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="3">
			<img src="l-imgs/furniture_47.gif" width="119" height="26" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="3">
			<img src="l-imgs/furniture_48.gif" width="178" height="94" alt=""></td>
		<td>
			<img src="l-imgs/furniture_49.gif" width="12" height="6" alt=""></td>
		<td>
			<img src="l-imgs/furniture_50.gif" width="7" height="6" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td rowspan="6">
			<img src="l-imgs/furniture_51.gif" width="12" height="118" alt=""></td>
		<td rowspan="6">
			<img src="l-imgs/furniture_52.gif" width="7" height="118" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="4">
			<img src="l-imgs/furniture_53.gif" width="119" height="99" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="78" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="4">
			<img src="l-imgs/furniture_54.gif" width="140" height="30" alt=""></td>
		<td colspan="2" rowspan="2">
			<img src="l-imgs/furniture_55.gif" width="45" height="17" alt=""></td>
		<td rowspan="4">
			<img src="l-imgs/furniture_56.gif" width="10" height="30" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="16" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<img src="l-imgs/furniture_57.gif" width="8" height="14" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<img src="l-imgs/furniture_58.gif" width="45" height="13" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="4" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="l-imgs/furniture_59.gif" width="119" height="9" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="9" alt=""></td>
	</tr>
	<tr>
		<td colspan="30">
			<img src="l-imgs/furniture_60.gif" width="743" height="7" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="7" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="l-imgs/furniture_61.gif" width="13" height="60" alt=""></td>
		<td colspan="2">
			<img src="l-imgs/furniture_62.gif" width="24" height="60" alt=""></td>
		<td>
			<img src="l-imgs/furniture_63.gif" width="48" height="60" alt=""></td>
		<td colspan="25">
			<img src="l-imgs/furniture_64.gif" width="647" height="60" alt=""></td>
		<td>
			<img src="l-imgs/furniture_65.gif" width="11" height="60" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="60" alt=""></td>
	</tr>
	<tr>
		<td colspan="30">
			<img src="l-imgs/furniture_66.gif" width="743" height="33" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="33" alt=""></td>
	</tr>
	<tr>
		<td colspan="30">
			<img src="l-imgs/furniture_67.gif" width="743" height="11" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="1" height="11" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="l-imgs/spacer.gif" width="15" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="13" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="15" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="9" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="48" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="22" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="70" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="38" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="6" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="2" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="13" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="12" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="2" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="66" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="24" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="29" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="25" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="23" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="71" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="4" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="13" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="40" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="20" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="103" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="30" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="3" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="11" height="1" alt=""></td>
		<td>
			<img src="l-imgs/spacer.gif" width="12" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
<!-- End ImageReady Slices -->
</body>
</html>';
        return $string;
    }

}
?>