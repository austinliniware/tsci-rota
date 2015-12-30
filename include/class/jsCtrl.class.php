<?php
class jsCtrl {

	public static function Location($href){
             echo "<script language='javascript'>\n";
             echo "location.href='$href';\n";
             echo "</script>\n";
        }
	public static function Alert($text){
             echo "<script language='javascript'>\n";
             echo "alert('$text');\n";
             echo "</script>\n";
        }
	public static function Alert_close($text){
             echo "<script language='javascript'>\n";
             echo "alert('$text');\n";
             echo "window.close();\n";
             echo "</script>\n";
        }
	public static function Alert_Location($text,$url){
             echo "<script language='javascript'>\n";
             echo "alert('$text');\n";
             echo "location.href=\"".$url."\";\n";
             echo "</script>\n";
        }
	public static function close(){
             echo "<script language='javascript'>\n";
             echo "window.close();\n";
             echo "</script>\n";
        }
	public static function failAlert($text){
             echo "<script language='javascript'>\n";
             echo "alert('$text');history.back(-1);\n";
             echo "</script>\n";
        }
	public static function back(){
             echo "<script language='javascript'>\n";
             echo "history.back(-1);\n";
             echo "</script>\n";
        }
	public static function succAlert($text){
             echo "<script language='javascript'>\n";
             echo "alert('$text');window.close();opener.focus();opener.location.reload();\n";
             echo "</script>\n";
        }
	public static function Confirm($text){
             echo "<script language='javascript'>\n";
             echo "if(!confirm('$text'))\n";
             echo "{window.close();opener.focus();opener.location.reload();}\n";
             echo "</script>\n";
        }
	public static function open($url){
             echo "<script language='javascript'>\n";
             echo "window.open('".$url."');\n";
             echo "</script>\n";
        }
}

?>