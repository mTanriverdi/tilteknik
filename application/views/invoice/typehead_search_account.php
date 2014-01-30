<meta charset="utf-8" />

<div class="ajax_search_box">
<table class="table table-bordered table-hover table-condensed">
    <tbody>
    <?php foreach($accounts as $account): ?>
    	<tr>
        	<td class="">
            	<div class="pointer select_account" style="height:40px;"
                	data-id="<?php echo $account['id']; ?>"
                	data-code="<?php echo $account['code']; ?>"
                    data-name="<?php echo $account['name']; ?>"
                    data-gsm="<?php echo $account['gsm']; ?>"
                >
                	<h4 style="margin:5px 0px; padding:0px;"><?php echo $account['name']; ?></h4>
                    <small class="pull-left"><?php echo $account['code']; ?></small>
                    <small class="pull-right"><?php echo $account['gsm']; ?></small>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div> <!-- /.ajax_search_box -->

<script>
$('.select_account').click(function(){
	$('#account_id').val($(this).attr('data-id'));
	$('#account_name').val($(this).attr('data-name'));
});
</script>