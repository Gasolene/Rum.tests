<?php header('content-type:application/xml') ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<response>

<?php if( isset( $_POST['data'] )) : ?>
  <message>success</message>
  <data><?php print_r( $_POST['data'] ) ?></data>
<?php else : ?>
  <message>fail</message>
<?php endif; ?>

</response>
