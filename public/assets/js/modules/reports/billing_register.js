$(document).ready(function () {
  // Initialize Select2
  $(".newspaper").select2();
  $(".department").select2();

  // Initialize Datepicker
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

  // Date range validation
  $('#to').on('change', function () {
      var fromDate = new Date($('#from').val());
      var toDate = new Date($('#to').val());

      if (toDate < fromDate) {
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

      if (toDate !== '' && toDate < fromDate) {
          $('#to').val('');
          Swal.fire({
              icon: 'error',
              title: 'Invalid Date Range',
              text: 'The "To" date cannot be before the "From" date.',
              confirmButtonText: 'OK'
          });
      }
  });

  function formatDate(f_date) {
      var f_date = new Date(f_date);
      var formattedDate = $.datepicker.formatDate('dd-mm-yy', f_date);
      return formattedDate;
  }

  // Set up CSRF token for AJAX requests
  $.ajaxSetup({
      headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
  });

  // Initialize DataTable
  var billingRegisterTable = $(".billingRegister-table").DataTable({
      destroy: true,
      processing: true,
      select: true,
      paging: true,
      lengthChange: true,
      searching: true,
      info: false,
      responsive: true,
      autoWidth: false,
  });

  // Fetch and populate data
  $(document).on('click', '.btn-get-billing-register', function () {
      $('.loader-overlay').show();
      var from = $('#from').val();
      var to = $('#to').val();
      var department = $('#department').val();
      var newspaper = $('#newspaper').val();

      if (from === '' || to === '') {      
          var currentYear = new Date().getFullYear();
          from = '01-01-' + currentYear;
          to = '31-12-' + currentYear;
      }

      $.ajax({
          type: 'POST',
          url: '/get_billing_register', 
          data: {
              from: from,
              to: to,
              department: department,
              newspaper: newspaper,
          }, 
          dataType: 'json',
          success: function (data) {
            console.log(data)
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
                  populateBillingRegisterTable(data, from, to);
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

  function populateBillingRegisterTable(response, from, to) {
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
              $('<td class="text-center">').text(advertisement.dept_name),
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
      var printButton = '<a href="/reports/print_billing_register/' + from + '/' + to + '" style="color: black; font-size: 20px;" class="billing_register" target="_blank">Print</a>';
      $("#printButtonPlaceholder").html(printButton);
  }

  $(document).on('click', '.btn-print', function () {
      var from = $('#from').val();
      var to = $('#to').val();
      if (from === '' || to === '') {      
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Please enter the dates',
              confirmButtonText: 'OK'
          });
          return; // Changed from exit(1) to return
      }
  });
});
