<?php $invoice = get_invoice($invoice_id); ?>
<?php $account = get_account($invoice['account_id']); ?>

<?php
if($invoice['type'] == 'payment')
{
	redirect(site_url('payment/view/'.$invoice['id']));	
}
?>

<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('invoice'); ?>"><?php lang('Buying-Selling'); ?></a></li>
    <li><a href="<?php echo site_url('invoice/invoice_list'); ?>">Fatura Listesi</a></li>
	<li class="active">
    
    <?php if($invoice['in_out'] == 0): ?>
    	Giriş Faturası
	<?php else: ?>
   		Çıkış Faturası
	<?php endif; ?>
    
    #<?php echo $invoice['id']; ?></li>
</ol>

<?php 
if(isset($_GET['status'])) 
{ 
	change_status_invoice($invoice_id, array('status'=>$_GET['status'])); 
	
	$data['type'] = 'invoice';
	$data['invoice_id'] = $invoice['id'];
	$data['account_id'] = $invoice['account_id'];
	$data['title'] = get_lang('Invoice');
	if($_GET['status'] == 0){$data['description'] = get_lang('Deleted Invoice.');}else{$data['description'] = get_lang('Activated bill again.');}
	add_log($data);
	$invoice = get_invoice($invoice_id);
} 
?>


<?php if($invoice['status'] == 0): ?>
	<?php alertbox('alert-danger', get_lang('Deleted Invoice.'), '', false); ?>
<?php endif; ?>




<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#transactions" data-toggle="tab"><i class="fa fa-shopping-cart"></i> İşlemler</a></li>
    <li class=""><a href="#history" data-toggle="tab"><i class="fa fa-comments"></i> Log</a></li>
    <li class="dropdown">
		<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-asterisk"></i> Seçenekler <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
			<li><a href="<?php echo site_url('user/new_message/?invoice_id='.$invoice['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?invoice_id='.$invoice['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
            
            <li class="divider"></li>
        	<li><a href="<?php echo site_url('invoice/invoice_print/'.$invoice['id']); ?>"><span class="glyphicon glyphicon-print mr9"></span><?php lang('Print Invoice'); ?></a></li>
            
            <?php if(get_the_current_user('role') <= 3): ?>
                <li class="divider"></li>
                <?php if($invoice['status'] == '1'): ?>
                    <li><a href="?status=0"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Delete'); ?></a></li>
                <?php else: ?>
                    <li><a href="?status=1"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Activate'); ?></a></li>
                <?php endif; ?>
            <?php endif; ?>
      </ul>
    </li>
</ul>





<div id="myTabContent" class="tab-content">



<div class="tab-pane fade active in" id="transactions">
<div class="row">
<div class="col-md-12">


