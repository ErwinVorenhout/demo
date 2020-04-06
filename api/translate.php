<?php 
class Translate
{
	private $_availableLanguages = array('nl');
	private $_language;

	public function __construct($language)
	{
		if(isset($language))
		{
			$this->language = $language;	
		}
	}
	/**
	 * _getTranslation 
	 * 
	 * This method get all translations from given translationFile
	 * 
	 * 
	 * @param string $baseText
	 * @return  string translatedText
	 */ 
	public function getTranslation($baseText)
	{
		switch($this->language)
		{
			case 'nl':
			
				$translationFile = include('nl.php');
				if(isset($language[$baseText]))
				{
					return $language[$baseText];
				}
				else
				{
					return $baseText;
				}
			break;
			default:
				return $baseText;
			break;
		}
	}
}
 ?>