
$(document).ready(function(){

  $('.air_date_picker').datepicker({
    language: 'en',
    todayButton: new Date(),
    autoClose : true,
    position: "bottom center"
    // minDate: new Date('07/04/2020')
    // inline : true,
    // minDate: new Date() // Now can select only dates, which goes after today
  });
  $(document).on('click', '#addTable', function() {
    const category_jewelry = document.getElementById('category_jewelry');
    if(category_jewelry.checked){
      const count = document.querySelectorAll('.count');
      const length = count.length;
      // console.log(length);
      let jewelry_counter = length == 0 ? 0 : parseInt(count[length - 1].value) + 1;
      jewelryTable(jewelry_counter);
    }
    
  });

  $('#itemTable').on('click','.remove_table',function() {
    $(this).closest('table').remove();
    setAppraisedComputation();
  });

  
  

  
});

// let jewelry_counter = 0;
function togglePassword(element){
    const state = element.getAttribute('data-id');
    const password = document.getElementById('password');
    // $(this).attr('data-id', 'off');
    if(state == 'off'){
        element.setAttribute('data-id', 'on');
        password.setAttribute('type', 'text');    
        element.innerHTML = '<i class="fas fa-eye-slash"></i>';
        return;
    }

    element.setAttribute('data-id', 'off');
    password.setAttribute('type', 'password');    
    element.innerHTML = '<i class="fas fa-eye"></i>';

}

function transaction_dates(element){
    // var date = document.getElementById("transaction_date").value;
    // console.log(moment);
    var date = element.value;
    const maturity = moment(date, 'MM/DD/YYYY').add(30, 'days');
    const expiration = moment(date, 'MM/DD/YYYY').add(120, 'days');
    const auction = moment(date, 'MM/DD/YYYY').add(180, 'days');

    if(maturity.isValid()){
      document.getElementById("maturity_date").value =  maturity.format('MM/DD/YYYY');
      document.getElementById("expiration_date").value = expiration.format('MM/DD/YYYY');
      document.getElementById("auction_date").value = auction.format('MM/DD/YYYY');
    } else {
      document.getElementById("maturity_date").value = '';
      document.getElementById("expiration_date").value = '';
      document.getElementById("auction_date").value = '';
    }


  }

  function remove_section(element){
    // console.log(element);
    $(element).parent().closest('section').remove();

  }

  function customerForm(event, element){
    event.preventDefault();
    $('.error_message').css('display', 'none');
    $('.input').removeClass('has-error is-focused');
    var form_data = new FormData(element);
    const id = form_data.get('id');
    const url = id == null ? '/settings/customer' : '/settings/customer/'+id; 
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        url : url,
        type : "POST",
        data : form_data,
        cache: false,
        contentType : false,
        processData : false,
        success: (data) => {
          // console.log(data.success);
          if(data.success === true){
            alert_message('Success', data.status);
           location.reload();
          }else{
            alert_message('Danger', 'Error occured. Pleasse contact administrator');

          }

        },
        error: (data) => {
          const error = data.responseJSON.errors;
          for(let errorValue in error){
            if (!error.hasOwnProperty(errorValue)) continue;
               $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
                $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

          }
        }
      });
  };
function select2Initialized(element, route, table = null){
  // console.log(table);
  if(table != null){
    table = table;
  }
  $(element).select2({
    width: '100%',
    ajax: {
      url: route,
    data: function (params) {
      return {
        search: params.term,
        table,
      }
    },
    processResults: function (data) {
        return {
          results : data
        }
    },

  }
  });
}

function getAttachmentNumber(attachment_id){
  const attachmentNumber = document.querySelector('#attachment_number');
  attachmentNumber.value = attachment_id.getAttribute('data-number');
}
 
function getSuki(radio){
  // console.log(document.getElementById("category_jewelry").checked);
  const suki_check = document.getElementById('suki_check');
  const suki = document.getElementById("suki");
  suki_check.checked = false;
  if(document.getElementById("category_jewelry").checked){
      suki.style.display = "block";
      suki_check.disabled = false;
  }else {
      suki.style.display = "none";
      suki_check.disabled = true;
  }
}

