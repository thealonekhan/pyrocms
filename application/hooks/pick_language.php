<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function PickLanguage() {
    
	require_once(APPPATH.'/config/language.php');
       
    // Lang set in URL via ?lang=something
    if(!empty($_GET['lang']))
    {
        // Turn en-gb into EN
        $lang = strtoupper(substr($_GET['lang'], 0, 2));
        $_SESSION['lang_code'] = $lang;
    }
    
    // Lang has already been set and is stored in a session
    elseif( !empty($_SESSION['lang_code']) )
    {
        $lang = $_SESSION['lang_code'];
    }
    
    // Lang has is picked by a user.
    // Set it to a session variable so we are only checking one place most of the time
    elseif( !empty($_COOKIE['lang_code']) )
    {
        $lang = $_SESSION['lang_code'] = $_COOKIE['lang_code'];
    }
    
    // Still no Lang. Lets try some browser detection then
    else if (!empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ))
    {
    	// explode languages into array
    	$accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

    	log_message('debug', 'Checking browser languages: '.implode(', ', $accept_langs));

    	// Check them all, until we find a match
    	foreach ($accept_langs as $lang)
    	{
    		// Turn en-gb into EN
    		$lang = strtoupper(substr($lang, 0, 2));

    		// Check its in the array. If so, break the loop, we have one!
    		if(in_array($lang, array_keys($config['supported_languages'])))
    		{
    			break;
    		}
    	}
    }
    
    // If no language has been worked out - or it is not supported - use the default
    if(empty($lang) or !in_array($lang, array_keys($config['supported_languages'])))
    {
        $lang = $config['default_language'];
    }

    // Whatever we decided the lang was, save it for next time to avoid working it out again
    $_SESSION['lang_code'] = $lang;
        
    // Load CI config class
    $CI_config =& load_class('Config');

    // Set the language config. Getting 'English' from the array by calling it from its key of 'EN', and make it lowercase so CI looks for 'english'
    $CI_config->set_item('language', strtolower($config['supported_languages'][$lang]));

    // Sets a constant to use throughout ALL of CI.
    define('DEFAULT_LANGUAGE', $lang);
}

?>