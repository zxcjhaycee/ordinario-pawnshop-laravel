
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
  // $(document).on('click', '#addTable', function() {


  // });

  $('#itemTable').on('click','.remove_table',function() {
    const inventory_item = $(this).closest('tbody').find('.inventory_item_id').val();
    const ticket_item = $(this).closest('tbody').find('.ticket_item_id').val();
    typeof inventory_item !='undefined' ? $('#items').append('<input type="hidden" name="inventory_deleted_item[]" value="'+inventory_item+'" id="deleted_item" />') : "";
    typeof ticket_item !='undefined' ? $('#items').append('<input type="hidden" name="ticket_deleted_item[]" value="'+ticket_item+'" id="deleted_item" />') : "";
    $(this).closest('table').remove();
    setAppraisedComputation();
  });

  $('.edit_pt').click(function(){
      const id = $(this).attr('id');
      // console.log(id);
      const input = '<input type="text" class="form-control input_date" name="ticket_number" value="'+id+'">';
      $(this).parent().html(input);
      $('#ticket_text').remove();
      $(this).hide();
  })
  
  $('#transaction_date').mask("00/00/0000", {placeholder: "mm/dd/yyyy"});

  
});

// let jewelry_counter = 0;

function togglePassword(element){
    const state = element.getAttribute('data-id');
    const password = document.getElementById('password');
    // $(this).attr('data-id', 'off');
    if(state == 'off'){
        element.setAttribute('data-id', 'on');
        password.setAttribute('type', 'text');    
        element.innerHTML = '<i class="fas fa-eye-slash"></input>';
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
    // const maturity = moment(date, 'MM/DD/YYYY').add(30, 'days');
    // const expiration = moment(date, 'MM/DD/YYYY').add(122, 'days');
    // const auction = moment(date, 'MM/DD/YYYY').add(182, 'days');
    const maturity = moment(date, 'MM/DD/YYYY').add(1, 'months');
    const expiration = moment(maturity, 'MM/DD/YYYY').add(3, 'months');
    const auction = moment(expiration, 'MM/DD/YYYY').add(2, 'months');

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
           window.scrollTo(0, 0);
           location.reload();
          }else{
            alert_message('Danger', 'Error occured. Pleasse contact administrator');
            window.scrollTo(0, 0);
          }

          if(data.auth_code_error === false){
            alert_message('Danger', data.status);
            window.scrollTo(0, 0);
          }

        },
        error: (data) => {
          const error = data.responseJSON.errors;
          window.scrollTo(0, 0);
          for(let errorValue in error){
            if (!error.hasOwnProperty(errorValue)) continue;
               $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
                $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

          }
        }
      });
  };
