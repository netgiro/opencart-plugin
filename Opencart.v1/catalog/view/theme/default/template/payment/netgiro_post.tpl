<!--
-->
<?php if (!empty($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>

<iframe id="payment_iframe" name="payment_iframe" width="100%" height="825px" frameBorder="0" style="display:none; margin-left:0%;"></iframe>

<form action="<?php echo $action; ?>" method="post" id="checkout-form" target="payment_iframe">
  <?php foreach ($fields as $key => $value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>

<div class="buttons">
  <div class="right">
    <a onclick="confirmSubmit();" id="button-confirm" class="button"><span><?php echo $button_continue; ?></span></a></td>
  </div>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {

	$('#payment_iframe').slideDown();
	$('#button-confirm').hide();

	$('#checkout-form').submit();

}
//--></script>