function alert_message(status, message){
  $('.alert_message').html('<div class="col-xl-7 col-lg-9 col-md-8 col-sm-12 col-12 mx-auto">'+
       '<div class="alert alert-'+status.toLowerCase()+' alert-dismissible fade show text-center" style="font-size:15px" role="alert">'+
           '<strong>'+status+'!</strong> '+message+ 
               '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                   '<span aria-hidden="true">&times;</span>'+
               '</button>'+
       '</div>'+
   '</div>');
 
}

function jewelryTable(jewelry_counter = 0){

  $("#itemTable").append('<table class="table table-bordered mt-3 jewelry_table" width="100%">'+
      '<thead>'+
        '<tr>'+
          '<td style="width:10%">Material <br> Item Type <br> Karat</td>'+
          '<td>Weight (g)</td>'+
          '<td>Rate Appraised Value</td>'+
          '<td>Description</td>'+
          '<td>Image</td>'+
          '<td></td>'+
        '</tr>'+
      '</thead>'+
      '<tbody>'+
      '<tr>'+
        '<td>'+
          '<select name="item_type_id['+jewelry_counter+']" class="form-control item_type" onChange="setAppraisedValue(this);getKarat(this.value, this)">'+
            '<option selected disabled></option>'+
          '</select>'+
        '</td>'+
        '<td>'+
          '<input class="form-control item_type_weight" type="number" name="item_type_weight['+jewelry_counter+']" value="0.00" onKeyup="setKaratWeight(this);setAppraisedValue(this)">'+
        '</td>'+
        '<td>'+
          '<input class="form-control item_type_appraised_value" type="number" name="item_type_appraised_value['+jewelry_counter+']" value="0" readonly >'+
        '</td>'+
        '<td rowspan=3 class="text-center">'+
          '<textarea class="form-control" rows="5" name="description['+jewelry_counter+']" id="description"></textarea> <br>'+
          '<button id="addDiamond" class="btn btn-warning btn-sm" type="button">ADD DIAMOND</button>'+
        '</td>'+
        '<td rowspan=3 class="text-center">'+
          '<div class="form-group">'+
            '<input type="file" name="image['+jewelry_counter+']" class="image" onChange="checkUpload(this)">'+
            '<button type="button" class="btn btn-success btn-sm uploadButton">Upload Image</button>'+
            '<div class="input-group">'+
              '<input type="text" name="image['+jewelry_counter+']" readonly="" class="form-control imageText">'+
            '</div>'+

          '</div>'+
          '<button type="button" class="btn btn-danger btn-sm removeImage" style="display:none" onClick="removeImage(this)">Remove Image</button>'+

        '</td>'+
        '<td rowspan=3>'+
          '<i class="material-icons remove_table" style="cursor: pointer;">cancel</i>'+
        '</td>'+
      '</tr>'+
      
      '<tr>'+
        '<td>'+
          '<input type="text" name="item_name['+jewelry_counter+']" class="form-control">'+

        '</td>'+
        '<td>'+
          '<input class="form-control" type="number" name="item_name_weight['+jewelry_counter+']" value="0.00">'+
        '</td>'+
        '<td>'+
          '<input class="form-control" type="number" name="item_name_appraised_value['+jewelry_counter+']" value="0" readonly>'+
        '</td>'+
      '</tr>'+
      
      '<tr>'+
        '<td>'+
          '<select name="item_karat['+jewelry_counter+']" class="form-control item_karat" onChange="setAppraisedValue(this)">'+
              '<option></option>'+
            '</select>'+
        '</td>'+
        '<td>'+
          '<input class="form-control karat_weight" type="number" name="item_karat_weight['+jewelry_counter+']" value="0.00" readonly>'+
          '<input type="hidden" value="'+jewelry_counter+'" class="count">'+
        '</td>'+
        '<td>'+
        '</td>'+
      '</tr>'+
      '</tbody>'+
      '</table>');
    
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      method: "GET",
      url: '/settings/rates.getItemType/1',
      success: (data) => {
        const item_type_element = document.querySelectorAll('.item_type');
        let item_type_value = '<option selected disabled></option>';
        data.forEach(element => {
          item_type_value += '<option value="'+element.id+'">'+element.item_type+'</option>';
        })
        for(let i = 0; i < item_type_element.length; i++){
          if(item_type_element[i].value == ''){
            item_type_element[i].innerHTML = item_type_value;
          }

        }
      },
      error: (data) => {
        console.log(data);
      }
  
    });
    // jewelry_counter++;
}

