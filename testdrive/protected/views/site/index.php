<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Selamat Data di  <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Anda Telah Sukses melakukan installasi YII pada Desktop Anda.</p>

<p>Untuk keterangan lebih lanjut anda bisa melihatnya pada bagian bawah ini!</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>Untuk informasi dalam melakukan pengembangan aplikasi bisa melihat dokmentasi di bawah ini
ini<a href="http://www.yiiframework.com/doc/">dokumentasi</a>.
untuk pertanyaan anda bisa bertana di <a href="http://www.yiiframework.com/forum/">forum</a>,
Jika anda mempunya pertanyaan serupa.</p>
