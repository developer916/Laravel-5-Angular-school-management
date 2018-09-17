<meta name="csrf-token" content="<?php echo csrf_token() ?>">
<?php
$p = file_get_contents("index.html");
echo $p;
?>