<div class="row show-grid">
	<div class="col-md-8">
    	<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation widget">
            <div class="header"><i class="fa fa-user"></i> Müşteri Bilgileri</div>
            <div class="content">     			
                <div class="row">
                    <div class="col-md-2">
                    	<a href="<?php echo site_url('account/view/'.$invoice['account_id']); ?>" target="_blank" class="img-thumbnail"> <img src="<?php echo base_url('theme/img/logo/128x128.png'); ?>" class="img-responsive" /></a>
                    </div> <!-- /.col-md-2 -->
                    <div class="col-md-5">
                        <a href="<?php echo site_url('account/view/'.$invoice['account_id']); ?>" target="_blank"> 
                            <div class="form-group pointer">
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon pointer"><label for="account_name" class="pointer"><span class="fa fa-user"></span></label></span>
                                    <input type="text" id="account_name" name="account_name" class="form-control ff-1 pointer required" minlength="3" maxlength="30" value="<?php echo $account['name']; ?>" readonly>
                                </div>
                            </div> <!-- /.form-group -->
                        </a>
                        
                        
                        <ul class="list-group">
  							<li class="list-group-item" style="padding:7px;"><span class="glyphicon glyphicon-phone"></span> <?php echo $account['gsm']; ?> </li>
                       		<li class="list-group-item" style="padding:7px;"><span class="glyphicon glyphicon-envelope"></span> <?php echo $account['email']; ?></li>
                       	</ul>
 
                    </div> <!-- /.col-md-4 -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-prepend input-group pointer">
                                <span class="input-group-addon"><label for="date" class="pointer"><span class="glyphicon glyphicon-calendar"></span></label></span>
                                <input type="text" id="date" name="date" class="form-control ff-1 required datepicker pointer" placeholder="<?php lang('Start Date'); ?>" minlength="3" maxlength="50" value="<?php echo substr($invoice['date'],0,10); ?>" readonly>
                            </div>
                        </div> <!-- /.form-group -->

                        <div class="form-group">
                            <div class="input-prepend input-group">
                                <span class="input-group-addon"><label for="description" class="pointer"><span class="fa fa-comment"></span></label></span>
                                <textarea id="description" name="description" class="form-control ff-1 fs-12" style="height:68px;"><?php echo $invoice['description']; ?></textarea>
                            </div>
                        </div> <!-- /.form-group -->
                    </div> <!-- /.col-md-4 -->
                </div> <!-- /.row -->
            </div> <!-- /.content -->
		</form>
    </div> <!-- /.col-md-8 -->
    <div class="col-md-4">
    	<div class="widget">
        	<?php if($invoice['in_out'] == '1'): ?>
        	<div class="header"><i class="fa fa-bar-chart-o"></i> #<?php echo $invoice['id']; ?> Numaralı Fiş Raporu</div>
            <div class="content">
            	
            	<div class="form-group col-md-6" style="padding:0px 5px;">
                    <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Maliyet</label>
                    <div class="input-prepend input-group">
                        <span class="input-group-addon"><span class="fa fa-try"></span></span>
                        <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($invoice['grand_total'] - $invoice['profit']); ?>" disabled="disabled">
                    </div>
                </div>
                <div class="form-group col-md-6" style="padding:0px 5px;">
                    <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Genel Toplam </label>
                    <div class="input-prepend input-group">
                        <span class="input-group-addon"><span class="fa fa-try"></span></span>
                        <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($invoice['grand_total']); ?>" disabled="disabled">
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="padding:0px 5px;">
                    <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Vergi</label>
                    <div class="input-prepend input-group">
                        <span class="input-group-addon"><span class="fa fa-try"></span></span>
                        <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($invoice['tax']); ?>" disabled="disabled">
                    </div>
                </div>
                <div class="form-group  has-success col-md-6" style="padding:0px 5px;">
                    <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Net Kazanç</label>
                    <div class="input-prepend input-group">
                        <span class="input-group-addon"><span class="fa fa-try"></span></span>
                        <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($invoice['profit']); ?>" disabled="disabled">
                    </div>
                </div>
                
                <div class="clearfix"></div>
            </div> <!-- /.content -->
            <?php else: ?>
            <div class="header"><i class="fa fa-bar-chart-o"></i> Giriş Faturası</div>
            <div class="content">
            	
                <div class="form-group col-md-6" style="padding:0px 5px;">
                    
                </div>
                <div class="form-group col-md-6" style="padding:0px 5px;">
                    <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Genel Toplam </label>
                    <div class="input-prepend input-group">
                        <span class="input-group-addon"><span class="fa fa-try"></span></span>
                        <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($invoice['grand_total']); ?>" disabled="disabled">
                    </div>
                </div>
                
                <small>Şu anda alış faturası yani stok girişi yapmaktasın. Alış faturası girildiğinde stok sayısı çoğalır, tam tersi olarak satış faturası girildiğinde stok sayısı azalır.</small>
                
                <div class="clearfix"></div>
            </div> <!-- /.content -->
            <?php endif; ?>
        </div> <!-- /.widget -->
    </div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


<div class="h20"></div>
<div class="widget">
	<div class="header"><i class="fa fa-shopping-cart"></i> Fiş Hareketleri</div>
    <div class="content">
