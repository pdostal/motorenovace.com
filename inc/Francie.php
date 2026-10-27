<?php
  $title = "Francie 2009";
?>
<h2><?php echo $title; ?></h2>
<?php echo photo("francie/01.JPG", $title, ""); ?><br />
<?php
  for ($i = 2; $i < 54; $i++) {
    if ($i < 10) {
      $i = "0$i";
    }
    echo photo("francie/$i.JPG", $title, "");
  }
?>