function getItemType(){
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    method: "GET",
    url: '/settings/rates.getItemType/1',
    success: (data) => {
      const item_type_element = document.querySelectorAll('.item_type');
      let item_type_value = '<option selected disabled></option>';
      data.forEach(element => {
        item_type_value += '<option value="'+element.id+'">'+element.item_type+'</option>';
      })
      for(let i = 0; i < item_type_element.length; i++){
        if(item_type_element[i].value == ''){
          item_type_element[i].innerHTML = item_type_value;
        }

      }
    },
    error: (data) => {
      console.log(data);
    }

  });
}
function getKarat(item_type, element){
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    method: "GET",
    url: '/settings/rates.getKarat/'+item_type,
    success: (data) => {
      // console.log(data);
      const item_karat = $(element).closest('table').find('.item_karat');
      // const suki_check = document.getElementById('suki_check');
      // item_karat[0].innerHTML = '';
      let item_karat_element = '<option selected disabled></option>';
      data.forEach(element => {
        item_karat_element += '<option value="'+element.karat+'" data-gram="'+element.gram+'" data-regular_rate="'+element.regular_rate+'" data-special_rate="'+element.special_rate+'">'+element.karat+'</option>';
      })
      item_karat[0].innerHTML = item_karat_element;
      $(element).closest('table').find('.item_type_appraised_value').val(0);
      setAppraisedComputation();
    },
    error: (data) => {
      console.log(data);
    }

  });
}

function setKaratWeight(element){
  const item_type_weight = $(element).val();
  const karat_weight = $(element).closest('table').find('.karat_weight');
  karat_weight[0].value = item_type_weight
}

function setAppraisedValue(element){
  // console.log(element);
  const item_type = $(element).closest('table').find('.item_type');
  const item_type_weight = $(element).closest('table').find('.item_type_weight');
  const item_karat = $(element).closest('table').find('.item_karat');
  const item_type_appraised_value = $(element).closest('table').find('.item_type_appraised_value');
  if($(item_type).val() != '' && ($(item_type_weight).val() != '' && $(item_type_weight).val() != '0.00') && $(item_karat).val() != null && $(item_karat).val() != ''){
    // console.log($(item_type_weight).val());

    const gram = $(item_karat).find(':selected').attr('data-gram');
    const regular_rate = $(item_karat).find(':selected').attr('data-regular_rate');
    const special_rate = $(item_karat).find(':selected').attr('data-special_rate');
    const suki_check = document.getElementById('suki_check');
    let rate_appraised_value = 0;
    if(suki_check.checked){
      rate_appraised_value = ($(item_type_weight).val() * gram) * special_rate;
      // rate_appraised_value = special_rate * gram;
    }else{
      rate_appraised_value = ($(item_type_weight).val() * gram) * regular_rate;
      // rate_appraised_value = regular_rate * gram;
      // console.log(item_type_weight);
    }

    $(item_type_appraised_value).val(rate_appraised_value.toFixed(2));

  }
  setAppraisedComputation();
}

function checkRate(element){

    $('.item_type_appraised_value').each(function(index){
      if($(this).val() != 0){
        const item_type_weight = $(this).closest('table').find('.item_type_weight');
        const item_karat = $(this).closest('table').find('.item_karat');
        const gram = $(item_karat).find(':selected').attr('data-gram');
        const regular_rate = $(item_karat).find(':selected').attr('data-regular_rate');
        const special_rate = $(item_karat).find(':selected').attr('data-special_rate');
        let rate_appraised_value = 0;
        if(element.checked){
          rate_appraised_value = ($(item_type_weight).val() * gram) * special_rate;
        }else{
          rate_appraised_value = ($(item_type_weight).val() * gram) * regular_rate;
        }
        $(this).val(rate_appraised_value.toFixed(2));
        setAppraisedComputation();
      }
    });
  }
