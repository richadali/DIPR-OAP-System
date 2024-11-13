$(document).ready(function () {
    // Initialize DatePickers
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

    // Validate date range
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

        if (toDate && toDate < fromDate) {
            $("#to").val("");
            Swal.fire({
                icon: "error",
                title: "Invalid Date Range",
                text: 'The "To" date cannot be before the "From" date.',
                confirmButtonText: "OK",
            });
        }
    });

    // Format date function
    function formatDate(f_date) {
        var f_date = new Date(f_date);
        return $.datepicker.formatDate("dd-mm-yy", f_date);
    }

    // Initialize DataTable
    var issueRegisterTable = $(".issueRegisterTable").DataTable({
        processing: true,
        select: true,
        paging: true,
        lengthChange: true,
        searching: true,
        info: false,
        responsive: true,
        autoWidth: false,
    });

    // Set up CSRF for AJAX
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Load initial table data
    function loadIssueRegisterData() {
        $.ajax({
            type: "POST",
            url: "/issue_register-view-data",
            success: function (response) {
                populateIssueRegisterTable(response);
            },
        });
    }
    loadIssueRegisterData();

    // Populate the DataTable with data
    function populateIssueRegisterTable(response) {
        issueRegisterTable.clear();
        $.each(response, function (indexInArray, advertisement) {
            var issueDate = formatDate(advertisement.issue_date);
            var ref_date = formatDate(advertisement.ref_date);
            var remarks = advertisement.remarks || "";
            var newRow = $("<tr>").append(
                $('<td class="text-center">').text(advertisement.mipr_no),
                $('<td class="text-center">').text(issueDate),
                $('<td class="text-center">').text(
                    advertisement.department.dept_name
                ),
                $('<td class="text-center">').text(
                    advertisement.ref_no + " Dt. " + ref_date
                ),
                $('<td class="text-center">').text(remarks)
            );
            issueRegisterTable.row.add(newRow);
        });
        issueRegisterTable.draw();
    }

    // Fetch filtered data on button click
    $(document).on("click", ".btn-get-issue-register", function () {
        $(".loader-overlay").show();
        var from = $("#from").val();
        var to = $("#to").val();

        if (!from || !to) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Please enter the dates",
                confirmButtonText: "OK",
            });
            $(".loader-overlay").hide();
            return;
        }

        $.ajax({
            type: "POST",
            url: "/get_issue_register",
            data: { from: from, to: to },
            dataType: "json",
            success: function (data) {
                $(".loader-overlay").hide();
                if (data.length === 0) {
                    Swal.fire({
                        icon: "info",
                        title: "No Data Found",
                        text: "There is no data available for the selected dates.",
                        confirmButtonText: "OK",
                    });
                } else {
                    populateIssueRegisterTable(data);
                    createPrintButton(from, to);
                }
            },
            error: function (error) {
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

    // Function to create a Print button link with date filters
    function createPrintButton(from, to) {
        var printButton =
            '<br><a href="/reports/print_issue_register/' +
            from +
            "/" +
            to +
            '" style="color: white; font-size: 15px;" class="btn btn-secondary issue_register" target="_blank">Print</a>';
        var excelButton =
            '<a href="/reports/export_issue_register/' +
            from +
            "/" +
            to +
            '" style="color: white; font-size: 15px;" class="btn btn-success issue_register" target="_blank">Export to Excel</a>';

        $("#printButtonPlaceholder").html(printButton + " " + excelButton);
    }
});