<?php
if(@$formError) { alertbox('alert-danger', $formError);	 }
if(@$error) { alertbox('alert-danger', $error);	 }
if(isset($barcode_code_unknown)){alertbox('alert-danger', 'Stok kodu bulunamadı.', '"'.$_POST['code'].'" stok koduna ait stok kartı açılmamış olabilir.');}
if(@$success) { foreach($success as $alert){ echo $alert;}}
?>
<form name="form_item" id="form_item" action="<?php echo site_url('invoice/view/'.$invoice['id']); ?>" method="POST" class="validation_2" style="padding:14px;">
<div class="row space-1">
	<div class="col-md-4">
    	<div class="form-group">
        	<label for="amount" class="control-label ff-1 ">Stok kodu veya stok adı</label>
            <div class="input-prepend input-group">
                <span class="input-group-addon pointer"><span class="glyphicon glyphicon-barcode fs-14"></span></span>
                <input type="text" id="code" name="code" class="form-control invoice_input barcodeCode required" placeholder="<?php lang('Barcode Code'); ?>" maxlength="100" value="" autocomplete="off">
            </div>
        </div> <!-- /.form-group -->
        <div class="search_product typeHead"></div>
    </div> <!-- /.col-md-6 -->
    <div class="col-md-1">
        <div class="form-group">
            <label for="amount" class="control-label ff-1 "><?php lang('Amount'); ?></label>
            <div class="input-prepend input-group">
                <input type="text" id="amount" name="amount" class="form-control" placeholder="0" maxlength="11" value="1" onkeyup="calc();" />
            </div>
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-1 -->
    <div class="col-md-1">
    	<div class="form-group">
            <label for="quantity_price" class="control-label ff-1 fs-12"><?php lang('Q. Price'); ?></label>
            <input type="text" id="quantity_price" name="quantity_price" class="form-control ff-1 text-right" placeholder="0.00" value="" style="padding:5px;" onkeyup="calc();">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-2 -->
     <div class="col-md-1">
    	<div class="form-group">
            <label for="total" class="control-label ff-1 "><?php lang('Total'); ?></label>
            <input type="text" id="total" name="total" class="form-control text-right" placeholder="0.00" value="" style="padding:5px;" readonly="readonly">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-2 -->
    <div class="col-md-1">
     	<label for="tax_rate" class="control-label ff-1">Kdv Oranı</label>
        <div class="clearfix"></div>
    	<div class="form-group">
            <input type="text" id="tax_rate" name="tax_rate" class="form-control text-center" placeholder="0" value="" onkeyup="calc();">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-1 -->
    <div class="col-md-1">
     	<label for="tax_rate" class="control-label ff-1">Kdv</label>
        <div class="clearfix"></div>
    	<div class="form-group">
            <input type="text" id="tax" name="tax" class="form-control text-right" placeholder="0.00" value="" readonly="readonly">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-1 -->
    <div class="col-md-1">
    	<div class="form-group">
            <label for="sub_total" class="control-label"><?php lang('Sub Total'); ?></label>
            <input type="text" id="sub_total" name="sub_total" class="form-control text-right" placeholder="0.00" value="" onkeyup="calc_subtotal();">
        </div>
    </div> <!-- /.col-md-1 -->
    <div class="col-md-2">
    	<label>&nbsp;</label>
        <input type="hidden" name="microtime" value="<?php echo logTime(); ?>" />
        <input type="hidden" name="item" value="" />
    	<button class="btn btn-default btn-block btn-sm"><?php lang('Add'); ?></button>
    </div> <!-- /.col-md-1 -->
</div> <!-- /.row -->

<script>
$(document).ready(function(e) {
	$('#code').keyup(function() {
		$('.typeHead').show();
		$.get("../../search_product_for_invoice/"+$(this).val()+"?<?php if($invoice['in_out']=='0'){echo'cost_price';} ?>", function( data ) {
		  $('.search_product').html(data);
		});
	});
});
</script>

</form>





<!-- items -->

<?php
$this->db->where('status', 1);
$this->db->where('invoice_id', $invoice_id);
$items = $this->db->get('fiche_items')->result_array();
?>