function select2Initialized(element, route, table = null, placeholder = null){
  // console.log(table);
  if(table != null){
    table = table;
  }
  $(element).select2({
    width: '100%',
    placeholder : placeholder,
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

  if(document.getElementById("category_jewelry").checked){
      suki.style.display = "block";
      suki_check.disabled = false;
  }else {
      suki_check.checked = false;
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

  $("#itemTable").append('<table class="table table-bordered mt-3 jewelry_table table_'+jewelry_counter+'_error" width="100%">'+
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
          '<div class="form-group input item_type_id_'+jewelry_counter+'_error">'+
          '<select name="item_type_id['+jewelry_counter+']" class="form-control item_type" onChange="setAppraisedValue(this);getKarat(this.value, this)" title="This field is required" data-toggle="tooltip">'+
            '<option selected disabled></option>'+
          '</select>'+

          '</div>'+
        '</td>'+
        '<td>'+
        '<div class="form-group input item_type_weight_'+jewelry_counter+'_error ">'+
          '<input class="form-control item_type_weight" type="number" name="item_type_weight['+jewelry_counter+']" value="0.00" step=".01" onKeyup="setKaratWeight(this);setAppraisedValue(this)">'+
        '</div>'+
          '</td>'+
        '<td>'+
        '<div class="form-group input item_type_appraised_value_'+jewelry_counter+'_error">'+
          '<input class="form-control item_type_appraised_value" type="number" name="item_type_appraised_value['+jewelry_counter+']" value="0" readonly >'+
        '</div>'+
        '</td>'+
        '<td rowspan=3 class="text-center">'+
          '<div class="form-group input description_'+jewelry_counter+'_error">'+
          '<textarea class="form-control" rows="5" name="description['+jewelry_counter+']" id="description" title="This field is required" data-toggle="tooltip"></textarea> <br>'+
          '</div>'+
          '<button id="addDiamond" class="btn btn-warning btn-sm" type="button">ADD DIAMOND</button>'+
        '</td>'+
        '<td rowspan=3 class="text-center">'+
          '<div class="form-group input image_'+jewelry_counter+'_error">'+
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
        '<div class="form-group input item_name_'+jewelry_counter+'_error">'+
          '<input type="text" name="item_name['+jewelry_counter+']" class="form-control item_name" title="This field is required" data-toggle="tooltip">'+
        '</div>'+
        '</td>'+
        '<td>'+
          '<input class="form-control item_name_weight" type="number" name="item_name_weight['+jewelry_counter+']" value="0.00" onKeyup="setKaratWeight(this);setAppraisedValue(this)" step=".01">'+
        '</td>'+
        '<td>'+
          '<input class="form-control item_name_appraised_value" type="number" name="item_name_appraised_value['+jewelry_counter+']" value="0" readonly>'+
        '</td>'+
      '</tr>'+
      
      '<tr>'+
        '<td>'+
        '<div class="form-group input item_karat_'+jewelry_counter+'_error">'+
          '<select name="item_karat['+jewelry_counter+']" class="form-control item_karat" onChange="setAppraisedValue(this)" title="This field is required" data-toggle="tooltip">'+
              '<option></option>'+
            '</select>'+
          '</div>'+
        '</td>'+
        '<td>'+
          '<input class="form-control karat_weight" type="number" name="item_karat_weight['+jewelry_counter+']" value="0.00" step=".01" readonly>'+
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
        let item_type_value = '<option selected></option>';
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

function nonJewelryTable(non_jewelry_counter = 0){
  $("#itemTable").append('<table class="table table-bordered mt-3 non_jewelry_table table_'+non_jewelry_counter+'_error" width="100%">'+
      '<thead>'+
        '<tr>'+
          '<td style="width:20%">Item Type <br/> Item Name</td>'+
          '<td>Rate Appraised Value</td>'+
          '<td>Description</td>'+
          '<td>Image</td>'+
          '<td></td>'+
        '</tr>'+
      '</thead>'+
      '<tbody>'+
      '<tr>'+
      '<td>'+
          '<div class="form-group input item_type_id_'+non_jewelry_counter+'_error">'+
          '<select name="item_type_id['+non_jewelry_counter+']" class="form-control item_type" onChange="setNonJewelryAppraisedValue(this);getItemName(this.value, this)">'+
            '<option selected></option>'+
          '</select>'+
          '</div>'+
      '</td>'+
      '<td rowspan="2">'+
        '<div class="form-group input item_name_appraised_value'+non_jewelry_counter+'_error">'+
          '<input class="form-control item_name_appraised_value" type="number" name="item_name_appraised_value['+non_jewelry_counter+']" value="0" readonly >'+
        '</div>'+
      '</td>'+
      '<td rowspan=2 class="text-center">'+
          '<div class="form-group input description_'+non_jewelry_counter+'_error">'+
          '<textarea class="form-control" rows="5" name="description['+non_jewelry_counter+']" id="description"></textarea> <br>'+
          '</div>'+
      '</td>'+
      '<td rowspan=2 class="text-center">'+
        '<div class="form-group input image_'+non_jewelry_counter+'_error">'+
          '<input type="file" name="image['+non_jewelry_counter+']" class="image" onChange="checkUpload(this)">'+
          '<button type="button" class="btn btn-success btn-sm uploadButton">Upload Image</button>'+
          '<div class="input-group">'+
            '<input type="text" name="image['+non_jewelry_counter+']" readonly="" class="form-control imageText">'+
          '</div>'+
        '</div>'+
        '<button type="button" class="btn btn-danger btn-sm removeImage" style="display:none" onClick="removeImage(this)">Remove Image</button>'+

      '</td>'+
      '<td rowspan=2>'+
        '<i class="material-icons remove_table" style="cursor: pointer;">cancel</i>'+
      '</td>'+
    '</tr>'+
        '<tr>'+
            '<td>'+
              '<div class="form-group input item_name_'+non_jewelry_counter+'_error">'+
                '<select name="item_name['+non_jewelry_counter+']" class="form-control item_name" onChange="setNonJewelryAppraisedValue(this)">'+
                  '<option selected></option>'+
                '</select>'+
                '<input type="hidden" value="'+non_jewelry_counter+'" class="count">'+
              '</div>'+
            '</td>'+
        '</tr>'+
      '</tbody>'+
      '</table>');

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        method: "GET",
        url: '/settings/rates.getItemType/2',
        success: (data) => {
          const item_type_element = document.querySelectorAll('.item_type');
          let item_type_value = '<option selected></option>';
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
  let branch_id = document.getElementById('branch_id');
  branch_id = branch_id.value != '' ? branch_id.value : null;
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    method: "GET",
    url: '/settings/rates.getKarat/'+item_type+'/'+branch_id,
    success: (data) => {
      // console.log(data);
      const item_karat = $(element).closest('table').find('.item_karat');
      // const suki_check = document.getElementById('suki_check');
      // item_karat[0].innerHTML = '';
      let item_karat_element = '<option selected></option>';
      data.forEach(element => {
        item_karat_element += '<option value="'+element.karat+'" data-regular_rate="'+element.regular_rate+'" data-special_rate="'+element.special_rate+'">'+element.karat+'</option>';
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

function getItemName(item_type, element){
  let branch_id = document.getElementById('branch_id');
  branch_id = branch_id.value != '' ? branch_id.value : null;
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    method: "GET",
    url: '/settings/rates.getKarat/'+item_type+'/'+branch_id,
    success: (data) => {

      const item_name = $(element).closest('table').find('.item_name');
      let item_name_element = '<option selected disabled></option>';
      data.forEach(element => {
        item_name_element += '<option value="'+element.description+'" data-regular_rate="'+element.regular_rate+'">'+element.description+'</option>';
      })
      item_name[0].innerHTML = item_name_element;
      $(element).closest('table').find('.item_type_appraised_value').val(0);
      setAppraisedComputation();
    },
    error: (data) => {
      console.log(data);
    }

  });
}

function setItemType(){
  $('.item_type').val("");
  $('.item_karat').val("");
  $('.item_type_weight').val(0);
  $('.karat_weight').val(0);
  $('.item_type_appraised_value').val(0);
}

function setItemBranch(){
  const branch = document.getElementById('branch_id').value;
  const item_category = document.getElementById('item_category');
  const suki = document.getElementById('suki');
  const item_button = document.getElementById('item_button');
  const jewelry_table = document.querySelectorAll('.jewelry_table');
  const non_jewelry_table = document.querySelectorAll('.non_jewelry_table');
  const radio_button = document.getElementsByName('item_category_id');
  radio_button.forEach((element) => {
    element.checked = false;
  });
  jewelry_table.forEach((element, key) => {
    jewelry_table[key].remove();
  });
  non_jewelry_table.forEach((element, key) => {
    non_jewelry_table[key].remove();
  });
  item_category.style.display = 'none';
  suki.checked = false;
  suki.style.display = 'none';
  item_button.style.display = 'none';
  if(branch != ''){
    item_category.removeAttribute('style');
  }
  setAppraisedComputation();

}
function setKaratWeight(element){
  const item_type_weight = $(element).closest('table').find('.item_type_weight')
  const karat_weight = $(element).closest('table').find('.karat_weight');
  const item_name_weight = $(element).closest('table').find('.item_name_weight');
  // console.log(item_type_weight.val());
  if(item_name_weight.val() > item_type_weight.val()){
    item_name_weight.val(0);
  }
  karat_weight[0].value = (item_type_weight.val() - item_name_weight.val()).toFixed(2);
}

function setAppraisedValue(element){
  // console.log(element);
  const item_type = $(element).closest('table').find('.item_type');
  const item_type_weight = $(element).closest('table').find('.item_type_weight');
  const item_karat = $(element).closest('table').find('.item_karat');
  const item_karat_weight = $(element).closest('table').find('.karat_weight');
  const item_name_weight = $(element).closest('table').find('.item_name_weight');
  const item_type_appraised_value = $(element).closest('table').find('.item_type_appraised_value');
  const item_name_appraised_value = $(element).closest('table').find('.item_name_appraised_value');
  if($(item_type).val() != '' && ($(item_type_weight).val() != '' && $(item_type_weight).val() != '0.00') && $(item_karat).val() != null && $(item_karat).val() != ''){
    // console.log($(item_type_weight).val());

    // const gram = $(item_karat).find(':selected').attr('data-gram');
    const regular_rate = $(item_karat).find(':selected').attr('data-regular_rate');
    const special_rate = $(item_karat).find(':selected').attr('data-special_rate');
    const suki_check = document.getElementById('suki_check');
    let rate_appraised_value = 0;
    let rate = 0;
    if(suki_check.checked){
      rate_appraised_value = $(item_karat_weight).val() * special_rate;
      rate = parseFloat(special_rate);
      // rate_appraised_value = special_rate * gram;
    }else{
      rate_appraised_value = $(item_karat_weight).val() * regular_rate;
      rate = parseFloat(regular_rate);
      // rate_appraised_value = regular_rate * gram;
      // console.log(item_type_weight);
    }
    // console.log(rate);
    $(item_type_appraised_value).val(rate.toFixed(2));
    $(item_name_appraised_value).val(rate_appraised_value.toFixed(2));

  }
  setAppraisedComputation();
}

function setNonJewelryAppraisedValue(element){
  const item_name = $(element).closest('table').find('.item_name');
  const item_type = $(element).closest('table').find('.item_type');
  const item_type_appraised_value = $(element).closest('table').find('.item_type_appraised_value');
  const item_name_appraised_value = $(element).closest('table').find('.item_name_appraised_value');
  if($(item_type).val() != '' && $(item_name).val() != null){
    const regular_rate = $(item_name).find(':selected').attr('data-regular_rate');
    $(item_name_appraised_value).val(parseFloat(regular_rate).toFixed(2));
  }
  setAppraisedComputation();
}
function checkRate(element){
    // console.log("Hello!");
    $('.item_name_appraised_value').each(function(index){
      if($(this).val() != 0){
        const item_type_weight = $(this).closest('table').find('.item_type_weight');
        const item_karat = $(this).closest('table').find('.item_karat');
        const gram = $(item_karat).find(':selected').attr('data-gram');
        const regular_rate = $(item_karat).find(':selected').attr('data-regular_rate');
        const special_rate = $(item_karat).find(':selected').attr('data-special_rate');
        const item_type_appraised_value = $(this).closest('table').find('.item_type_appraised_value');
        let rate_appraised_value = 0;
        if(element.checked){
          $(item_type_appraised_value).val(parseFloat(special_rate).toFixed(2));
          rate_appraised_value = special_rate * $(item_type_weight).val();
        }else{
          $(item_type_appraised_value).val(parseFloat(regular_rate).toFixed(2));
          rate_appraised_value = regular_rate * $(item_type_weight).val();
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
  $('.error_message').css('display', 'none');
  $('.input').removeClass('has-error is-focused');
  $('.alert_error').remove();
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/pawn' : '/pawn_auction/pawn/'+id; 
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
        // console.log(data);
        if(data.success){
          window.location.href = data.link;
          return;
        }
        if(!data.auth_code_error){
          window.scrollTo(0, 0);
          alert_message('Danger', data.status);
          return;
        }
        window.scrollTo(0, 0);
        alert_message('Danger', 'Error occured. Pleasse contact administrator');


      },
      error: (data) => {
        const error = data.responseJSON.errors;
        // window.scrollTo(0, 0);
        let first = true;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;
            if(first){
              $('.'+errorValue.replace(/\./g, '_')+'_error').find(':input').focus();
              first = false;
            }

             if(/\.|[0-9]/g.test(errorValue)){
              $('.'+errorValue.replace(/\./g, '_')+'_error').addClass('has-error is-focused');
             }else{
              $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
              $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');

             }


             const alert_error = '<div class="col-xl-8 col-lg-12 col-md-12 mx-auto alert_error">'+
             '<div class="alert alert-danger alert-dismissible fade show text-center" style="font-size:15px" role="alert">'+
                 '<strong>Error!</strong> '+error[errorValue][0].replace(/\.|[0-9]/g, '')+
                     '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                         '<span aria-hidden="true">&times;</span>'+
                     '</button>'+
             '</div>'+
          '</div>';
            const count = errorValue.match(/\d+/g);
            if(count !== null){
              $( alert_error ).insertBefore('.table_'+count[0]+'_error');
            }
        }
      }
    });
};

function add_other_charges(element, table){
  // console.log(element);
  const tbody = document.querySelector('#'+table+'_body');
  const tr = tbody.insertRow(tbody.rows.length);
  const other_charges_select = tr.insertCell(0);
  const other_charges_amount = tr.insertCell(1);
  const remove = tr.insertCell(2);
  other_charges_select.innerHTML = '<select class="form-control '+table+'_select" name="other_charges_id[]"></select>';
  other_charges_amount.innerHTML = '<input type="text" class="form-control '+table+'_amount" name="other_charges_amount[]">';
  remove.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>';

  const other_charges = '.'+table+'_select';
  const other_charges_route =  '/settings/other_charges/search' ;
  const table_other_charges = table == 'other_charges' ? 'charges' : 'discount';
      select2Initialized(other_charges,other_charges_route, table_other_charges);

}

function removeTr(element){
  const other_charges = $(element).closest('tr').find('.inventory_other_charges_id').val();
  const discount = $(element).closest('tr').find('.discount_id').val();
  // console.log(discount);
  typeof other_charges != 'undefined' ? $('#other_charges_body').append('<input type="hidden" name="deleted_other_charges[]" value="'+other_charges+'" />') : "";
  typeof discount != 'undefined' ? $('#discount_body').append('<input type="hidden" name="deleted_other_charges[]" value="'+discount+'" />') : "";
  $(element).closest('tr').remove();

  setDiscount();
  setOtherCharges();
}

function setOtherCharges(){
  const other_charges_amount = document.querySelectorAll('.other_charges_amount');
  const other_charges = document.getElementById('other_charges');
  let total = 0;
  for(let i = 0; i < other_charges_amount.length; i++){
    // console.log(other_charges_amount[i]);
      total += other_charges_amount[i].value != '' ? parseFloat(other_charges_amount[i].value) : 0;
  }
  other_charges.value = total.toFixed(2);
  setNetProceeds();
}

function setDiscount(){
  const discount_amount = document.querySelectorAll('.discount_amount');
  const discount = document.getElementById('discount');
  let total = 0;
  for(let i = 0; i < discount_amount.length; i++){
    // console.log(other_charges_amount[i]);
      total += discount_amount[i].value != '' ? parseFloat(discount_amount[i].value) : 0;
  }
  discount.value = total.toFixed(2);
  setNetProceeds();
}

function renewForm(event, element){
  event.preventDefault();
  $('.error_message').css('display', 'none');
  $('.form-group').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/pawn/renew' : '/pawn_auction/pawn/renew/'+id; 
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
        if(data.success){
           window.location.href = data.link;
          return;
        }
        if(!data.auth_code_error){
          window.scrollTo(0, 0);
          alert_message('Danger', data.status);
          return;
        }

        window.scrollTo(0, 0);
        alert_message('Danger', 'Error occured. Pleasse contact administrator');

      },
      error: (data) => {
        const error = data.responseJSON.errors;
        // window.scrollTo(0, 0);
        let first = true; // to get the first loop index
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;
              // $('.transaction_date_error').find(':input').focus();
              if(first){
                $('.'+errorValue+'_error').find(':input').focus();
                first = false;
              }
              $('.'+errorValue+'_error').addClass('has-error is-focused');
              $('.'+errorValue+'_error').append('<label class="text-danger error_message">'+error[errorValue][0]+'</label>');


        }
      }
    });
};


function redeemForm(event, element){
  event.preventDefault();
  $('.error_message').css('display', 'none');
  $('.form-group').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  // console.log(id);
  const url = id == null ? '/pawn_auction/pawn/redeem' : '/pawn_auction/pawn/redeem/'+id; 
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
        if(data.success){
          window.location.href = data.link;
          return;
        }
        if(!data.auth_code_error){
          window.scrollTo(0, 0);
          alert_message('Danger', data.status);
          return;
        }
        window.scrollTo(0, 0);
        alert_message('Danger', 'Error occured. Pleasse contact administrator');
      },
      error: (data) => {
        const error = data.responseJSON.errors;
        // window.scrollTo(0, 0);
        let first = true;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;

            if(first){
              $('.'+errorValue+'_error').find(':input').focus();
              first = false;
            }
            $('.'+errorValue+'_error').addClass('has-error is-focused');
            $('.'+errorValue+'_error').append('<label class="text-danger error_message">'+error[errorValue][0]+'</label>');

        }
      }
    });
};


