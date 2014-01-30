<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function index()
	{
		$this->template->view('invoice/dashboard_view');
	}
	
	public function new_invoice()
	{
		redirect(site_url('invoice/add'));
	}
	public function add()
	{
		$invoice['account_id'] = '';
		$account['name'] = '';
		$invoice['description'] = '';
		
		if(isset($_POST['add']) and is_log())
		{
			$continue = true;
			$this->form_validation->set_rules('account_id', get_lang('Account Card'), 'required|digits');
		
			if($this->form_validation->run() == FALSE)
			{
				$data['formError'] =  validation_errors();
			}
			else
			{
				$invoice['date'] = $this->input->post('date').' '.date('H:i:s');;
				$invoice['account_id'] = $this->input->post('account_id');
				$invoice['description'] = $this->input->post('description');
				
				$invoice['type'] = 'invoice';
				if(isset($_GET['sell'])){$invoice['in_out'] = '1';} else if(isset($_GET['buy'])){$invoice['in_out'] = '0';} 
					
				$invoice_id = add_invoice($invoice);
				
				if($invoice_id > 0)
				{
					$data['type'] = 'invoice';
					$data['invoice_id'] = $invoice_id;
					$data['account_id'] = $invoice['account_id'];
					$data['title'] = 'Yeni Fiş';
					if(isset($_GET['sell']))
					{ $data['description'] = 'Yeni satış fişi oluşturdu.'; }
					else { $data['description'] = 'Yeni alış fişi oluşturdu.'; } 
					add_log($data);
					
					redirect(site_url('invoice/view/'.$invoice_id));
				}
				else { $data['error'] = 'Bilinmeyen Bir Hata!'; }
			}
		}
		
		/* eger hesap kartından deger gonderilmis ise */
		if(isset($_GET['account_id']))
		{
			$account = get_account($_GET['account_id']);
			$invoice['account_id'] = $account['id'];
		}
		
		$data['account'] = $account;
		$data['invoice'] = $invoice;
		$this->template->view('invoice/new_invoice', $data);
	}
	
	
	
	public function view($invoice_id)
	{
		$data['invoice_id'] = $invoice_id;
		$invoice = get_invoice($invoice_id);
		
		if(isset($_POST['item']) and is_log())
		{
			$continue = true;
			$this->form_validation->set_rules('code', get_lang('Barcode Code'), 'required');
			$this->form_validation->set_rules('amount', get_lang('Amount'), 'required|digits');
			$this->form_validation->set_rules('quantity_price', get_lang('Quantity Price'), 'number');
			$this->form_validation->set_rules('tax_rate', get_lang('Tax Rate'), 'digits');
			
			if($this->form_validation->run() == FALSE)
			{
				$data['formError'] = validation_errors();
			}
			else
			{
				$item['invoice_id'] = $invoice['id'];
				
				$product['code'] 	= $this->input->post('code');
				$item['quantity'] = $this->input->post('amount');
				$item['quantity_price'] = $this->input->post('quantity_price');
				$item['total'] = '';
				$item['tax_rate'] 	= $this->input->post('tax_rate');
				
				$product = get_product(array('status'=>'1', 'code'=>$product['code']));
				
				if(!$product) { $data['barcode_code_unknown'] = 'Stok kartı bulunamadı.'; }
				else
				{
					$item['product_id'] = $product['id'];
					if($item['quantity'] < 0)				{ $item['quantity'] = 1; }
					if($item['quantity_price'] == '')	{ $item['quantity_price'] = $product['tax_free_sale_price']; }
					if($item['tax_rate'] == '')			{ $item['tax_rate'] = $product['tax_rate']; }
					
					$item['total'] 		= $item['quantity'] * $item['quantity_price'];
					$item['tax']		= ($item['total'] / 100) * $item['tax_rate'];
					$item['sub_total'] 	= ($item['total'] + $item['tax']);
					$item['in_out'] 	= $invoice['in_out'];
					
					$item['tax_free_cost_price'] = $product['tax_free_cost_price'];
					$item['cost_price'] = $product['cost_price'];
					$item['tax_free_sale_price'] = $item['quantity_price'];
					$item['sale_price'] = $item['sub_total'] / $item['quantity'];
					$item['profit'] = ($item['tax_free_sale_price'] - $item['tax_free_cost_price']) * $item['quantity'];
					
					$item['product_code'] = $product['code'];
					$item['product_name'] = $product['name'];
					unset($item['quantity_price']);
					
					$item_id = add_item($item);
					if($item_id > 0)
					{
						$data['type'] = 'invoice';
						$data['invoice_id'] = $invoice['id'];
						$data['product_id'] = $product['id'];
						$data['account_id'] = $invoice['account_id'];
						if($invoice['in_out'] == 0){$data['title'] = 'Alış';}else{$data['title'] = 'Satış';}
						if($invoice['in_out'] == 0){$data['description'] = 'Ürün alışı.';}else{$data['description'] = 'Ürün satışı.';}
						add_log($data);
						calc_invoice_items($invoice['id']);	
						calc_product($product['id']);
						$data['success']['add_item'] = get_alertbox('alert-success', '"'.$product['code'].'" stok hareketi eklendi.');
					}
				}
			}
		}
		
		
		
		/* 	STOK HAREKETI SILMEK
			silinmesi gereken stok hareketleri
		*/
		if(isset($_GET['delete_item']))
		{
			$item = get_invoice_item($_GET['item_id']);
			if($item['product_id'] > 0){ $product = get_product($item['product_id']);} else {$product['id'] = '0';}
			
			$this->db->where('id', $_GET['item_id']);
			$this->db->update('fiche_items', array('status'=>'0'));
			if($this->db->affected_rows() > 0)
			{
				$data['type']='item'; $data['invoice_id']=$invoice['id'];$data['product_id']=$product['id'];$data['account_id']=$invoice['account_id'];
				$data['title'] 		= 'Silme';
				$data['description']	= 'Stok hareketi silindi.';
				add_log($data);
				
				$data['success']['delete_item'] = get_alertbox('alert-danger', '"'.$product['code'].'" stok hareketi silindi.');
			}
		}
		
		
		calc_account_balance($invoice['account_id']);
		calc_invoice_items($invoice['id']);
		$this->template->view('invoice/invoice_view', $data);
	}
	
	public function invoice_list()
	{
		$this->template->view('invoice/invoice_list_view');
	}
	
	public function invoice_design()
	{
		$this->template->view('invoice/invoice_design_view');
	}
	
	public function invoice_print($invoice_id)
	{
		$data['invoice_id'] = $invoice_id;
		$this->template->blank_view('invoice/invoice_print_view', $data);
	}
	
	public function search_account($text='')
	{
		$this->db->where('status', '1');
		$this->db->like('code', urldecode($text));
		$this->db->or_like('name', urldecode($text));
		$this->db->or_like('gsm', urldecode($text));
		$this->db->limit(7);
		$query = $this->db->get('accounts')->result_array();
		$data['accounts'] = $query;
		$this->load->view('invoice/typehead_search_account', $data);
	}
	
	public function search_product_for_invoice($text='')
	{
		$this->db->where('status', '1');
		$this->db->like('code', urldecode($text));
		$this->db->or_like('name', urldecode($text));
		$this->db->limit(7);
		$query = $this->db->get('products')->result_array();
		
		$query = $this->db->query('SELECT * FROM til_products WHERE status="1" AND code LIKE "%'.urldecode($text).'%" AND status="1" AND name LIKE "%'.urldecode($text).'%"');
		$data['products'] = $query->result_array();
		$this->load->view('invoice/typehead_search_product', $data);
	}
	
	
}
