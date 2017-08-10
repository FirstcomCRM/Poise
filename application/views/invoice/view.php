<div class="row">
  <div class="form-panel view-box" id="main-content-area">
    <div class="content-title"><span>View Invoice Detail</span></div>
        <div class="clearfix sp-margin-lg"></div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Invoice No :</label>
            <div class="col-md-7"><?= $invoice['invoice_no']; ?></div>
          </div> 
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Invoice Date :</label>
              <div class="col-md-7"><?= $invoice['invoice_date'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div>  
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Transaction No.:</label>
              <div class="col-md-7"><?= $invoice['case_no'] ?> </div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
		  <div class="form-group">
              <label for="name" class="col-md-5 control-label">Entry No :</label>
              <div class="col-md-7"><?= $invoice['entry_no'] ?> </div>
          </div> 		  
        </div>
        <div class="col-md-6">
          <div class="form-group">
              <label for="name" class="col-md-5 control-label">Deliver To :</label>
              <div class="col-md-7"><?= $invoice['deliver_to'] ?> </div>
          </div> 
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Address :</label>
            <div class="col-md-7"><?= $invoice['address'] ?></div>
          </div>

          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Attention :</label>
            <div class="col-md-7"><?= $invoice['attention'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Customer No. :</label>
            <div class="col-md-7"><?= $invoice['customer_no'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
          <div class="form-group">
            <label for="name" class="col-md-5 control-label">Associate :</label>
            <div class="col-md-7"><?= $invoice['associate'] ?></div>
          </div>
          <div class="clearfix sp-margin-sm"></div> 
        </div>
        <div class="clearfix sp-margin-lg"></div>
        <div class="panel panel-default tab-panel">
          <div class="panel-heading">
              Invoice Detail
          </div>
          <div class="panel-body">
            <div class="row-fluid table-responsive" id="tbl-detail">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" id='detail-table'>
                      <thead>
                        <tr>
                         <th style="width:40%">Description</th>
						  <th style="width:20%">Qty</th>
						  <th style="width:15%">Amount</th>
                        </tr>
                      </thead>
                      <tbody> 
                        <?php if( isset($details) ) { 
                          foreach($details as $key=>$pd) {
                        ?>
                          <tr class="id-<?= $pd['invoice_detail_id'] ?>">
                     
                            <td><?= nl2br(implode('&nbsp;', explode(' ', $pd['description']))) ?></td>
                            <td><?= $pd['qty'] ?></td>
                            <td><?= $pd['amount'] ?></td>
                          </tr>
                        <?php
                          }
                        } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                       
                          <td colspan="2" class="text-right">Sub Total : </td>
                          <td class="text-right">$ <?= number_format($invoice['sub_total'],2); ?></td>
                        </tr>
                        <tr> 
                       
                          <td colspan="2" class="text-right">GST (<?= $invoice['gst'] ?>  % ) : </td>
                          <td class="text-right">$ <?= number_format($invoice['total'] - $invoice['sub_total'],2); ?></td>
                        </tr>
                        <tr>
                      
                          <td colspan="2" class="text-right">Grand Total : </td>
                          <td class="text-right">$ <?= number_format($invoice['total'],2); ?></td>
                        </tr>
                      </tfoot>
                    </table>      
                </div>
            </div>    
          </div>
        </div>            
        <div class="clearfix sp-margin-sm"></div>
  </div>
</div>