<table class="table table-hover table-bordered table-condensed">
	<thead>
    	<tr>
        	<th width="1"></th>
        	<th width="150"><?php lang('Barcode Code'); ?></th>
            <th><?php lang('Product Name'); ?></th>
            <th><?php lang('Quantity'); ?></th>
            <th><?php lang('Quantity Price'); ?></th>
            <th><?php lang('Total'); ?></th>
            <th><?php lang('Tax Rate'); ?></th>
            <th><?php lang('Tax'); ?></th>
            <th><?php lang('Sub Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $item): ?>
    	<?php
		if($item['product_id'] > 0)
		{
			$product = get_product($item['product_id']);
		}
		else
		{
			$product['id'] = '';
			$product['code'] = $item['product_code'];	
			$product['name'] = $item['product_name'];	
		}
		?>
    	<tr>
        	<td>
            	<a href="<?php echo site_url('invoice/view/'.$invoice['id'].'/?item_id='.$item['id'].'&delete_item=✓'); ?>" title="Sil" class="text-danger"><span class="fa fa-trash-o fs-14"></span></a>
            </td>
        	<td class="fs-10">
            	<?php if($product['id'] > 0): ?>
                	<a href="<?php echo site_url('product/view/'.$product['id']); ?>" target="_blank"><?php echo $product['code']; ?></a>
            	<?php else: ?>
                	<?php echo $product['name']; ?>
                <?php endif; ?>
            </td>
            <td class="fs-10"><?php echo $product['name']; ?></td>
            <td class="text-center"><?php echo get_amount($item['quantity']); ?></td>
            <td class="text-right"><?php echo get_money($item['tax_free_sale_price']); ?> <small>TL</small></td>
            <td class="text-right"><?php echo get_money($item['total']); ?> <small>TL</small></td>
            <td class="text-center">% (<?php echo $item['tax_rate']; ?>)</td>
            <td class="text-right"><?php echo get_money($item['tax']); ?> <small>TL</small></td>
            <td class="text-right"><?php echo get_money($item['sub_total']); ?> <small>TL</small></td>
        </tr>	
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    	<tr class=" no-strong">
        	<th colspan="3" class="text-center no-strong"><?php lang('Grand Total'); ?></th>
            <th class="text-center no-strong text-danger"><?php echo $invoice['quantity']; ?></th>
            <th></th>
            <th colspan="1" class="text-right no-strong text-danger"><?php echo get_money($invoice['total']); ?> <small>TL</small></th>
            <th colspan="2" class="text-center no-strong text-danger"><?php echo get_money($invoice['tax']); ?> <small>TL</small></th>
            <th class="text-right fs-16 no-strong text-danger"><?php echo get_money($invoice['grand_total']); ?> <small>TL</small></th>
        </tr>
    </tfoot>
</table>
<!-- /items -->

	</div> <!-- /.content -->
</div> <!-- /.widget -->
	

</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
</div> <!-- /#transactions -->





<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('invoice_id'=>$invoice['id']), 'ASC'); ?>
</div> <!-- #history -->





</div> <!-- /#myTabContent -->



<style>
.barcodeCode:focus { border:1px solid #f00 !important; }
</style>

<script>
$('.openModal-product_list').click(function() {
	$('#modal-product_list').click();
});

$('.barcodeCode').focus();
$('#serial_box').hide();

function calc()
{
	if($('#quantity_price').val() == 'NaN'){ $('#quantity_price').val('0'); }
	
	var amount 		= $('#amount').val();	
	var quantity_price = $('#quantity_price').val();	
	var total 		= $('#total').val();
	var tax_rate 	= $('#tax_rate').val();
	var tax 		= $('#tax').val();
	var sub_total 	= $('#sub_total').val();
	
	$('#total').val(parseFloat(amount * quantity_price).toFixed(2));
	
	tax = parseFloat($('#total').val() / 100);
	tax_rate = $('#tax_rate').val();
	if(tax_rate == ''){tax_rate = 0;} 
	$('#tax').val(parseFloat(parseFloat(tax) * parseInt(tax_rate)).toFixed(2));
	$('#sub_total').val(parseFloat(parseFloat($('#total').val()) + parseFloat($('#tax').val())).toFixed(2));
	$('#sub_total').val(parseFloat($('#sub_total').val()).toFixed(2));
}


function calc_subtotal()
{
	if($('#quantity_price').val() == 'NaN'){ $('#quantity_price').val('0'); }
	if($('#sub_total').val() == ''){ $('#sub_total').val('0'); }
	
	var amount 		= $('#amount').val();	
	var quantity_price = $('#quantity_price').val();	
	var total 		= $('#total').val();
	var tax_rate 	= $('#tax_rate').val();
	var tax 		= $('#tax').val();
	var sub_total 	= $('#sub_total').val();
	
	
	total = parseFloat(parseFloat(sub_total) / parseFloat('1.'+tax_rate)).toFixed(2);
	
	$('#total').val(parseFloat(total).toFixed(2));
	$('#quantity_price').val(parseFloat(parseFloat($('#total').val()) / parseFloat($('#amount').val())).toFixed(2)).toFixed(2);
	
	$('#sub_total').val(parseFloat($('#sub_total').val()).toFixed(2));s
}

</script>