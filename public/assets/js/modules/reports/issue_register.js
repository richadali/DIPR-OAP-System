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
    var issueRegisterTable  = $('.issueRegister-table').DataTable({               
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
      url: "/issue_register-view-data",
      success: function (response) {
        console.log(response);
          var table = '';
          $(".issueRegister-table tbody").empty();
          $.each(response, function (indexInArray, advertisement) { 

            // var newspaperNames = advertisement.assigned_news.map(function (news) {
            //     return news.empanelled.news_name;
            // }).join(', ');

            var issueDate = formatDate(advertisement.issue_date);
            var ref_date = formatDate(advertisement.ref_date);
            if(advertisement.remarks == null)
                remarks='';
            else
                remarks = advertisement.remarks;
            table += '<tr>'+
               '<td class="text-center">'+ (indexInArray+1)+ '</td>'+
               '<td class="text-center">'+ issueDate + '</td>'+
               '<td class="text-center">'+ advertisement.hod+ '</td>'+
               '<td class="text-center">'+ advertisement.ref_no + ' Dt. '+ ref_date +'</td>'+
               '<td class="text-center">'+ remarks + '</td></tr>';
          });
            $(".issueRegister-table tbody").append(table);
           
        }
      });
      $(document).on('click', '.btn-get-issue-register', function () {
        $('.loader-overlay').show();
    
        setTimeout(function () {
            $('.loader-overlay').hide();
        }, 500);
    });


    $(document).on('click', '.btn-get-issue-register', function () {
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
            url: '/get_issue_register', 
            data: {
                from: from,
                to: to,
            }, 
            dataType: 'json',
            success: function (data) {
                $('.loader-overlay').hide();
                issueRegisterTable.clear();
                if (data.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data Found',
                        text: 'There is no data available for the selected dates.',
                        confirmButtonText: 'OK'
                    });
                    issueRegisterTable.clear().draw();
                } else {
                    populateIssueRegisterTable(data,from,to);
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
    
    function populateIssueRegisterTable(response,from,to) {
        issueRegisterTable.clear();
        $.each(response, function (indexInArray, advertisement) { 
            
        //   var newspaperNames = advertisement.assigned_news.map(function (news) {
        //       return news.empanelled.news_name;
        //   }).join(', ');
          var issueDate = formatDate(advertisement.issue_date);
          var ref_date = formatDate(advertisement.ref_date);
          if(advertisement.remarks == null)
              remarks='';
          else
              remarks = advertisement.remarks;
            var newRow = $('<tr>').append(
                $('<td class="text-center">').text(indexInArray + 1),
                $('<td class="text-center">').text(issueDate),
                $('<td class="text-center">').text(advertisement.hod),
                $('<td class="text-center">').text(advertisement.ref_no + ' Dt. ' + ref_date),
                $('<td class="text-center">').text(remarks)
            );
            issueRegisterTable.row.add(newRow);
        });
        
        issueRegisterTable.draw();

        var jsonData = JSON.stringify(response);

        // Encode the JSON string to make it safe for the URL
        var encodedData = encodeURIComponent(jsonData);
        var printButton = '<br><a href="/reports/print_issue_register/' + from + '/'+to+'" style="color: white; font-size: 15px;"  class="btn btn-secondary issue_register" target="_blank" >Print</a>';
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
        // $.ajax({
        //     type: 'POST',
        //     url: '/reports/print_issue_register/',
        //     data: {
        //         from: from,
        //         to: to,
        //     },
        //     success: function (response) {
        //         $('#btn-generate-pdf').trigger('click');
        //     },
        //     error: function (error) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Error',
        //             text: 'An error occurred while generating the PDF. Please try again later.',
        //             confirmButtonText: 'OK'
        //         });
        //     }
        // });
    });
});