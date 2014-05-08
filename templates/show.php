<?php
$short_url = $this->data["short_url"];
$original = $this->data["original"];
$twitter_web_url = $this->data["twitter_web_url"];
$twitter_app_url = $this->data["twitter_app_url"];
$tweetbot_url = $this->data["tweetbot_url"];
$twitterrific_url = $this->data["twitterrific_url"];
$twittelator_url = $this->data["twittelator_url"];
$echofon_url = $this->data["echofon_url"];
$email_url = $this->data["email_url"];
$helper = $this->data["helper"];

include('form.php');
?>

<div class="thinline"></div>

<div class="instructionsection"><p class="title"><?= $helper->link_to($short_url, $short_url, array("class" => "shortened")) ?> now directs to &#x2013;</p>
<p class="contents"><?= $helper->link_to($original, $original, array("id" => "originallink")) ?></p></div>

<div class="thinline"></div>

<div class="instructionsection"><p class="title">Share <?= $helper->link_to($short_url, $short_url)?></p>
<p class="contents"><?= $helper->link_to("Twitter (web),", $twitter_web_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Twitter (app),", $twitter_app_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Tweetbot,", $tweetbot_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Twitterrific,", $twitterrific_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Twittelator Pro,", $twittelator_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Echofon,", $echofon_url, array("class" => "shortened"))?>
 <?= $helper->link_to("Email", $email_url, array("class" => "shortened"))?></p></div>

