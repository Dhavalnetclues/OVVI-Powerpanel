<?php 
/**
 * An Offensive Word Filtering Class
 *
 */
namespace App\Helpers;

class Offensiveword
{   
	static  private $_word_list;
	static  private $_total_words_in_list;
	
	/**
	 * Class Constructor
	 * 
	 * Currently this is not used because the public methods that are
	 * available are all statically called
	 */
	public function __construct( )
	{

	}
	
	/**
	 * Load the offensive word list
	 *
	 * @return boolean
	 */
	static private function load_bad_word_list( )
	{		
		if( is_file(__DIR__.'/badwords/en.txt') && is_readable( __DIR__.'/badwords/en.txt' ) )
		{
			$lines = file( __DIR__.'/badwords/en.txt' );			
			self::$_total_words_in_list = count( $lines );
			for( $i = 0; $i <= ( self::$_total_words_in_list - 1); $i++ ) if( $lines[$i] != "" ) self::$_word_list[] = trim( strtolower( $lines[$i] ) );
			usort( self:: $_word_list, array( __CLASS__,'sort' ) );
			$lines = null;
			return true;
		}
		else return false;
	}
	
	/**
	 * Sort the word list by string length, longest to smallest string. This
	 * is the user defined callback function for the usort function call
	 * in the load_bad_word_list method.
	 *
	 * @param string $a
	 * @param string $b
	 * @return integer
	 */
	static private function sort( $a, $b )
	{
		if( strlen( $a ) < strlen( $b ) ) return 1;
		if( strlen( $a ) > strlen( $b ) ) return -1;
		
		// they must be the same length
		return 0;
	}
	
	/**
	 * Check to see if there are offensive words in the string
	 *
	 * @param string $string
	 * @return boolean
	 */
	static public function isOffensive( $string )
	{
		if( count( self::$_word_list ) == 0 ) self::load_bad_word_list( );
		for( $i = 0; $i <= ( self::$_total_words_in_list - 1 ); $i++ )
		{
			$varTemp=stripos( $string, self::$_word_list[$i]);
			if( $varTemp == '' ) return true;
//      if( stripos( $string, self::$_word_list[$i], true ) ) return true;
		}
		return false;
	}
	
	/**
	 * Actually censor out the offensive words from the string.
	 *
	 * @param string $string by reference
	 * @return boolean
	 */
	static public function censor( &$string = null,$isAlias=null )
	{
		if( self::isOffensive( $string ) === false ) return true;
		for( $i = 0; $i <= ( self::$_total_words_in_list - 1 ); $i++ )
		{
			$Tempdata='/\b'.self::$_word_list[$i].'\b/i';			
			if($isAlias){
					$string = preg_replace($Tempdata, substr(self::$_word_list[$i], 0, 1).sprintf("%'-".(strlen(self::$_word_list[$i])-2)."s", NULL).substr(self::$_word_list[$i], strlen(self::$_word_list[$i])-1, 1), $string);
			}else{
					$string = preg_replace($Tempdata, substr(self::$_word_list[$i], 0, 1).sprintf("%'*".(strlen(self::$_word_list[$i])-2)."s", NULL).substr(self::$_word_list[$i], strlen(self::$_word_list[$i])-1, 1), $string);
			}		
			//$string = eregi_replace(self::$_word_list[$i], substr(self::$_word_list[$i], 0, 1).sprintf("%'*".(strlen(self::$_word_list[$i])-2)."s", NULL).substr(self::$_word_list[$i], strlen(self::$_word_list[$i])-1, 1), $string);
		}
		return $string; 
	}
}

#$string = 'Some crappy words are in this crap.';
#OffensiveWord::censor( $string );
#echo $string;
// outputs: Some c****y words are in this c**p