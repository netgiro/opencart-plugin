<!--

-->
<iframe id="payment_iframe" name="payment_iframe" width="100%" height="825px" frameBorder="0" style="display:none; margin-left:0%;"></iframe>

<?php if (version_compare(VERSION, '2.0', '>=')) { // v2.0.x Compatibility ?>


<?php if (isset($error) && $error) { ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>
<?php if (isset($testmode) && $testmode) { ?>
<div class="alert alert-info"><?php echo $text_testmode; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="checkout-form" target="payment_iframe">
  <?php foreach ($fields as $key => $value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="submit" value="<?php echo $button_continue; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>




<?php } else { // 1.5.x version check ?>


<?php if (isset($error) && $error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if (isset($testmode) && $testmode) { ?>
  <div class="warning"><?php echo $this->language->get('text_testmode'); ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" id="checkout-form" target="payment_iframe">
  <?php foreach ($fields as $key => $value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>
<div class="buttons" style="text-align: right;min-height:20px;">
  <div class="right">
    <?php /* <a id="button-confirm" class="button"><span><?php echo $button_continue; ?></span></a> */ ?>
	<input type="button" value="<?php echo $button_continue; ?>" id="button-confirm" class="button" />
  </div>
</div>


<?php } // End version check ?>
 
 
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$('#payment_iframe').slideDown();
	$('#button-confirm').hide();
	$('#checkout-form').submit();
});
//--></script>