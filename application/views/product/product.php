<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>">Yönetim Paneli</a></li>
  <li><a href="<?php echo site_url('product'); ?>">Stok Yönetimi</a></li>
  <li><a href="<?php echo site_url('product/lists'); ?>">Stok Listesi</a></li>
  <li class="active"><?php echo @$product['name']; ?></li>
</ol>




<?php if(@$product_card_not_found): ?>
    <div class="row">
    	<div class="col-md-3">
    		<img src="<?php echo base_url('theme/img/logo/256x256.png'); ?>" class="img-responsive" />
    	</div> <!-- /.col-md-3 -->
        <div class="col-md-9">
        	<h3 class="line">Stok Kartı Bulunamadı!</h3>
            <ul class="sugar">
            	<li>Aradığın stok kartı bulunamadı.</li>
            	<li>Ürün ID numarası yada stok barkod kodu yanlış olabilir.</li>
            	<li>Ürün ID numarası yada stok barkod kodu yanlış olabilir.</li>
            </ul>
        </div> <!-- /.col-md-9 -->
    </div> <!-- /.row -->
<?php else: ?>


<?php if($product['status'] == '0'): ?>
	<?php alertbox('alert-danger', 'Stok kartı silinmiş.', 'Stok kartı silinmiş. Stok kartını tekrar aktif hale getirebilirsin.', false); ?>
<?php endif; ?>



<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#product_card" data-toggle="tab"><i class="fa fa-folder-o"></i> Ürün Kartı</a></li>
    <li><a href="#invoices" data-toggle="tab"><i class="fa fa-shopping-cart"></i> Giriş-Çıkış</a></li>
    <li><a href="#history" data-toggle="tab"><i class="fa fa-keyboard-o"></i> Log</a></li>
    <li class="dropdown">
		<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-asterisk"></i> <?php lang('Options'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
			<li><a href="<?php echo site_url('user/new_message/?product_id='.$product['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?product_id='.$product['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
            
            <li class="divider"></li>
        	<li><a href="<?php echo site_url('product/print_barcode/'.$product['id']); ?>?print"><span class="glyphicon glyphicon-print mr9"></span>Barkod Yazdır</a></li>
            
            <?php if(item_access('product_edit_button')): ?>
                <li class="divider"></li>
                <?php if($product['status'] == '1'): ?>
                    <li><a href="?status=<?php echo base64_encode('0'); ?>"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Delete'); ?></a></li>
                <?php else: ?>
                    <li><a href="?status=<?php echo base64_encode('1'); ?>"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Activate'); ?></a></li>
                <?php endif; ?>
            <?php endif; ?>
      </ul>
    </li>
</ul>

<div id="myTabContent" class="tab-content">

<!-- product card -->
<div class="tab-pane fade active in" id="product_card">
<div class="row">
<div class="col-md-8">

<?php
if(@$update_product_success) { alertbox('alert-success', 'Stok Kartı Güncellendi.', 
'"'.$product['code'].'" stok kartı bilgileri veritabanında güncellendi.'); }
if(@$formError) { alertbox('alert-danger', $formError);	 }
if(@$haveBarcode) { alertbox('alert-danger', '"'.$haveBarcode.'" Barkod kodu başka bir ürün kartında bulundu.', 
	'Başka bir ürün kartı "'.$haveBarcode.'" barkod kodunu kullanıyor. <br/> Barkod kodları eşsiz olmalı ve sadece bir ürün kartına ait olmalı.');	 }
# resim yukleme mesajlari
if(@$error_image_upload) { alertbox('alert-danger', 'Resim yüklenemedi.', $error_image_upload); }
if(@$success_image_upload) { alertbox('alert-success', 'Resim yüklendi.'); }
if(@$delete_image) { alertbox('alert-warning', 'Resim silindi.'); }
if(@$success_default_image) { alertbox('alert-success', 'Varsayılan görsel değiştirildi.'); }



