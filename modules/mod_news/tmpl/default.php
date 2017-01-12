<?php
defined('_JEXEC') or die;
?>
<?php
$news = [
	['title' => 'title', 
	'data' => 'data', 
	]
];
?>
<?php foreach ($news as $item) : ?>
	<li><?php echo $item->title;?></li>
	<li><?php echo $item->data;?></li>
<?php endforeach; ?>