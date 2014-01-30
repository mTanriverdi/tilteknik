<?php $users = get_user_array(); ?>

<ol class="breadcrumb">
  <li class="active"><?php lang('Dashboard'); ?></li>
</ol>



<div class="row">
	<div class="col-md-8">
    

        
        

        <div class="row">
            <div class="col-md-3">
                <a href="<?php echo site_url('product'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-inbox fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">stok</div>
                            <div class="desc">stok yönetimi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
           </div> <!-- /.col-md-3 -->
           
            <div class="col-md-3">
                <a href="<?php echo site_url('account'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-stop fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">hesap</div>
                            <div class="desc">hesap yönetimi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-3 -->  
           
            <div class="col-md-3">   
                <a href="<?php echo site_url('invoice'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-shopping-cart fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">alış-veriş</div>
                            <div class="desc">ürün alışı ve satışı</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->  
                </a>
            </div> <!-- /.col-md-6 -->
            
            <div class="col-md-3"> 
                <a href="<?php echo site_url('payment'); ?>" class="link-dashboard-stat">
                    <div class="dashboard-stat blue none">
                        <div class="visual">
                            <i class="icon-comments glyphicon glyphicon-euro fs-20"></i>
                        </div>
                        <div class="details">
                            <div class="number">kasa</div>
                            <div class="desc">ödeme hareketleri ve çek takibi</div>
                         </div>
                    </div> <!-- /.dashboard-stat -->
                </a>
             </div> <!-- /.col-md-3 -->
             
             
             <div class="col-md-3">   
                <a href="<?php echo site_url('plugins'); ?>" class="link-dashboard-stat">
                <div class="dashboard-stat metro_red none">
                    <div class="visual">
                        <i class="icon-comments glyphicon glyphicon-tags fs-20"></i>
                    </div>
                    <div class="details">
                        <div class="number">eklenti</div>
                        <div class="desc">eklenti listesi ve yönetimi</div>
                     </div>
                </div> <!-- /.dashboard-stat -->
                </a>
            </div> <!-- /.col-md-3 -->
        </div> <!-- /.row -->
       
        
   
 	
	<div class="h20"></div>
   
        
    </div> <!-- /.col-md-8 -->
    <div class="col-md-4">
    
    	<div class="widget">
        	<div class="header">Not Defteri</div>
            <div class="content" style="padding:0px;">
                <textarea class="dashboard-note ff-1" id="dashboard-note" style="margin:0px;"><?php $dashboard_note = get_option(array('option_group'=>'dashboard', 'option_key'=>'dashboard-note')); echo $dashboard_note['option_value']; ?></textarea>
                <div id="hidden-note-value"></div>
                <script>
                $('#note-save').click(function() {
                    $( "#hidden-note-value" ).load('<?php echo site_url('general/save_note'); ?>/' + encodeURI($('#dashboard-note').val()));
                    $('.dashboard-note').css('backgroundColor', '#dff0d8');
                    setTimeout(function(){$('.dashboard-note').css('backgroundColor', '#F6F6F6');},3000);
                });
                </script>
                
            </div> <!-- /.content -->
        </div> <!-- /.widget -->
        
        <div class="h20"></div>
        
        
        <div class="widget">
        	<div class="header gray1">Gelen Kutusu</div>
            <div class="content no_padding">
				<?php
                $this->db->where('status', 1);
                $this->db->where_in('type', array('message','reply_message'));
                $this->db->where('inbox_view', '1');
                $this->db->where('receiver_id', get_the_current_user('id'));		
                $this->db->order_by('date DESC, recent_activity DESC');
                $this->db->limit(10);
                $query = $this->db->get('user_mess')->result_array();
                ?>
                <?php if($query) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th><?php lang('Sender'); ?></th>
                            <th><?php lang('Title'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($query as $q): ?>
                       <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                            <td><?php echo $users[$q['sender_id']]['surname']; ?></td>
                            <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo mb_substr($q['title'],0,30,'utf-8'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <?php alertbox('alert-info', get_lang('Your inbox is empty.'), '', false); ?>
                <?php endif; ?>
 			</div> <!-- /.content -->
		</div> <!-- /.widget -->
        
        <div class="h20"></div>
        
        <div class="widget">
        	<div class="header gray1">Görev Yöneticisi</div>
            <div class="content no_padding">
				<?php
                $this->db->where('status', 1);
                $this->db->where_in('type', array('task','reply_task'));
                $this->db->where('inbox_view', '1');
                $this->db->where('receiver_id', get_the_current_user('id'));		
                $this->db->order_by('recent_activity', 'DESC');
                $this->db->limit(10);
                $query = $this->db->get('user_mess')->result_array();
                ?>
                <?php if($query) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th><?php lang('Sender'); ?></th>
                            <th><?php lang('Title'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($query as $q): ?>
                       <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                            <td><?php echo $users[$q['sender_id']]['surname']; ?></td>
                            <td><a href="<?php echo site_url('user/task/'.$q['id']); ?>"><?php echo $q['title']; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <?php alertbox('alert-info', get_lang('No task.'), '', false); ?>
                <?php endif; ?>
        	</div> <!-- /.content -->
    	</div> <!-- /.widget -->
    </div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

