<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('invoice'); ?>"><?php lang('Buying-Selling'); ?></a></li>
	<li class="active"><?php lang('Invoice List'); ?></li>
</ol>


<?php $accounts = get_account_list_for_array(); ?>

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
        	<th><?php lang('ID'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Type'); ?></th>
            <th><?php lang('Input'); ?>/<?php lang('Output'); ?></th>
            <th><?php lang('Account Card'); ?></th>
            <th><?php lang('Products'); ?></th>
            <th><?php lang('Grand Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
	$this->db->where('status', 1);
	$this->db->where('type', 'invoice');
	$this->db->where('quantity >', '0');
	$this->db->order_by('ID', 'DESC');
	$invoices = $this->db->get('fiches')->result_array();
	?>
    
    <?php foreach($invoices as $invoice): ?>
    	<tr>
        	<td class="hide"></td>
        	<td><a href="<?php echo site_url('invoice/view/'.$invoice['id']); ?>">#<?php echo $invoice['id']; ?></a></td>
            <td><?php echo substr($invoice['date'],0,16); ?></td>
            <td><?php echo $invoice['type']; ?></td>
            <td><?php echo get_text_in_out($invoice['in_out']); ?></td>
            <td><a href="<?php echo site_url('account/get_account/'.$invoice['account_id']); ?>" target="_blank"><?php echo $accounts[$invoice['account_id']]['name']; ?></a></td>
            <td class="text-center"><?php echo $invoice['quantity']; ?></td>
            <td class="text-right"><?php echo get_money($invoice['grand_total']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> <!-- /.table -->
