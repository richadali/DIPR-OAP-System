$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Fetch and display MIPR Numbers
    $.ajax({
        type: "POST",
        url: "/mipr-no-view-data",
        success: function (response) {
            console.log(response);
            var table = "";
            $(".user-table tbody").empty();
            $.each(response, function (indexInArray, valueOfElement) {
                table +=
                    "<tr>" +
                    '<td class="text-center">' +
                    ++indexInArray +
                    "</td>" +
                    '<td class="text-center">' +
                    valueOfElement.mipr_no +
                    "</td>";
                table +=
                    '<td class="text-center"><span style="color:darkblue;" data-id="' +
                    valueOfElement.mipr_no +
                    '" class="icon ri-edit-2-fill mipr-no-edit"></span></td>';
                ("</tr>");
            });
            $(".user-table tbody").append(table);
            $(".user-table").DataTable({
                destroy: true,
                processing: true,
                select: true,
                paging: false,
                lengthChange: true,
                searching: false,
                info: false,
                responsive: true,
                autoWidth: false,
            });
        },
    });

    // Handle form submission for editing MIPR Number
    $("#mipr-no-form").on("submit", function (e) {
        e.preventDefault();

        // Validate if mipr_no is 4 characters long
        var miprNo = $("#mipr_no").val();
        if (miprNo.length !== 4) {
            alert("MIPR Number must be exactly 4 characters long.");
            return;
        }

        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/mipr-no-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "YY") {
                    // Success for editing MIPR number
                    $(".table_msg5").show();
                    $(".table_msg5").delay(2000).fadeOut();
                    $("#mipr-no-form").trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data["flag"] == "NN") {
                    // Error while editing MIPR number
                    $(".table_msg6").show();
                    $(".table_msg6").delay(2000).fadeOut();
                    $("#mipr-no-form").trigger("reset");
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

    // Handle clicking the edit icon
    $(document).on("click", ".mipr-no-edit", function (e) {
        e.preventDefault();
        var miprNo = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/mipr-no-show-data",
            data: { mipr_no: miprNo },
            cache: false,
            success: function (data) {
                $("#mipr-no-form").trigger("reset");
                $("#mipr_no").val(data.mipr_no);
                $("#fin_year").val(data.fin_year);
                $("#disablebackdrop").modal("show");
            },
        });
    });
});
