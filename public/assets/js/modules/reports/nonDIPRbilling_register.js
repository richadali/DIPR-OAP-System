$(document).ready(function () {

    $("#from").datepicker({
          dateFormat: 'dd-mm-yy', 
          changeYear: true, 
          changeMonth: true 
    });

    $("#to").datepicker({
          dateFormat: 'dd-mm-yy', 
          changeYear: true, 
          changeMonth: true 
    });

    $('#to').on('change', function () {
        var fromDate = new Date($('#from').val());
        var toDate = new Date($('#to').val());
    
        // Check if the "to" date is before the "from" date
        if (toDate < fromDate) {
          // Reset the "to" date input and show an error message
          $('#to').val('');
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date Range',
            text: 'The "To" date cannot be before the "From" date.',
            confirmButtonText: 'OK'
          });
        }
      });
    $('#from').on('change', function () {
        var fromDate = new Date($('#from').val());
        var toDate = new Date($('#to').val());
    
        // Check if the "to" date is before the "from" date
        if (toDate !='' && toDate<fromDate) {
          // Reset the "to" date input and show an error message
          $('#to').val('');
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date Range',
            text: 'The "To" date cannot be before the "From" date.',
            confirmButtonText: 'OK'
          });
        }
      });

    function formatDate(f_date)
    {
      var f_date = new Date(f_date);
      var formattedDate = $.datepicker.formatDate('dd-mm-yy', f_date);
      return formattedDate;
    }

    $.ajaxSetup({
      headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    var billingRegisterTable  = $('.billingRegister-table').DataTable({               
        destroy:true,
        processing:true,
        select:true,
        paging:true,
        lengthChange:true,
        searching:true,
        info:false,
        responsive:true,
        autoWidth:false,
    });
  
    $.ajax({
      type: "POST",
      url: "/nonDIPR_register-view-data",
      success: function (response) {
        console.log(response);
          var table = '';
          $(".billingRegister-table tbody").empty();
          $.each(response, function (indexInArray, bill) { 
            var release_order_date = formatDate(bill.release_order_date);
            var bill_date = formatDate(bill.bill_date);

            table += '<tr>'+
               '<td class="text-center">'+ (indexInArray+1)+ '</td>'+
               '<td class="text-center">'+ bill.hod + '</td>'+
               '<td class="text-center">'+ bill.news_name+ '</td>'+
               '<td class="text-center">'+ bill.release_order_no + '</td>'+
               '<td class="text-center">'+ release_order_date+ '</td>'+
               '<td class="text-center">'+ bill.bill_no +'</td>'+
               '<td class="text-center">'+ bill_date + '</td>'+
               '<td class="text-center" >'+ bill.amount+ '</td>';

          });
            $(".billingRegister-table tbody").append(table);
           
        }
      });
      $(document).on('click', '.btn-get-billing-register', function () {
        $('.loader-overlay').show();
    
        setTimeout(function () {
            $('.loader-overlay').hide();
        }, 500);
    });


    $(document).on('click', '.btn-get-billing-register', function () {
        $('.loader-overlay').show();
        var from = $('#from').val();
        var to = $('#to').val();
        if(from == '' || to =='')
        {      
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter the dates',
                confirmButtonText: 'OK'
            });
            exit(1);
            
        }
        $.ajax({
            type: 'POST',
            url: '/get_nonDIPR_register', 
            data: {
                from: from,
                to: to,
            }, 
            dataType: 'json',
            success: function (data) {
                $('.loader-overlay').hide();
                billingRegisterTable.clear();
                if (data.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data Found',
                        text: 'There is no data available for the selected dates.',
                        confirmButtonText: 'OK'
                    });
                    billingRegisterTable.clear().draw();
                } else {
                    populateBillingRegisterTable(data,from,to);
                }
            },
            error: function (error) {
                console.log('Error:', error);
                $('.loader-overlay').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while fetching data. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    function populateBillingRegisterTable(response,from,to) {
        billingRegisterTable.clear();
        $.each(response, function (indexInArray, bill) { 
          
          var advertisement = bill.advertisement;
          var newspaperNames = advertisement.assigned_news.map(function (news) {
              return news.empanelled.news_name;
          }).join(', ');
          var release_order_date = formatDate(advertisement.release_order_date);
          var bill_date = formatDate(bill.bill_date);
         
            var newRow = $('<tr>').append(
                $('<td class="text-center">').text(indexInArray + 1),
                $('<td class="text-center">').text(advertisement.hod),
                $('<td class="text-center">').text(newspaperNames),
                $('<td class="text-center">').text(advertisement.release_order_no),
                $('<td class="text-center">').text(release_order_date),
                $('<td class="text-center">').text(bill.bill_no),
                $('<td class="text-center">').text(bill_date),
                $('<td class="text-center">').text(advertisement.amount)
            );
            billingRegisterTable.row.add(newRow);
        });
        
        billingRegisterTable.draw();

        var jsonData = JSON.stringify(response);

        // Encode the JSON string to make it safe for the URL
        var encodedData = encodeURIComponent(jsonData);
        var printButton = '<a href="/reports/print_nonDIPR_register/' + from + '/'+to+'" style="color: black; font-size: 20px;"  class=" billing_register" target="_blank" >Print</a>';
        $("#printButtonPlaceholder").html(printButton);
    }
    

    $(document).on('click', '.btn-print', function () {
        var from = $('#from').val();
        var to = $('#to').val();
        if(from == '' || to =='')
        {      
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter the dates',
                confirmButtonText: 'OK'
            });
            exit(1);
        }
    });
});