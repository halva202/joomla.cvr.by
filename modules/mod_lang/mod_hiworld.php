<?php
defined('_JEXEC') or die;
 
echo 'Hi, world! lang<br>';

/* $app = JFactory::getApplication();
 
// Определяем язык из cookies
$langCode = $app->input->cookie->getString(JApplication::getHash('language'));
 
// Если cookies не установлены, используем язык обозревателя
if (!$langCode)
{
    $langCode = JLanguageHelper::detectLanguage();
}

echo $langCode; */