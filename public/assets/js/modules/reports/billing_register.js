$(document).ready(function () {
    // Initialize Select2
    $(".newspaper").select2();
    $(".department").select2();

    // Initialize Datepicker
    $("#from").datepicker({
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true,
    });

    $("#to").datepicker({
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true,
    });

    $("#to").on("change", function () {
        var fromDate = new Date($("#from").val());
        var toDate = new Date($("#to").val());

        if (toDate < fromDate) {
            $("#to").val("");
            Swal.fire({
                icon: "error",
                title: "Invalid Date Range",
                text: 'The "To" date cannot be before the "From" date.',
                confirmButtonText: "OK",
            });
        }
    });

    $("#from").on("change", function () {
        var fromDate = new Date($("#from").val());
        var toDate = new Date($("#to").val());

        if (toDate !== "" && toDate < fromDate) {
            $("#to").val("");
            Swal.fire({
                icon: "error",
                title: "Invalid Date Range",
                text: 'The "To" date cannot be before the "From" date.',
                confirmButtonText: "OK",
            });
        }
    });

    $("#clearDates").on("click", function () {
        $("#from").val("");
        $("#to").val("");
    });

    function formatDate(f_date) {
        var f_date = new Date(f_date);
        var formattedDate = $.datepicker.formatDate("dd-mm-yy", f_date);
        return formattedDate;
    }

    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Load initial billing register data
    function loadBillingRegisterData() {
        $.ajax({
            type: "POST",
            url: "/billing_register-view-data",
            success: function (response) {
                console.log(response);
                populateBillingRegisterTable(response);
            },
        });
    }
    loadBillingRegisterData(); // Call function to load data initially

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
    $(document).on("click", ".btn-get-billing-register", function () {
        $(".loader-overlay").show();

        // Get values from input fields
        var from = $("#from").val();
        var to = $("#to").val();
        var department = $("#department").val();
        var newspaper = $("#newspaper").val();

        // Check if at least one field has a value
        if (
            from === "" &&
            to === "" &&
            (!department || department.length === 0) &&
            (!newspaper || newspaper.length === 0)
        ) {
            $(".loader-overlay").hide();

            Swal.fire({
                icon: "warning",
                title: "Input Required",
                text: "Please fill at least one of the fields: From Date, To Date, Department, or Organization.",
                confirmButtonText: "OK",
            });

            return; // Stop execution if no field is filled
        }

        // Set default dates if "From" and "To" are empty
        if (from === "" || to === "") {
            var today = new Date();
            var currentYear = today.getFullYear();
            var startYear, endYear;

            // Determine if we're currently in the first or second half of the financial year
            if (today.getMonth() + 1 >= 4) {
                // If it's April or later, the financial year started this year and ends next year
                startYear = currentYear;
                endYear = currentYear + 1;
            } else {
                // If it's before April, the financial year started last year and ends this year
                startYear = currentYear - 1;
                endYear = currentYear;
            }

            from = "01-04-" + startYear;
            to = "31-03-" + endYear;
        }

        // Proceed with AJAX request if validation passes
        $.ajax({
            type: "POST",
            url: "/get_billing_register",
            data: {
                from: from,
                to: to,
                department: department,
                newspaper: newspaper,
            },
            dataType: "json",
            success: function (data) {
                $(".loader-overlay").hide();
                billingRegisterTable.clear();
                if (data.length === 0) {
                    Swal.fire({
                        icon: "info",
                        title: "No Data Found",
                        text: "There is no data available for the selected criteria.",
                        confirmButtonText: "OK",
                    });
                    billingRegisterTable.clear().draw();
                } else {
                    populateBillingRegisterTable(data, from, to);
                    createPrintButton(from, to, department, newspaper); // Call to create print button
                }
            },
            error: function (error) {
                console.log("Error:", error);
                $(".loader-overlay").hide();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while fetching data. Please try again later.",
                    confirmButtonText: "OK",
                });
            },
        });
    });

    function populateBillingRegisterTable(response) {
        billingRegisterTable.clear();
        $.each(response, function (index, bill) {
            var advertisement = bill.advertisement;

            var newspaperName = bill.empanelled
                ? bill.empanelled.news_name
                : "N/A";
            var issue_date = formatDate(advertisement.issue_date);
            var billDate = formatDate(bill.bill_date);

            var newRow = $("<tr>").append(
                $('<td class="text-center">').text(index + 1),
                $('<td class="text-center">').text(
                    advertisement.department.dept_name || "N/A"
                ),
                $('<td class="text-center">').text(newspaperName),
                $('<td class="text-center">').text(
                    advertisement.mipr_no || "N/A"
                ),
                $('<td class="text-center">').text(issue_date || "N/A"),
                $('<td class="text-center">').text(bill.bill_no || "N/A"),
                $('<td class="text-center">').text(billDate || "N/A"),
                $('<td class="text-center">').text(bill.total_amount || "0.00")
            );

            billingRegisterTable.row.add(newRow);
        });

        billingRegisterTable.draw();
    }

    // Function to create a Print button link with date filters
    function createPrintButton(from, to, department, newspaper) {
        var printButton =
            '<br><a href="/reports/print_billing_register/' +
            encodeURIComponent(from || "") +
            "/" +
            encodeURIComponent(to || "") +
            "?department=" +
            encodeURIComponent(department || "") +
            "&newspaper=" +
            encodeURIComponent(newspaper || "") +
            '" style="color: white; font-size: 15px;" class="btn btn-secondary issue_register" target="_blank">Print</a>';

        var excelButton = "/reports/export_billing_register?";

        if (from) excelButton += "from=" + encodeURIComponent(from) + "&";
        if (to) excelButton += "to=" + encodeURIComponent(to) + "&";
        if (department)
            excelButton += "department=" + encodeURIComponent(department) + "&";
        if (newspaper)
            excelButton += "newspaper=" + encodeURIComponent(newspaper) + "&";

        excelButton = excelButton.slice(0, -1); // Remove the last '&'

        excelButton =
            '<a href="' +
            excelButton +
            '" style="color: white; font-size: 15px;" class="btn btn-success issue_register" target="_blank">Export to Excel</a>';

        $("#printButtonPlaceholder").html(printButton + " " + excelButton);
    }
});