function repawnForm(event, element){
  event.preventDefault();
  $('.error_message').css('display', 'none');
  $('.input').removeClass('has-error is-focused');
  const form_data = new FormData(element);
  const id = form_data.get('id');
  $('.alert_error').remove();
  // console.log(id);
  const url = id == null ? '/pawn_auction/pawn/repawn' : '/pawn_auction/pawn/repawn/'+id; 
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
        // console.log(data);
        if(data.success){
          window.location.href = data.link;
          return;
        }
        if(!data.auth_code_error){
          window.scrollTo(0, 0);
          alert_message('Danger', data.status);
          return;
        }
        window.scrollTo(0, 0);
        alert_message('Danger', 'Error occured. Pleasse contact administrator');

      },
      error: (data) => {
        const error = data.responseJSON.errors;
        // window.scrollTo(0, 0);
        let first = true;
        for(let errorValue in error){
          if (!error.hasOwnProperty(errorValue)) continue;

            if(first){
              $('.'+errorValue.replace(/\./g, '_')+'_error').find(':input').focus();
              first = false;
            }
             if(/\.|[0-9]/g.test(errorValue)){
              $('.'+errorValue.replace(/\./g, '_')+'_error').addClass('has-error is-focused');
             }else{
              $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
              $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');
             }

             const alert_error = '<div class="col-xl-8 col-lg-12 col-md-12 mx-auto alert_error">'+
             '<div class="alert alert-danger alert-dismissible fade show text-center" style="font-size:15px" role="alert">'+
                 '<strong>Error!</strong> '+error[errorValue][0].replace(/\.|[0-9]/g, '')+
                     '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                         '<span aria-hidden="true">&times;</span>'+
                     '</button>'+
             '</div>'+
          '</div>';
             const count = errorValue.match(/\d+/g);
            if(count !== null){
              $( alert_error ).insertBefore('.table_'+count[0]+'_error');

            }
        }
      }
    });
};

function noticeListingForm(event, element){
  event.preventDefault();
  let checkbox = $("input:checkbox[name=notice]:checked").map(function(){return $(this).val()}).get();
    const form_data = new FormData(element);
    form_data.append('id', JSON.stringify(checkbox));
    const url = '/reports/notice_listing/store';

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
        // location.reload();
        window.location.href = data.link;
      },
      error: (data) => {

      }
    });
};


function auctionForm(event, element){
  event.preventDefault();
    let checkbox = $("input:checkbox[name=auction]:checked").map(function(){return $(this).val()}).get();
    // console.log(checkbox.length);
    if(checkbox.length == 0){
      return false;
    }
    const form_data = new FormData(element);
    form_data.append('id', JSON.stringify(checkbox));
    const url = '/pawn_auction/auction';
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
        // location.reload();
        if(data.success){
          location.reload();


        }
      },
      error: (data) => {

      }
    });
};

function singleNoticeForm(event, element){
  event.preventDefault();
    const form_data = new FormData(element);
    const url = '/reports/notice_listing/single_store';

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
        // location.reload();
        $('.notice').popover('hide');
        tableFunction();
      },
      error: (data) => {
      }
    });
};