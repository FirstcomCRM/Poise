

</div>
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><a href="<?=base_url()?>dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
          <li><a href="<?=base_url()?>invoice">Invoice</a></li>
          <li class="active">View Invoice</li>
            <div class="pull-right">
                <div class="btn-group">
                    <a class="btn btn-default btn-xs" href="<?=base_url()?>invoice"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
                </div>
            </div>
        </ol>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <?php if(isset($msg) && $msg != '') { ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 text-right">
        <a class="btn btn-default btn-sm" href="<?=base_url()?>quotation_maid/print_preview/<?=$quotation['quotation_id']?>" target="_blank"><i class="fa fa-print"></i> Print</a>
       
            <a class="btn btn-default btn-sm" href="<?=base_url()?>quotation_maid/edit/<?=$quotation['quotation_id']?>"><i class="fa fa-pencil-square-o"></i> Edit</a>
      
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Invoice Details
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Customer</label></div>
                                <div class="col-lg-6">
                                    <a href="#" data-toggle="modal"data-target="#cusomter-info" value="<?=$quotation['customer_id']?>"><?=$quotation['customer_name']?></a>
                                </div>
                                <div class="modal fade" id="cusomter-info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Customer Info</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Customer Name:</div>
                                                    <div class="col-xs-9"><?=$customer_info['customer_name']?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Telephone :</div>
                                                    <div class="col-xs-9" ><?=$customer_info['customer_tel']?></div>
                                                </div>
                                          
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Email:</div>
                                                    <div class="col-xs-9"><?=$customer_info['customer_email']?></div>
                                                </div>
                                    
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Fax:</div>
                                                    <div class="col-xs-9"><?=$customer_info['customer_fax']?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Address:</div>
                                                    <div class="col-xs-9"><?=nl2br($customer_info['customer_address'])?></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-3 text-right">Postal Code:</div>
                                                    <div class="col-xs-9"><?=$customer_info['customer_postal']?></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Quotation No.</label></div>
                                <div class="col-lg-6"><?=$quotation['quotation_no']?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Date</label></div>
                                <div class="col-lg-6"><?=date('d M Y',$quotation['quotation_date'])?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Status</label></div>
                                <div class="col-lg-6 <?=$quotation['is_close']==1?'text-success':'text-danger'?>"><?=$quotation['is_close']==1?'Closed':'Pending'?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Payment Terms</label></div>
                                <div class="col-lg-6"><?=$quotation['payment_terms']?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Issued By</label></div>
                                <div class="col-lg-6"><?=$quotation['issued_by']?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Sale Person</label></div>
                                <div class="col-lg-6"><?=$quotation['s_name']?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label>Remark</label></div>
                                <div class="col-lg-6"><?=nl2br($quotation['remark'])?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                    <div class="col-lg-4"><label>Internal Remark <br>(Not showing on printing page)</label></div>
                                <div class="col-lg-6"><?=nl2br($quotation['internal_remark'])?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                            <div class="clearfix margin-sm-b"></div>
                            <div class="col-sm-12 order-header">Order Product Information</div>
                            <div class="clearfix margin-sm-b"></div>
                            <div class="col-sm-12">
                                <div class="row-fluid grid-area product-grid"> 
                                    <div class="row tbl-head">
                                      
                                    </div>
                                    <?php 
                                        if(isset($quotation_products) && count($quotation_products) != 0) { 
                                            $count = 0; 
                                            foreach ($quotation_products as $quotation_product) : 
                                              $row =  ($count%2)==1 ? 'row odd': 'row even';
                                              $classname = $row . ' id-'.$quotation_product['invoice_package_id']; 
                                    ?>
                                            <div class="<?= $classname; ?>"> 
                                            <?php if ($quotation_product['maid_name'] != "")
                                            { ?>
                                            <div class='col-md-2'><b>FDW:</b></div>
                                             <div class='col-md-10'><?= $quotation_product['maid_name']; ?></div>
                         
                        
                                              <div class='col-md-2'><b>Package:</b></div>
                                              <div class='col-md-4'><?= $quotation_product['package_name']; ?></div>
                                              <div class='col-md-2'><b>Package Price:</b></div>
                                              <div class='col-md-4 col-total'><?= $quotation_product['total_amount']; ?></div>
                                 
                    
                                              <div class='col-md-2'><b>Insurance:</b></div>
                                              <div class='col-md-4'><?= $quotation_product['insurance_name']; ?></div>
                                              <div class='col-md-2'><b>Insurance Price:</b></div>
                                              <div class='col-md-4 col-total-i'><?= $quotation_product['insurance_fee']; ?></div>
                                  
                                              <div class='col-md-2'><b>Addhoc Item</b></div>
                                              <div class='col-md-10'><?= $quotation_product['addhoc']; ?></div>
                              
                                              <div class='col-md-2'><b>Addhoc Quantity</b></div>
                                              <div class='col-md-10'><?= $quotation_product['addhoc_qty']; ?></div>
                                   
                                              <div class='col-md-2'><b>Item Price:</b></div>
                                              <div class='col-md-4'><?= $quotation_product['addhoc_price']; ?></div>
                                              <div class='col-md-2'><b>Addhoc Price:</b></div>
                                              <div class='col-md-4 col-total-a'><?= $quotation_product['total_addhoc_price']; ?></div>
                                
                                              <div class='col-md-2'><b>Remark:</b></div>
                                              <div class='col-md-4'><?= $quotation_product['remark']; ?></div>
                                              <div class='col-md-2 '><b>Discount:</b></div>
                                              <div class='col-md-4 col-total-disc'><?= $quotation_product['discount']; ?></div> 
                                            

                                                 <?php }else if($quotation_product['maid_name'] == '')
                                              {?>
                                             <div class='col-md-2'><b>Addhoc Quantity</b></div>
                                              <div class='col-md-10'><?= $quotation_product['addhoc_qty']; ?></div>
                                             <div class='col-md-2'><b>Item Price:</b></div>
                                              <div class='col-md-4'><?= $quotation_product['addhoc_price']; ?></div>
                                              <div class='col-md-2'><b>Addhoc Price:</b></div>
                                              <div class='col-md-4 col-total-a'><?= $quotation_product['total_addhoc_price']; ?></div>
                                           
                                              <?php } ?>
                                              </div>



                                            <br>
                                            <br>
                                    <?php 
                                            $count++;
                                            endforeach;
                                        }
                                    ?>
                                    <br>
                                    </br>
                                    <div class="product-add-area"></div>
                                    <div class="clearfix margin-sm-b"></div>
                                    <div class="row">
                                    <div class="clearfix margin-sm-b"></div>
                                    <div class="row">
                                        <div class="col-md-9 text-right">Total Amount :</div>
                                        <div class="col-md-2">$<?=$quotation['total_amount']?></div> 
                                        <div class="clearfix margin-sm-b"></div> 
                                        <div class="col-md-9 text-right">GST :</div>
                                        <div class="col-md-2"><?=$quotation['gst']?>%</div> 
                                        <div class="clearfix margin-sm-b"></div> 
                                        <div class="col-md-9 text-right">Total Inc. GST :</div>
                                        <div class="col-md-2">$<?=$quotation['total_inc_gst']?></div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

<br>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Payment Details
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8">
                        <table class="table table-hover" id="payment-records">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($payments!=''): ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr>
                                            <td><?=date('d M Y', $payment['payment_date'])?></td>
                                            <td>$<?=number_format($payment['amount'],2)?></td>
                                            <td><?=$payment['payment_type']?></td>
                                            <td><?=nl2br($payment['remark'])?></td>
                                            <td>
                                                <a class="btn btn-xs"  title="Delete" href="<?=base_url()?>invoice/delete_payment/<?=$quotation['quotation_id']?>/<?=$payment['invoice_payment_id']?>" onclick="return confirm_delete()" ><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-xs-12">Total Paid: $<?=number_format($quotation['total_paid'], 2)?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">Balance of Payable: $<?=round($quotation['total_paid'],2)<round($quotation['total_inc_gst'],2)?number_format($quotation['total_inc_gst']-$quotation['total_paid'],2):'0.00'?></div>
                        </div>
                        <br>
                        <form method="post">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Add Payment
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php if(validation_errors()) { ?>
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger">
                                            <strong><?= validation_errors();?></strong>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <input type="hidden" name="quotation_id" value="<?=$quotation['quotation_id']?>">
                                                <div class="col-lg-4"><label>Date</label></div>
                                                <div class="col-lg-8"><input class="form-control input-sm" id="payment_date" name="payment_date" value="<?=isset($_POST['payment_date'])?$_POST['payment_date']:date('d-m-Y')?>"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4"><label>Amount</label></div>
                                                <div class="col-lg-8"><input class="form-control input-sm" name="amount" value="<?=isset($_POST['amount'])?$_POST['amount']:''?>"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4"><label>Payment Type</label></div>
                                                <div class="col-lg-8">
                                                    <select class="form-control input-sm" name="payment_type">
                                                        <option value="">- Please Select Payment Type -</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Cheque">Cheque</option>
                                                        <option value="Other">Credit Card</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4"><label>Remark</label></div>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control input-sm" name="remark"><?=isset($_POST['remark'])?$_POST['remark']:''?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4"></div>
                                                <div class="col-lg-8">
                                                    <button type="submit" onclick="return confirm_payment()" class="btn btn-primary btn-sm">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<script>
var base_url = "<?=base_url()?>";
var quotation_id = "<?=$quotation_id?>";
</script>