function checkUpload(element){
  // console.log(element.value);
  const uploadButton = $(element).closest('table').find('.uploadButton');
  const removeImage = $(element).closest('table').find('.removeImage');
  $(uploadButton).html('UPLOAD IMAGE');
  $(removeImage).css('display', 'none');
  if(element.value != ''){
    $(uploadButton).html('CHANGE IMAGE');
    $(removeImage).css('display', '');
  }
}

function removeImage(element){
  const image = $(element).closest('table').find('.image');
  const imageText = $(element).closest('table').find('.imageText');
 
  $(image).val('');
  $(imageText).val('');
  $(element).css('display', 'none');

}
function inventoryForm(event, element){
  event.preventDefault();
  // $('.error_message').css('display', 'none');
  // $('.input').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/inventory' : '/pawn_auction/inventory/'+id; 
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      url : url,
      type : "POST",
      data : form_data,
      cache: false,
      contentType : false,
      processData : false,
      success: (data) => {
        // console.log(data.success);
        if(data.success === true){
          // alert_message('Success', data.status);
        if(data.create === true){
          window.location.href = data.link;
        }
         location.reload();

        }else{
          alert_message('Danger', 'Error occured. Pleasse contact administrator');
        }

      },
      error: (data) => {
        const error = data.responseJSON.errors;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;
             $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
              $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

        }
      }
    });
};

function add_other_charges(element){
  // console.log(element);
  const tbody = document.querySelector('#other_charges_body');
  const tr = tbody.insertRow(tbody.rows.length);
  const other_charges_select = tr.insertCell(0);
  const other_charges_amount = tr.insertCell(1);
  const remove = tr.insertCell(2);
  other_charges_select.innerHTML = '<select class="form-control other_charges_select" name="other_charges_id[]"></select>';
  other_charges_amount.innerHTML = '<input type="text" class="form-control other_charges_amount" name="other_charges_amount[]">';
  remove.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>';

  const other_charges = '.other_charges_select';
  const other_charges_route =  '/settings/other_charges/search' ;
  const table_other_charges = 'other_charges';
      select2Initialized(other_charges,other_charges_route, table_other_charges);

}

function removeTr(element){
  $(element).closest('tr').remove();
  setOtherCharges();
}

function setOtherCharges(){
  const other_charges_amount = document.querySelectorAll('.other_charges_amount');
  const other_charges = document.getElementById('other_charges');
  let total = 0;
  for(let i = 0; i < other_charges_amount.length; i++){
      total += parseFloat(other_charges_amount[i].value);
  }
  other_charges.value = total.toFixed(2);
  setNetProceeds();
}


function renewForm(event, element){
  event.preventDefault();
  // $('.error_message').css('display', 'none');
  // $('.input').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/inventory/renew' : '/pawn_auction/inventory/renew/'+id; 
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      url : url,
      type : "POST",
      data : form_data,
      cache: false,
      contentType : false,
      processData : false,
      success: (data) => {
        // console.log(data.success);
        if(data.success === true){
          // alert_message('Success', data.status);
        if(data.create === true){
          window.location.href = data.link;
        }
         location.reload();

        }else{
          alert_message('Danger', 'Error occured. Pleasse contact administrator');
        }

      },
      error: (data) => {
        const error = data.responseJSON.errors;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;
             $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
              $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

        }
      }
    });
};


function redeemForm(event, element){
  event.preventDefault();
  // $('.error_message').css('display', 'none');
  // $('.input').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/inventory/redeem' : '/pawn_auction/inventory/renew/'+id; 
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      url : url,
      type : "POST",
      data : form_data,
      cache: false,
      contentType : false,
      processData : false,
      success: (data) => {
        // console.log(data.success);
        if(data.success === true){
          // alert_message('Success', data.status);
        if(data.create === true){
          window.location.href = data.link;
        }
         location.reload();

        }else{
          alert_message('Danger', 'Error occured. Pleasse contact administrator');
        }

      },
      error: (data) => {
        const error = data.responseJSON.errors;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;
             $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
              $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

        }
      }
    });
};

