$(document).ready(function () {

    $(document).on('click','#users-tab',function (e) { 
      $('#page-info-form').trigger("reset");
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
      url: "/page-info-view-data",
      success: function (response) {
          console.log(response)
          var table = '';
          $(".user-table tbody").empty();
          $.each(response, function (indexInArray, valueOfElement) { 
               table += '<tr>'+
               '<td class="text-center">'+ ++indexInArray+ '</td>'+
               '<td class="text-center">'+ valueOfElement.page_info_name+ '</td>';
               table += '<td class="text-center"><span style="color:darkblue;" data-id="'+ valueOfElement.id+'"class="icon ri-edit-2-fill page-info-edit"></span> &nbsp; &nbsp;<span style="color:red;" data-id="'+ valueOfElement.id+'" class="icon ri-chat-delete-fill page-info-delete"></span> </td>'
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
  
      
      $('#page-info-form').on('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(this);
          $.ajax({
            type:'POST',
            url:'/page-info-store-data',
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
        });
        
      $(document).on('click','.page-info-edit',function (e) { 
          e.preventDefault();
          var id = $(this).data('id');
          var value;
          $.ajax({
            type: "POST",
            url: "/page-info-show-data",
            data: {id},
            cache:false,
            success:function(data){
              $('#page-info-form').trigger("reset");
              $("#id").val(data[0]['id']);
              $("#page_info").val(data[0]['page_info_name']);
              $("#users-tab").tab('show');
            }
          });
        });
  
        $(document).on('click','.page-info-delete',function (e) { 
          e.preventDefault();
          var id = $(this).data('id');
          $.ajax({
            type: "POST",
            url: "/page-info-delete-data",
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