<?php
/**
 * здесь описание и комментарии
 */
defined('_JEXEC') or die;
 
// подключаем наш хелпер
require_once __DIR__ . '/helper.php';
 
//вызываем метод getNews(), который находится в хелпере 
//(извлекает из базы данных нужную нам информацию
$news = modNewsHelper::getNews();
 
//подключаем html-шаблон для вывода содержания модуля (шаблон default).
require JModuleHelper::getLayoutPath('mod_news', $params->get('layout', 'default'));