?>

    <form name="form_new_product" id="form_new_product" action="?" method="POST" class="validation widget">
        <div class="header"><i class="fa fa-puzzle-piece"></i> Stok Kartı</div>
        <div class="content">
            <div class="row">
                <div class="col-md-6">
                       
                    <div class="form-group">
                        <label for="code" class="control-label ff-1 fss-16"><?php lang('Barcode Code'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                            <input type="text" id="code" name="code" class="form-control  ff-1" placeholder="<?php lang('Barcode Code'); ?>" minlength="3" maxlength="32" value="<?php echo $product['code']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label ff-1 fss-16"><?php lang('Product Name'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                            <input type="text" id="name" name="name" class="form-control  ff-1 required" placeholder="<?php lang('Product Name'); ?>" minlength="3" maxlength="100" value="<?php echo $product['name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label ff-1 fss-16"><?php lang('Description'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                            <textarea id="description" name="description" class="form-control  ff-1" placeholder="<?php lang('Description'); ?>" style="height:91px;"><?php echo $product['description']; ?></textarea>
                        </div>
                    </div>
            
                                      
                </div> <!-- /.col-md-4 -->
                <div class="col-md-6">
                	
                    <?php if(item_access('product_cost_price')): ?>
                	<div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_free_cost_price" class="control-label ff-1 fs-12">Maliyet Fiyatı Kdv Hrç.</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try"></span></span>
                                    <input type="text" id="tax_free_cost_price" name="tax_free_cost_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($product['tax_free_cost_price']); ?>" disabled="disabled">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        	<div class="form-group">
                                <label for="cost_price" class="control-label ff-1 fs-12">Maliyet Fiyatı Kdv Dhl.</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try fs-16"></span></span>
                                    <input type="text" id="cost_price" name="cost_price" class="form-control ff-1 number text-right" placeholder="0.00" value="<?php echo get_money($product['cost_price'], array('virgule'=>false)); ?>">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                    </div> <!-- /.row -->
                    <?php endif; ?>
                    
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_free_sale_price" class="control-label ff-1 fs-12">Satış Fiyatı Kdv Hariç</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try"></span></span>
                                    <input type="text" id="tax_free_sale_price" name="tax_free_sale_price" class="form-control number text-right" placeholder="0.00" value="<?php echo get_money($product['tax_free_sale_price']); ?>" disabled="disabled">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        	<div class="form-group has-success">
                                <label for="sale_price" class="control-label ff-1 fs-12">Satış Fiyatı Kdv Dahil</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try fs-16"></span></span>
                                    <input type="text" id="sale_price" name="sale_price" class="form-control ff-1 number text-right fs-16" placeholder="0.00" value="<?php echo get_money($product['sale_price'], array('virgule'=>false)); ?>">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                    </div> <!-- /.row -->
                    
                    
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                <label for="tax" class="control-label ff-1 fs-12">Kdv Tutarı</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try"></span></span>
                                    <input type="text" id="tax" name="tax" class="form-control number text-right" placeholder="0.00" value="<?php echo $product['tax']; ?>" disabled="disabled">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        	<div class="form-group">
                                <label for="tax_rate" class="control-label ff-1 fs-12">Kdv Oranı</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="text" id="tax_rate" name="tax_rate" class="form-control ff-1 number text-right" placeholder="0.00" value="<?php echo $product['tax_rate']; ?>">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                    </div> <!-- /.row -->
                    
                    <?php if(item_access('product_profit_rate')): ?>
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="form-group">
                                <label for="tax" class="control-label ff-1 fs-12">Karlılık Oranı</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="text" id="tax" name="tax" class="form-control number text-right" placeholder="0.00" value="<?php echo @number_format((($product['tax_free_sale_price'] - $product['tax_free_cost_price'])/$product['tax_free_cost_price']) * 100, 0, '', ''); ?>" disabled="disabled">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                        <div class="col-md-6">
                        	<div class="form-group <?php if(($product['tax_free_sale_price'] - $product['tax_free_cost_price']) < 0): ?>has-error<?php endif; ?>">
                                <label for="tax_rate" class="control-label ff-1 fs-12">Kar Tutarı</label>
                                <div class="input-prepend input-group">
                                    <span class="input-group-addon"><span class="fa fa-try"></span></span>
                                    <input type="text" id="tax_rate" name="tax_rate" class="form-control ff-1 number text-right" placeholder="0.00" value="<?php echo get_money($product['tax_free_sale_price'] - $product['tax_free_cost_price']); ?>" disabled="disabled">
                                </div>
                            </div> <!-- /.form-group -->
                        </div> <!-- /.col-md-6 -->
                    </div> <!-- /.row -->
                    <?php endif; ?>
                    
                    

                    <label for="unit">Birim</label>
                    <select name="unit" id="unit" class="form-control">
                        <option value="number" <?php selected($product['unit'], 'number'); ?>>ADET</option>
                        <option value="gram" <?php selected($product['unit'], 'gram'); ?>>GRAM</option>
                        <option value="kilogram" <?php selected($product['unit'], 'kilogram'); ?>>KILOGRAM</option>
                        <option value="ton" <?php selected($product['unit'], 'ton'); ?>>TON</option>
                        <option value="millimeter" <?php selected($product['unit'], 'millimeter'); ?>>MILIMETRE</option>
                        <option value="centimeter" <?php selected($product['unit'], 'centimeter'); ?>>SANTIMETRE</option>
                        <option value="meter" <?php selected($product['unit'], 'meter'); ?>>METRE</option>
                        <option value="parcel" <?php selected($product['unit'], 'parcel'); ?>>KOLI</option>
                    </select>
                 
                </div> <!-- /.col-md- -->
            </div> <!-- /.row -->


        	
            <div class="h20"></div>
            <div class="row">
            	<div class="col-md-9"></div>
                <div class="col-md-3">
					<?php if($product['status'] == 1): ?>
                        <?php if(item_access('product_edit_button')): ?>
                            <input type="hidden" name="log_time" value="<?php echo logTime(); ?>" />
                            <input type="hidden" name="update_product" />
                            <button class="btn btn-default btn-block"><?php lang('Update'); ?> &raquo;</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div> <!-- /.col-md-3 -->
            </div> <!-- /.row -->
        </div> <!-- /.content -->	
	</form>
    
    
    
    <div class="h20"></div>
    <form name="form_gallery" id="form_gallery" action="?" method="POST" enctype="multipart/form-data" class="validation_2 widget">
    	<div class="header"><i class="fa fa-picture-o mr5"></i> Ürün Görseli - Fotoğraf Galerisi</div>
    	<div class="content">
            
            <div class="row">
                <div class="col-md-9">
                    <input type="file" name="image" id="image" class="form-control required" />
                </div> <!-- /.col-md-8 -->
                <div class="col-md-3">
                    <input type="hidden" name="log_time" value="<?php echo logTime(); ?>" />
                    <button class="btn btn-default btn-block">Resim Yükle</button>
                </div> <!-- /.col-md-4 -->
            </div> <!-- /.row -->
            
            <div class="h20"></div>
            
            
            <?php
			// urun gorsellerini buradaki fonksiyon ile cekiyoruz
			$this->db->where('product_id', $product['id']);
			$this->db->where('group', 'gallery');
			$this->db->order_by('id', 'ASC');
			$product_gallery = $this->db->get('product_meta')->result_array();
            if($product_gallery)
            {
				?><div class="row"> <?php
                foreach($product_gallery as $image): ?>
                <div class="col-md-3 product_image_gallery" style="margin-bottom:10px;">
                    <a href="<?php echo base_url('uploads/products/'.$product['id'].'/'.$image['key']); ?>" class="img-thumbnail image_zoom<?php echo $image['id']; ?>" data-lightbox="image-1" <?php if($image['val_text'] == 'default_image'): ?>style="border:1px solid #999;"<?php endif; ?>>
                        <img class="img-responsive product_image" src="<?php echo base_url('uploads/products/'.$product['id'].'/'.$image['key']); ?>" />
                    </a>
                    
                    <div class="image_hover">
                        <a href="javascript:;" onclick="$('.image_zoom<?php echo $image['id']; ?>').click();" class="fs-18 btn btn-success btn-xs" title="Görseli büyüt"><i class="fa fa-picture-o"></i></a>
                        <a href="?default_image=<?php echo $image['id']; ?>" class="fs-18 btn btn-warning btn-xs" title="Varsayılan görsel yap"><i class="fa fa-check-square-o"></i></a>
                        <a href="?delete_product_image=<?php echo $image['id']; ?>" class="fs-18 btn btn-danger btn-xs" title="Görseli sil"><i class="fa fa-trash-o"></i></a>
                    </div> <!-- /.image_hover -->
                </div> <!-- /.col-md-3 -->
                <?php endforeach; ?>
                </div> <!-- /.row -->
                <style>
				.product_image_gallery .image_hover {
					display:none;
					position:absolute;
					margin-top:-46px;
					margin-left:10px;
				}
				.product_image_gallery:hover > a {
					background-color:#333;
				}
				.product_image_gallery:hover > a .product_image {
					opacity:0.3;
				}
				.product_image_gallery:hover > .image_hover {
					display:block;
				}
				</style>
                 <?php
            }
            else
            {
                ?>
                <img src="<?php echo base_url('theme/img/logo/product_no_image.png'); ?>" class="img-responsive" />
                <h3 style="position:absolute; margin-top:-76px; margin-left:100px; font-weight:normal;" class="text-muted">ürün görseli bulunamadı! <br /><small>yukarıdaki menüden ürün görseli ekleyebilirsin.</small></h3>
                <?php
            }
            ?>
            
        </div> <!-- /.content -->
    </form> <!-- /#form_gallery .widget-->

	

</div> <!-- /.col-md-8 -->
<div class="col-md-4">    
    
	<div class="widget">
    	<div class="header dark_gray"><i class="fa fa-puzzle-piece"></i> Stok Kartına Genel Bakış</div>
        <div class="content">


            <?php if($product['amount'] < 0): ?>
                <div class="row stat">
                    <div class="col-md-2">
                        <i class="fa fa-sort-numeric-desc text-danger"></i>
                    </div>
                    <div class="col-md-10">
                        <div class="value"><span class="<?php if($product['amount'] < 0): ?>text-danger<?php else: ?>text-success<?php endif; ?>"><?php echo get_amount($product['amount']); ?></span></div>
                        <div class="title"><?php unit_name($product['unit']); ?>  stok lazım</div>
                        <small class="text-danger">stok miktarı (-)eksi değere düşmüş</small>
                    </div>
                </div> <!-- /.row stat -->

                <?php if(item_access('product_cost_price')): ?>
                    <div class="row stat">
                        <div class="col-md-2">
                            <i class="fa fa-try text-warning"></i>
                        </div>
                        <div class="col-md-10">
                            <div class="value"><span class="text-warning"><?php echo get_money($product['amount'] * $product['cost_price']); ?> <i class="fa fa-try fs-16"></i></span></div>
                            <div class="title">satın alma değeri</div>
                            <small class="text-warning">alış değerinde stok alman gerekiyor</small>
                        </div>
                    </div> <!-- /.row stat -->
                <?php endif; ?>

            <?php else: ?>
                <div class="row stat">
                    <div class="col-md-2">
                        <i class="fa fa-sort-numeric-asc text-success"></i>
                    </div>
                    <div class="col-md-10">
                        <div class="value"><span class="<?php if($product['amount'] < 0): ?>text-danger<?php else: ?>text-success<?php endif; ?>"><?php echo get_amount($product['amount']); ?></span></div>
                        <div class="title"><?php unit_name($product['unit']); ?>  stok var</div>
                        <small class="text-muted">stok bulunmakta</small>
                    </div>
                </div> <!-- /.row stat -->
                <div class="row stat">
                    <div class="col-md-2">
                        <i class="fa fa-try "></i>
                    </div>
                    <div class="col-md-10">
                        <div class="value"><?php echo get_money($product['amount'] * $product['sale_price']); ?> <i class="fa fa-try fs-16"></i></div>
                        <div class="title">stok değeri</div>
                        <small class="text-muted">satış değerinde stok bulunmakta</small>
                    </div>
                </div> <!-- /.row stat -->
            <?php endif; ?>

            <!-- bu urunden bu gune kadar kac tane satildi -->
            <div class="row stat">
                <div class="col-md-2">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="col-md-10">
                    <?php
                        $this->db->where('status', 1);
                        $this->db->where('product_id', $product['id']);
                        $this->db->where('in_out', '1');
                        $this->db->select_sum('quantity');
                        $sale_quantity = $this->db->get('fiche_items')->row_array();
                        if($sale_quantity['quantity'] < 1){$sale_quantity['quantity'] = 0;}
                    ?>
                    <div class="value"><?php echo get_amount($sale_quantity['quantity']); ?></div>
                    <div class="title"><?php unit_name($product['unit']); ?> satıldı</div>
                    <small class="text-muted">bu güne kadar satılan ürün sayısı</small>
                </div>
            </div> <!-- /.row stat -->

            <!-- bu urunden bu gune kadar kac tane satildi -->
            <?php if(item_access('product_profit_rate')): ?>
                <div class="row stat">
                    <div class="col-md-2">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="col-md-10">
                        <div class="value"><?php echo get_money($product['profit']); ?></div>
                        <div class="title">net kazanç</div>
                        <small class="text-muted">bu ürün satışından bu güne kadar kazanılan para</small>
                    </div>
                </div> <!-- /.row stat -->
            <?php endif; ?>
            
        </div> <!-- /.content -->
    </div> <!-- /.widget -->
    <div class="h20"></div>



    <div class="widget">
        <div class="header"><i class="fa fa-picture-o mr5"></i> Ürün Görseli</div>
        <div class="content">
            <?php $default_image = get_product_meta(array('product_id'=>$product['id'], 'group'=>'gallery', 'val_text'=>'default_image')); ?>
            <?php if($default_image): ?>
                <img src="<?php echo base_url('uploads/products/'.$product['id'].'/'.$default_image['key']); ?>" class="img-responsive" />
            <?php else: ?>
                <img src="<?php echo base_url('theme/img/logo/product_no_image.png'); ?>" class="img-responsive" />
                <h3 style="position:absolute; margin-top:-76px; margin-left:100px; font-weight:normal; width:150px;" class="text-muted">ürün görseli bulunamadı!</h3>
            <?php endif; ?>
            
        </div> <!-- /.content -->
    </div> <!-- /.widget -->
    <div class="h20"></div>
    
	<div class="widget">
    	<div class="header dark_gray"><span class="glyphicon glyphicon-barcode mr5"></span> Barkod</div>
        <div class="content padding-4">
            <a href="<?php echo site_url('product/print_barcode/'.$product['id']); ?>?print" class="img-thumbnail text-center" style="width:100%;">
                <div style="height:4px;"></div>
                <img src="<?php echo get_barcode($product['code']); ?>" class="img-responsive" />
            </a>
        </div> <!-- /.content -->
   </div> <!-- /.widget -->
    
    
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


</div><!-- /#product card -->












<!-- invoice
	stok kartına ait giriş çıkış fişleri buradan listelenmekte
-->
<div class="tab-pane fade in" id="invoices">
<?php $accounts = get_account_list_for_array(); ?>

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
        	<th><?php lang('ID'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Input'); ?>/<?php lang('Output'); ?></th>
            <th><?php lang('Account Card'); ?></th>
            <th><?php lang('Quantity'); ?></th>
            <th><?php lang('Quantity Price'); ?></th>
            <th><?php lang('Total'); ?></th>
            <th><?php lang('Tax Rate'); ?></th>
            <th><?php lang('Tax'); ?></th>
            <th><?php lang('Sub Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
	$this->db->where('status', 1);
	$this->db->where('product_id', $product['id']);
	$this->db->order_by('ID', 'ASC');
	$invoices = $this->db->get('fiche_items')->result_array();
	?>
    
    <?php foreach($invoices as $item): ?>
    	<tr class="<?php if($item['in_out'] == 0){echo'warning';}else{echo'success';} ;?>">
        	<td class="hide"></td>
        	<td><a href="<?php echo site_url('invoice/view/'.$item['invoice_id']); ?>">#<?php echo $item['invoice_id']; ?></a></td>
            <td><?php echo substr($item['date'],0,16); ?></td>
            <td><?php echo strtoupper(replace_TR(get_text_in_out($item['in_out']))); ?></td>
            <td><a href="<?php echo site_url('account/get_account/'.$item['account_id']); ?>" target="_blank"><?php echo $accounts[$item['account_id']]['name']; ?></a></td>
            <td class="text-center"><?php echo $item['quantity']; ?></td>
            <td class="text-right"><?php echo get_money($item['tax_free_sale_price']); ?></td>
            <td class="text-right"><?php echo get_money($item['total']); ?></td>
            <td class="text-right">% (<?php echo $item['tax_rate']; ?>)</td>
            <td class="text-right"><?php echo get_money($item['tax']); ?></td>
            <td class="text-right"><?php echo get_money($item['sub_total']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> <!-- /.table -->
</div> <!-- /#invoices -->






<!-- history -->
<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('product_id'=>$product['id']), 'DESC'); ?>
</div> <!-- /#history -->












</div> <!-- /#myTabContent -->

<?php endif; ?>