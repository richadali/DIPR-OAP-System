$(document).ready(function () {
    $(".select2").select2();

    $(document).on("click", "#users-tab", function (e) {
        $("#bill-form").trigger("reset");
        $("#id").val("");
    });

    $(document).ready(function () {
        $("#bill_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true,
        });
    });

    function formatDateFromDB(f_date) {
        var f_date = new Date(f_date);
        var formattedDate = $.datepicker.formatDate("dd-mm-yy", f_date);
        return formattedDate;
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "POST",
        url: "/bill-view-data",
        success: function (response) {
            console.log(response);
            var table = "";
            $(".bill-table tbody").empty();
            $.each(response, function (indexInArray, bill) {
                var f = "";
                if (bill.payment_by != "D") {
                    f =
                        '<a href="/reports/forwarding_letter/' +
                        bill.id +
                        "?newspaper=" +
                        encodeURIComponent(bill.news_name) +
                        '" style="color: black; font-size: 20px;" data-id="' +
                        bill.id +
                        '" class="icon ri-download-fill release_order" target="_blank"></a>';
                }

                var billDate = formatDate(bill.bill_date);
                table +=
                    "<tr>" +
                    '<td class="text-center">' +
                    (indexInArray + 1) +
                    "</td>" +
                    '<td class="text-center">' +
                    bill.mipr_no +
                    "</td>" +
                    '<td class="text-center">' +
                    bill.dept_name +
                    "</td>" +
                    '<td class="text-center">' +
                    bill.news_name + // Each newspaper will now be a separate row
                    "</td>" +
                    '<td class="text-center">' +
                    bill.bill_no +
                    "</td>" +
                    '<td class="text-center">' +
                    billDate +
                    "</td>";

                table +=
                    '<td class="text-center"><span style="color:darkblue;" data-id="' +
                    bill.id +
                    '"class="icon ri-edit-2-fill bill-edit"></span> &nbsp; &nbsp;<span style="color:red;font-size:20px;" data-id="' +
                    bill.id +
                    '" class="icon ri-close-line bill-delete"></span> &nbsp; &nbsp;  </td>' +
                    "<td align=center>" +
                    f +
                    "</td></tr>";
            });
            $(".bill-table tbody").append(table);
            $(".bill-table").DataTable({
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
        },
    });

    function formatDate(dateString) {
        var date = new Date(dateString);
        var day = String(date.getDate()).padStart(2, "0");
        var month = String(date.getMonth() + 1).padStart(2, "0");
        var year = date.getFullYear();
        return day + "-" + month + "-" + year;
    }

    $("#ad_id").on("change", function () {
        getNewspaper();
        getDeptLetterNo();
    });

    function getDeptLetterNo() {
        var ad_id = $("#ad_id").val();

        $.ajax({
            type: "POST",
            url: "/bill-get-dept-letter-no",
            data: {
                ad_id: ad_id,
            },
            success: function (response) {
                $("#dept_letter_no").val(response.dept_letter_no);
            },
        });
    }

    function getNewspaper() {
        var ad_id = $("#ad_id").val();

        $.ajax({
            type: "POST",
            url: "/bill-get-newspaper",
            data: {
                ad_id: ad_id,
            },
            success: function (response) {
                $("#empanelled_id").empty();
                $("#empanelled_id").append(
                    '<option value="" disabled selected>--Select Organization--</option>'
                );
                // Populate options with news_name from empanelled relationship
                $.each(response.assigned_news, function (index, news) {
                    $("#empanelled_id").append(
                        '<option value="' +
                            news.empanelled.id +
                            '">' +
                            news.empanelled.news_name +
                            "</option>"
                    );
                });
            },
        });
    }

    $("#empanelled_id").on("change", function () {
        var ad_id = $("#ad_id").val();
        var empanelled_id = $("#empanelled_id").val();
        $.ajax({
            type: "POST",
            url: "/get_bill_details",
            data: {
                ad_id: ad_id,
                empanelled_id: empanelled_id,
            },
            success: function (response) {
                if (response != "") {
                    Swal.fire({
                        icon: "warning",
                        title:
                            "Bill already exist with bill no: " +
                            response[0]["bill_no"],
                        confirmButtonText: "OK",
                    });
                    getNewspaper();
                }
            },
        });
    });

    const amountInput = document.getElementById("amount");
    const gstRateSelect = document.getElementById("gst_rate");
    const totalAmountInput = document.getElementById("total_amount");

    function calculateTotalAmount() {
        const amount = parseFloat(amountInput.value) || 0;
        const gstRate = gstRateSelect.value;

        let totalAmount = amount;
        if (gstRate && gstRate !== "NA") {
            const gstPercentage = parseFloat(gstRate) || 0;
            totalAmount += (amount * gstPercentage) / 100;
        }

        totalAmountInput.value = totalAmount.toFixed(2);
    }

    // Add event listeners to trigger calculation
    amountInput.addEventListener("input", calculateTotalAmount);
    gstRateSelect.addEventListener("change", calculateTotalAmount);

    const modalAmountInput = document.getElementById("modal_amount");
    const modalGstRateSelect = document.getElementById("modal_gst_rate");
    const modalTotalAmountInput = document.getElementById("modal_total_amount");

    // Function to calculate the total amount (including GST) in the modal
    function calculateModalTotalAmount() {
        const modalAmount = parseFloat(modalAmountInput.value) || 0; // Base amount before GST
        const modalGstRate = modalGstRateSelect.value; // Selected GST rate

        let modalTotalAmount = modalAmount;
        if (modalGstRate && modalGstRate !== "NA") {
            const gstPercentage = parseFloat(modalGstRate) || 0;
            modalTotalAmount += (modalAmount * gstPercentage) / 100; // Add GST to the base amount
        }

        modalTotalAmountInput.value = modalTotalAmount.toFixed(2); // Update total amount field
    }

    // Add event listeners to the modal fields for changes
    modalAmountInput.addEventListener("input", calculateModalTotalAmount);
    modalGstRateSelect.addEventListener("change", calculateModalTotalAmount);

    $("#bill-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/bill-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "Y" || data["flag"] == "YY") {
                    //Succcess for creation of new user
                    {
                        if (data["flag"] == "Y") {
                            $(".table_msg1").show();
                            $(".table_msg1").delay(2000).fadeOut();
                            // $('#table-form').trigger("reset");
                        } else if (data["flag"] == "YY") {
                            $(".table_msg5").show();
                            $(".table_msg5").delay(2000).fadeOut();
                            // $('#table-form').trigger("reset");
                        }
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                } else if (data["flag"] == "N") {
                    //Error while creation of new user
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                } else if (data["flag"] == "VE") {
                    //Validation Errors
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                } else if (data["flag"] == "NN") {
                    //Error while editing an Ad
                    $(".table_msg6").show();
                    $(".table_msg6").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        var fieldElement = $('[name="' + field + '"]');
                        var errorMessage = $(
                            '<span class="error">' + messages[0] + "</span>"
                        );
                        fieldElement.after(errorMessage);
                        errorMessage.css({
                            color: "red",
                            fontSize: "12px",
                        });
                    });
                }
            },
        });
    });

    $("#edit-bill-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/bill-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "Y" || data["flag"] == "YY") {
                    //Succcess for creation of new user
                    {
                        if (data["flag"] == "Y") {
                            $(".table_msg1").show();
                            $(".table_msg1").delay(2000).fadeOut();
                            // $('#table-form').trigger("reset");
                        } else if (data["flag"] == "YY") {
                            $(".table_msg5").show();
                            $(".table_msg5").delay(2000).fadeOut();
                            // $('#table-form').trigger("reset");
                        }
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                } else if (data["flag"] == "N") {
                    //Error while creation of new user
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                } else if (data["flag"] == "VE") {
                    //Validation Errors
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                } else if (data["flag"] == "NN") {
                    //Error while editing an Ad
                    $(".table_msg6").show();
                    $(".table_msg6").delay(2000).fadeOut();
                    $("#table-form").trigger("reset");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        var fieldElement = $('[name="' + field + '"]');
                        var errorMessage = $(
                            '<span class="error">' + messages[0] + "</span>"
                        );
                        fieldElement.after(errorMessage);
                        errorMessage.css({
                            color: "red",
                            fontSize: "12px",
                        });
                    });
                }
            },
        });
    });

    $(document).on("click", ".bill-edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");

        $.ajax({
            type: "POST",
            url: "/bill-edit-data",
            data: { id },
            cache: false,
            success: function (data) {
                console.log(data);

                // Reset any previous form data
                $("#edit-bill-form").trigger("reset");

                // Fill modal with bill details (excluding reference_no and empanelled)
                $("#modal_id").val(data[0]["id"]);
                document.getElementById("modal_ad_id_display").textContent =
                    data[0]["advertisement"]["ref_no"];
                $("#modal_ad_id").val(data[0]["advertisement"]["ref_no"]);
                $("#modal_bill_no").val(data[0]["bill_no"]);
                $("#modal_bill_date").val(
                    formatDateFromDB(data[0]["bill_date"])
                );

                var gstRate = data[0]["gst_rate"];
                if (gstRate === null || gstRate === undefined) {
                    gstRate = "NA";
                }
                $("#modal_gst_rate").val(gstRate);

                // Populate total amount
                var totalAmount = parseFloat(data[0]["total_amount"]);
                $("#modal_total_amount").val(totalAmount.toFixed(2));

                // Calculate and populate amount before GST if GST rate is not NA
                if (gstRate !== "NA") {
                    gstRate = parseFloat(gstRate);
                    var amountBeforeGST = totalAmount / (1 + gstRate / 100);
                    $("#modal_amount").val(amountBeforeGST.toFixed(2)); // rounding to 2 decimal places
                } else {
                    $("#modal_amount").val(totalAmount.toFixed(2)); // If GST is NA, set amount equal to total amount
                }

                $("#modal_bill_memo_no").val(data[0]["bill_memo_no"]);
                $("#editBillModal").modal("show");
            },
        });
    });

    $(document).on("click", ".bill-delete", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/bill-delete-data",
            data: { id },
            cache: false,
            success: function (data) {
                if (data["flag"] == "Y") {
                    $(".table_msg3").show();
                    $(".table_msg3").delay(5000).fadeOut();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    $(".table_msg4").show();
                    $(".table_msg4").delay(1000).fadeOut();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
            },
        });
    });

    $("#title").click(function (e) {
        $(".msg1").hide();
    });

    $("#body").click(function (e) {
        $(".msg2").hide();
    });
});

//---------------- VALIDATING FIELDS

$("#amount").on("input", function () {
    $(this).val(
        $(this)
            .val()
            .replace(/[^0-9.]/g, "")
    );
});

$("#modal_amount").on("input", function () {
    $(this).val(
        $(this)
            .val()
            .replace(/[^0-9.]/g, "")
    );
});
