<?php

class safeString {
    
	/* ----------------------------------*/
	/* Properties						 */
	/* ----------------------------------*/		
	
  	protected static $transliterationTable = array(
												 'à' => 'a', 
												 'À' => 'A',
												 'Á' => 'A', 
												 'á' => 'a',												 
												 'â' => 'a', 
												 'Â' => 'A', 
												 'å' => 'a', 
												 'Å' => 'A', 
												 'ä' => 'a', 
												 'Ä' => 'A', 
												 'æ' => 'e', 
												 'Æ' => 'E',
												 'é' => 'e', 
												 'É' => 'E', 
												 'è' => 'e', 
												 'È' => 'E', 
												 'ê' => 'e', 
												 'Ê' => 'E', 
												 'ë' => 'e', 
												 'Ë' => 'E', 
												 'œ' => 'e',
												 'í' => 'i', 
												 'Í' => 'I', 
												 'ì' => 'i', 
												 'Ì' => 'I', 
												 'î' => 'i', 
												 'Î' => 'I', 
												 'ï' => 'i', 
												 'Ï' => 'I', 
												 'ó' => 'o', 
												 'Ó' => 'O', 
												 'ò' => 'o', 
												 'Ò' => 'O', 
												 'ô' => 'o', 
												 'Ô' => 'O', 
												 'ő' => 'o', 
												 'Ő' => 'O', 
												 'õ' => 'o', 
												 'ø' => 'o', 
												 'Ø' => 'O', 
												 'ō' => 'o', 
												 'ș' => 's', 
												 'Ș' => 'S', 
												 'ţ' => 't', 
												 'Ţ' => 'T', 
												 'ú' => 'u', 
												 'Ú' => 'U', 
												 'ù' => 'u', 
												 'Ù' => 'U',												  
												 'û' => 'u', 
												 'Û' => 'U', 
												 'ü' => 'u', 
												 'Ü' => 'U', 												
												 'ÿ' => 'y', 
												 'Ÿ' => 'Y', 
												 'ç' => 'c',
												 'Ç' => 'C',
												 'ñ' => 'n',
												 'Ñ' => 'N'
												);
  
   

	/* ----------------------------------*/
	/* Methods							 */
	/* ----------------------------------*/
	
	
  	/** 
     * clearString  
     * 
     * Method used to clean a string to be safely appended to the url
     * 
     * 
     */   
  public static function clearString($text){

 	// return converted string
	if (function_exists('remove_accents')){
		$text = remove_accents($text);
	} else {
		$text = str_replace(array_keys(self::$transliterationTable), array_values(self::$transliterationTable), $text);
	}
	$text = str_replace(' ','-',$text);
	$text = preg_replace("/\'|\!|\@|\#|\$|\€|\^|\&|\*|\:|\~|\.|\,|\;|\(|\)|\{|\}|\[|\]|\?|\>|\<|\||\+|\/|\=|\`|%|\"\s/i", '-', $text);
	
	$text = preg_replace('/(\-){2,}/',"-", $text);
  	$text = preg_replace('/(^\-|\-\z)/',"", $text);

	return $text;

  }

}

?>