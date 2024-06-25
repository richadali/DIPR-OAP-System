$(document).ready(function () {

    $('#advertisementType option[value="8"]').remove();

    $('#advertisementType').on('change', function() {
      if ($(this).val() == '7') {
        $('#ad-category-container').show();
        $('#ad_category').prop('disabled', false); 
    } else {
        $('#ad-category-container').hide();
        $('#ad_category').prop('disabled', true); 
        $('#ad_category').val('');
    }
  });

    $(document).on('click','#users-tab',function (e) { 
      $('#rates-form').trigger("reset");
      $("#id").val('');
    });
  
    $.ajaxSetup({
      headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    var user_id ;
  
    $.ajax({
      type: "POST",
      url: "/rates-view-data",
      success: function (response) {
        console.log(response)
          var table = '';
          $(".user-table tbody").empty();
          $.each(response, function (indexInArray, valueOfElement) { 
               var categoryName = valueOfElement.ad_category ? valueOfElement.ad_category.category_name : '-';
               table += '<tr>'+
               '<td class="text-center">'+ ++indexInArray+ '</td>'+
               '<td class="text-center">'+ valueOfElement.advertisement_type.name+'</td>'+
               '<td class="text-center">'+ categoryName  + ' </td>'+
               '<td class="text-center">'+ valueOfElement.amount+ '</td>'+
               '<td class="text-center">'+ valueOfElement.gst_rate+ '%</td>';
            //    '<td class="text-center">'+ valueOfElement.designation+ '</td>'+
            //    '<td class="text-center">'+ valueOfElement.email+ '</td>'+
            //    '<td class="text-center">'+ valueOfElement.role.role_name+ '</td>';
               
               table += '<td class="text-center"><span style="color:darkblue;" data-id="'+ valueOfElement.id+'"class="icon ri-edit-2-fill rate-edit"></span> &nbsp; &nbsp;<span style="color:red;" data-id="'+ valueOfElement.id+'" class="icon ri-chat-delete-fill rate-delete"></span> </td>'
               '</tr>';
          });
            $(".user-table tbody").append(table);
            $('.user-table').DataTable({               
              destroy:true,
              processing:true,
              select:true,
              paging:true,
              lengthChange:true,
              searching:true,
              info:false,
              responsive:true,
              autoWidth:false
          });
        }
      });
  
      $('#rate-form').on('submit', function(e) {
          e.preventDefault();
          var adCategory = $('#ad_category').val();
          var advertisementType = $('#advertisementType').val();
          var rate = $('#rate').val();
          if(adCategory==='')
          {
            $('#error-rate').text('The Advertisement Category must be selected');
          }
          if(advertisementType === '') {
            $('#error-rate').text('The Advertisement Type must be selected');
            return;
          }
          else
          {
            var formData = new FormData(this);
          $.ajax({
            type:'POST',
            url:'/rates-store-data',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
              console.log(data);
              if(data['flag']=='Y'){ //Succcess for creation of new user
                $(".table_msg1").show();
                $('.table_msg1').delay(2000).fadeOut();
                $('#table-form').trigger("reset");
                setTimeout(function(){
                  window.location.reload();
               }, 1500);
              }
              else if(data['flag']=='YY'){ //Succcess for editing an user
                $(".table_msg5").show();
                $('.table_msg5').delay(2000).fadeOut();
                $('#table-form').trigger("reset");
                setTimeout(function(){
                  window.location.reload();
               }, 1500);
              } 
              else if(data['flag']=='N'){  //Error while creation of new user
                $(".table_msg2").show();
                $('.table_msg2').delay(2000).fadeOut();
                $('#table-form').trigger("reset");
              }
              else if(data['flag']=='VE'){ //Validation Errors
                $(".table_msg2").show();
                $('.table_msg2').delay(2000).fadeOut();
                $('#table-form').trigger("reset");
              } 
             
              else if(data['flag']=='NN'){ //Error while editing an user
                $(".table_msg6").show();
                $('.table_msg6').delay(2000).fadeOut();
                $('#table-form').trigger("reset");
              } 
            },
            error: function(xhr, status, error){
              if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(field, messages) {
                    var fieldElement = $('[name="' + field + '"]');
                    var errorMessage = $('<span class="error">' + messages[0] + '</span>');
                    fieldElement.after(errorMessage);
                    errorMessage.css({
                      color: 'red',
                      fontSize: '12px'
                  });
                });
            }
           }
          });
          }
        
          
        });
        
      $(document).on('click','.rate-edit',function (e) { 
          e.preventDefault();
          var id = $(this).data('id');
  
          var value;
          $.ajax({
            type: "POST",
            url: "/rates-show-data",
            data: {id},
            cache:false,
            success: function(data) {
              $('#rate-form').trigger("reset");
              $("#id").val(data[0]['id']);
              $("#ad_category").val(data[0]['ad_category_id']).trigger('change').attr('readonly', true); 
              $("#ad_category_hidden").val(data[0]['ad_category_id']); 
              $("#advertisementType").val(data[0]['advertisement_type_id']).trigger('change').attr('readonly', true); 
              $("#advertisement_type_hidden").val(data[0]['advertisement_type_id']); 
              $("#rate").val(data[0]['amount']);
              $("#gst_rate").val(data[0]['gst_rate']);
              $("#users-tab").tab('show');
          }
          });
      });
  
      $(document).on('click','.rate-delete',function (e) { 
          e.preventDefault();
          var id = $(this).data('id');
          $.ajax({
            type: "POST",
            url: "/rates-delete-data",
            data: {id},
            cache:false,
            success:function(data){
              if(data['flag']=='Y'){
                $(".table_msg3").show();
                $('.table_msg3').delay(5000).fadeOut();
                setTimeout(function(){
                  window.location.reload();
               }, 1000);
  
              }
              else {
                $(".table_msg4").show();
                $('.table_msg4').delay(1000).fadeOut();
                setTimeout(function(){
                  window.location.reload();
               }, 1500);
              }
            }
          });
      });
  
      $("#title").click(function(e){
          $(".msg1").hide();
      });
  
      $("#body").click(function(e){
          $(".msg2").hide();
      });
    });