<h3>Ближайший праздник: </h3>
<p class="selectedDate"> Дата ближайшего праздника: <?= $info['date2'] ?> </p>

<p> За день до выбранной даты: <span class="prevDate" id="prevDate" onclick="prevDate()"><?= $info[0] ?></span></p>
<!-- <p class="pictureDate"><img src="/media/k2/items/treatment/!test.jpg"></p> -->
<p class="pictureDate"><img src="/media/k2/items/treatment/<?= $info['kind'] ?>/item_<?= $info['id'] ?>.jpg"></p>
<p class="nextDate" id="nextDate"> Следующий день после выбранной даты: <?= $info[1] ?> </p>
<p><?= $info['title'] ?> </p>
<p><?= $info['text'] ?> </p>