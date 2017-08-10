$(function(){
  var inv_tbl = $('#invoice-table').dataTable({
    "processing": true,
    "serverSide": true,
    'iDisplayLength': 20,
    "order": [[ 1, "asc" ]], // Default Sroting by Desc
    "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
    "ajax": {
        "url": burl + "summary/aj_dtInvoice/" + $('#project-id').val(),
        "type": "POST",
        "data" : { }
    },
    "columns": [
        { "data": "no", "orderable": false, "bSearchable": false },
        { "data": "invoice_no"},
        { "data": "total_amount"},
        { "data": "remark" },
        { "data": "action", "orderable": false, "bSearchable": false }
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        // For auto numbering at 'No' column
        var start = inv_tbl.api().page.info().start;
        $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
    },
    "footerCallback": function ( row, data, start, end, display ) {
        getInvoiceTotal();
    }
  });

  var po_tbl = $('#po-table').dataTable({
    "processing": true,
    "serverSide": true,
    'iDisplayLength': 20,
    "order": [[ 1, "asc" ]], // Default Sroting by Desc
    "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
    "ajax": {
        "url": burl + "summary/aj_dtPO/" + $('#project-id').val(),
        "type": "POST",
        "data" : { }
    },
    "columns": [
        { "data": "no", "orderable": false, "bSearchable": false },
        { "data": "po_no"},
        { "data": "vendor"},
        { "data": "total_amount"},
        { "data": "remark" },
        { "data": "action", "orderable": false, "bSearchable": false }
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        // For auto numbering at 'No' column
        var start = po_tbl.api().page.info().start;
        $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
    },
    "footerCallback": function ( row, data, start, end, display ) {
        getPoTotal();
    }
  });

  var do_tbl = $('#do-table').dataTable({
    "processing": true,
    "serverSide": true,
    'iDisplayLength': 20,
    "order": [[ 1, "asc" ]], // Default Sroting by Desc
    "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
    "ajax": {
        "url": burl + "summary/aj_dtDO/" + $('#project-id').val(),
        "type": "POST",
        "data" : { }
    },
    "columns": [
        { "data": "no", "orderable": false, "bSearchable": false },
        { "data": "do_no"},
        { "data": "stock"},
        { "data": "total_qty"},
        { "data": "price" },
        { "data": "total_amount" },
        { "data": "remark" },
        { "data": "action", "orderable": false, "bSearchable": false }
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        // For auto numbering at 'No' column
        var start = do_tbl.api().page.info().start;
        $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
    },
    "footerCallback": function ( row, data, start, end, display ) {
        getDoTotal();
    }
  });

  var cost_tbl = $('#cost-table').dataTable({
    "processing": true,
    "serverSide": true,
    'iDisplayLength': 20,
    "order": [[ 1, "asc" ]], // Default Sroting by Desc
    "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
    "ajax": {
        "url": burl + "summary/aj_dtCost/" + $('#project-id').val(),
        "type": "POST",
        "data" : { }
    },
    "columns": [
        { "data": "no", "orderable": false, "bSearchable": false },
        { "data": "description"},
        { "data": "qty"},
        { "data": "uom"},
        { "data": "price" },
        { "data": "total_amount"},
        { "data": "action", "orderable": false, "bSearchable": false }
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        // For auto numbering at 'No' column
        var start = cost_tbl.api().page.info().start;
        $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
    },
    "footerCallback": function ( row, data, start, end, display ) {
        getCostTotal();
    }
  });

  var misc_tbl = $('#misc-table').dataTable({
    "processing": true,
    "serverSide": true,
    'iDisplayLength': 20,
    "order": [[ 1, "asc" ]], // Default Sroting by Desc
    "lengthMenu": [[20, 50, 100, 200, -1], [20, 50, 100, 200, "All"]],
    "ajax": {
        "url": burl + "summary/aj_dtMisc/" + $('#project-id').val(),
        "type": "POST",
        "data" : { }
    },
    "columns": [
        { "data": "no", "orderable": false, "bSearchable": false },
        { "data": "description"},
        { "data": "qty"},
        { "data": "price" },
        { "data": "total_amount"},
        { "data": "action", "orderable": false, "bSearchable": false }
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        // For auto numbering at 'No' column
        var start = misc_tbl.api().page.info().start;
        $('td:eq(0)',nRow).html(start + iDisplayIndex + 1);
    },
    "footerCallback": function ( row, data, start, end, display ) {
        getMiscTotal();
    }
  });

  //INVOICE CRUD//
  $('#btn-add-invoice').click(function(e) {
     e.preventDefault();
    $('#inv-submiturl').val('summary/addInvoice');
    $('.quick-action').html('Add');
    $('#invoiceModal').modal('show');
  });

  $('#invoice-table').on('click', '.inv-edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'summary/aj_getInvoice/' + id,
        data :  {},
        success: function(data){ 
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#inv-edit-id').val(result['pj_invoice_id']);   
            $('#inv-invoice-no').val(result['invoice_no']);
            $('#inv-total-amount').val(result['total_amount']);   
            $('#inv-remark').val(result['remark']);   
            var updateurl = 'summary/editInvoice/' + id;
            $('#inv-submiturl').val(updateurl);
            $('.quick-action').html('Edit');
            $("#invoiceModal").modal('show');
          }
          else {
             alert(result['msg']);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
  });

  $('#invoice-table').on('click', '.inv-delete-link', function(e) {
    e.preventDefault();
    var url =  $(this).attr("href");
    var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
    //$('#hid-delete-id').val(del_id);
    $('#hid-delete-url').val('summary/deleteInvoice/' + del_id);
    $("#myModal").modal('show');
  });

  //Quick Delete Confirm
  $("#btn-confirm-yes").click(function(){
    var del_id= $('#hid-delete-id').val();
    $("#myModal").modal('hide');  
    var url =  $('#hid-delete-url').val();
    var url_split = url.split("/");
    var ref_tbl = '';
    if( url_split[1] == 'deleteInvoice') {
      ref_tbl = inv_tbl;
    }
    else if (url_split[1] == 'deletePO') {
      ref_tbl = po_tbl;
    }
    else if (url_split[1] == 'deleteDO') {
      ref_tbl = do_tbl;
    }
    else if (url_split[1] == 'deleteCost') {
      ref_tbl = cost_tbl;
    }
    else if (url_split[1] == 'deleteMisc') {
      ref_tbl = misc_tbl;
    }
    deleteAjax(url, ref_tbl);
  });

  $('#invoiceModal').on('hidden.bs.modal', function () {
    $('#inv-submiturl').val('');
    $('#inv-edit-id').val('');
    emptyForm('invoice-form');
  });

  $('#btn-inv-submit').click(function(e) { 
    e.preventDefault();
    var valid = validateForm('invoice-form');
    if(valid) {  
      var formdata = $("#invoice-form").serializeObject();
      formdata.project_id = $('#project-id').val(); 
      var url = $('#inv-submiturl').val();
      quickSummarysubmit(url, formdata, inv_tbl, 'invoiceModal', 'invoice-form');
    }   
  });

  //PO CRUD
  $('#btn-add-po').click(function(e) {
     e.preventDefault();
    $('#po-submiturl').val('summary/addPO');
    $('.quick-action').html('Add');
    $('#poModal').modal('show');
  });

  $('#po-table').on('click', '.po-edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'summary/aj_getPO/' + id,
        data :  {},
        success: function(data){ 
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#po-edit-id').val(result['pj_po_id']);   
            $('#po-po-no').val(result['po_no']);
            $('#po-vendor').val(result['vendor']);
            $('#po-total-amount').val(result['total_amount']);   
            $('#po-remark').val(result['remark']);   
            var updateurl = 'summary/editPO/' + id;
            $('#po-submiturl').val(updateurl);
            $('.quick-action').html('Edit');
            $("#poModal").modal('show');
          }
          else {
             alert(result['msg']);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
  });

  $('#po-table').on('click', '.po-delete-link', function(e) {
    e.preventDefault();
    var url =  $(this).attr("href");
    var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
    //$('#hid-delete-id').val(del_id);
    $('#hid-delete-url').val('summary/deletePO/' + del_id);
    $("#myModal").modal('show');
  });

  $('#poModal').on('hidden.bs.modal', function () {
    $('#po-submiturl').val('');
    $('#po-edit-id').val('');
    emptyForm('po-form');
  });

  $('#btn-po-submit').click(function(e) { 
    e.preventDefault();
    var valid = validateForm('po-form');
    if(valid) {  
      var formdata = $("#po-form").serializeObject();
      formdata.project_id = $('#project-id').val(); 
      var url = $('#po-submiturl').val();
      quickSummarysubmit(url, formdata, po_tbl, 'poModal', 'po-form');
    }   
  });

  //DO CRUD
  $('#btn-add-do').click(function(e) {
     e.preventDefault();
    $('#do-submiturl').val('summary/addDO');
    $('.quick-action').html('Add');
    $('#doModal').modal('show');
  });

  $('#do-table').on('click', '.do-edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'summary/aj_getDO/' + id,
        data :  {},
        success: function(data){ 
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#do-edit-id').val(result['pj_do_id']);   
            $('#do-do-no').val(result['do_no']);
            $('#do-stock-id').val(result['stock_id']);
            $('#do-total-qty').val(result['total_qty']);
            $('#do-price').val(result['price']);   
            $('#do-remark').val(result['remark']);   
            var updateurl = 'summary/editDO/' + id;
            $('#do-submiturl').val(updateurl);
            $('.quick-action').html('Edit');
            $("#doModal").modal('show');
          }
          else {
             alert(result['msg']);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
  });

  $('#do-table').on('click', '.do-delete-link', function(e) {
    e.preventDefault();
    var url =  $(this).attr("href");
    var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
    //$('#hid-delete-id').val(del_id);
    $('#hid-delete-url').val('summary/deleteDO/' + del_id);
    $("#myModal").modal('show');
  });

  $('#doModal').on('hidden.bs.modal', function () {
    $('#do-submiturl').val('');
    $('#do-edit-id').val('');
    emptyForm('do-form');
  });

  $('#btn-do-submit').click(function(e) { 
    e.preventDefault();
    var valid = validateForm('do-form');
    if(valid) {  
      var formdata = $("#do-form").serializeObject();
      formdata.project_id = $('#project-id').val(); 
      var url = $('#do-submiturl').val();
      quickSummarysubmit(url, formdata, do_tbl, 'doModal', 'do-form');
    }   
  });

  //Cost CRUD
  $('#btn-add-cost').click(function(e) {
     e.preventDefault();
    $('#cost-submiturl').val('summary/addCost');
    $('.quick-action').html('Add');
    $('#costModal').modal('show');
  });

  $('#cost-table').on('click', '.cost-edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'summary/aj_getCost/' + id,
        data :  {},
        success: function(data){ 
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#cost-edit-id').val(result['pj_cost_id']);   
            $('#cost-description').val(result['description']);
            $('#cost-qty').val(result['qty']);   
            $('#cost-uom-id').val(result['uom_id']);
            $('#cost-price').val(result['price']);   
            var updateurl = 'summary/editCost/' + id;
            $('#cost-submiturl').val(updateurl);
            $('.quick-action').html('Edit');
            $("#costModal").modal('show');
          }
          else {
             alert(result['msg']);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
  });

  $('#cost-table').on('click', '.cost-delete-link', function(e) {
    e.preventDefault();
    var url =  $(this).attr("href");
    var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
    //$('#hid-delete-id').val(del_id);
    $('#hid-delete-url').val('summary/deleteCost/' + del_id);
    $("#myModal").modal('show');
  });

  $('#costModal').on('hidden.bs.modal', function () {
    $('#cost-submiturl').val('');
    $('#cost-edit-id').val('');
    emptyForm('cost-form');
  });

  $('#btn-cost-submit').click(function(e) { 
    e.preventDefault();
    var valid = validateForm('cost-form');
    if(valid) {  
      var formdata = $("#cost-form").serializeObject();
      formdata.project_id = $('#project-id').val(); 
      var url = $('#cost-submiturl').val();
      quickSummarysubmit(url, formdata, cost_tbl, 'costModal', 'cost-form');
    }   
  });

  //MISC CRUD
  $('#btn-add-misc').click(function(e) {
     e.preventDefault();
    $('#misc-submiturl').val('summary/addMisc');
    $('.quick-action').html('Add');
    $('#miscModal').modal('show');
  });

  $('#misc-table').on('click', '.misc-edit-link', function(e) { 
      e.preventDefault();
      var id = getIdfromURL($(this).attr('href'));
      $.ajax({
        type :  "POST",
        url  :  burl + 'summary/aj_getMisc/' + id,
        data :  {},
        success: function(data){ 
          var result = $.parseJSON(data);
          if(result['status'] == 'success') {
            $('#misc-edit-id').val(result['pj_misc_id']);   
            $('#misc-description').val(result['description']);
            $('#misc-qty').val(result['qty']);   
            $('#misc-uom-id').val(result['uom_id']);
            $('#misc-price').val(result['price']);   
            var updateurl = 'summary/editMisc/' + id;
            $('#misc-submiturl').val(updateurl);
            $('.quick-action').html('Edit');
            $("#miscModal").modal('show');
          }
          else {
             alert(result['msg']);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
  });

  $('#misc-table').on('click', '.misc-delete-link', function(e) {
    e.preventDefault();
    var url =  $(this).attr("href");
    var del_id = url.substring(url.lastIndexOf("/") + 1, url.length);
    //$('#hid-delete-id').val(del_id);
    $('#hid-delete-url').val('summary/deleteMisc/' + del_id);
    $("#myModal").modal('show');
  });

  $('#miscModal').on('hidden.bs.modal', function () {
    $('#misc-submiturl').val('');
    $('#misc-edit-id').val('');
    emptyForm('misc-form');
  });

  $('#btn-misc-submit').click(function(e) { 
    e.preventDefault();
    var valid = validateForm('misc-form');
    if(valid) {  
      var formdata = $("#misc-form").serializeObject();
      formdata.project_id = $('#project-id').val(); 
      var url = $('#misc-submiturl').val();
      quickSummarysubmit(url, formdata, misc_tbl, 'miscModal', 'misc-form');
    }   
  });

  function getInvoiceTotal() {
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_getTotalinvoiceamount/" + $('#project-id').val(), 
        data: { },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#total-inv-amount').html(result['total']);
              calculateProfit();
            }
            else {
                alert('Error In Total Invoice Amount Update.');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });
  }

  function getPoTotal() {
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_getTotalpoamount/" + $('#project-id').val(), 
        data: { },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#total-po-amount').html(result['total']);
              calculateProfit();
            }
            else {
                alert('Error In Total Invoice Amount Update.');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });
  }

  function getDoTotal() {
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_getTotaldoamount/" + $('#project-id').val(), 
        data: { },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#total-do-amount').html(result['total']);
              calculateProfit();
            }
            else {
                alert('Error In Total Invoice Amount Update.');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });
  }

  function getCostTotal() {
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_getTotalcostamount/" + $('#project-id').val(), 
        data: { },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#total-cost-amount').html(result['total']);
              calculateProfit();
            }
            else {
                alert('Error In Total Invoice Amount Update.');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });
  }

  function getMiscTotal() {
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_getTotalmiscamount/" + $('#project-id').val(), 
        data: { },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#total-misc-amount').html(result['total']);
              calculateProfit();
            }
            else {
                alert('Error In Total Invoice Amount Update.');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });
  }

  function calculateProfit() {
    // var total = parseFloat($('#total-inv-amount').text()) - parseFloat($('#total-po-amount').text()) - parseFloat($('#total-do-amount').text()) - parseFloat($('#total-cost-amount').text()) - parseFloat($('#total-misc-amount').text());
    // $('#profit-loss-area').html(myFixed(total, 2));
    $.ajax({
        type: "POST",
        url: burl + "summary/aj_calculateProfit", 
        data: { 
          inv_total   : $('#total-inv-amount').text(),
          po_total    : $('#total-po-amount').text(),
          do_total    : $('#total-do-amount').text(),
          cost_total  : $('#total-cost-amount').text(),
          misc_total  : $('#total-misc-amount').text(),
        },
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#profit-loss-area').html( '$ ' + result['total']);
            }
            else {
                alert('Error In Profit Calculation...');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("ERROR!!!");        
        } 
    });


  }

});