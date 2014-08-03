<?php
/**
 * body.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $title; ?></title>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="http://yandex.st/bootstrap/3.1.1/css/bootstrap.min.css" />
<script src="http://yandex.st/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
var app = {
    host: '<?= App::instance()->request->getHost() . App::instance()->urlFor('base'); ?>',
    time: <?= time(); ?>

};
</script>

</head>

<body class="<?= $body_class; ?>">
<?= $body; ?>
</body>

</html>
