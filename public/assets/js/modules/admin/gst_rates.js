$(document).ready(function () {
    $(document).on("click", "#gst-tab", function (e) {
        $("#gst-form").trigger("reset");
        $("#id").val("");
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var gst_id;

    // Fetch and display GST rates
    $.ajax({
        type: "POST",
        url: "/gst-rates-view-data",
        success: function (response) {
            console.log(response);
            var table = "";
            $(".gst-table tbody").empty();
            $.each(response, function (indexInArray, valueOfElement) {
                table +=
                    "<tr>" +
                    '<td class="text-center">' +
                    ++indexInArray +
                    "</td>" +
                    '<td class="text-center">' +
                    valueOfElement.rate +
                    "</td>";
                table +=
                    '<td class="text-center"><span style="color:darkblue;" data-id="' +
                    valueOfElement.id +
                    '" class="icon ri-edit-2-fill gst-edit"></span> &nbsp; &nbsp;<span style="color:red;" data-id="' +
                    valueOfElement.id +
                    '" class="icon ri-chat-delete-fill gst-delete"></span> </td>';
                ("</tr>");
            });
            $(".gst-table tbody").append(table);
            $(".gst-table").DataTable({
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

    // Handle form submission for adding or editing GST rates
    $("#gst-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/gst-rates-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "Y") {
                    // Success for new GST rate creation
                    $(".table_msg1").show();
                    $(".table_msg1").delay(2000).fadeOut();
                    $("#gst-form").trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data["flag"] == "YY") {
                    // Success for editing GST rate
                    $(".table_msg5").show();
                    $(".table_msg5").delay(2000).fadeOut();
                    $("#gst-form").trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data["flag"] == "N") {
                    // Error in creation of new GST rate
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#gst-form").trigger("reset");
                } else if (data["flag"] == "VE") {
                    // Validation Errors
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#gst-form").trigger("reset");
                } else if (data["flag"] == "NN") {
                    // Error in editing GST rate
                    $(".table_msg6").show();
                    $(".table_msg6").delay(2000).fadeOut();
                    $("#gst-form").trigger("reset");
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

    // Edit GST rate
    $(document).on("click", ".gst-edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/gst-rates-show-data",
            data: { id },
            cache: false,
            success: function (data) {
                $("#gst-form").trigger("reset");
                $("#id").val(data[0]["id"]);
                $("#gst_rate").val(data[0]["rate"]);
                // Switch to the Add/Edit GST Rate tab
                $("#gst-add-tab").tab("show"); // Change this line
            },
            error: function (xhr, status, error) {
                console.error("Error fetching GST rate data: ", error); // Log any errors
            },
        });
    });

    // Delete GST rate
    $(document).on("click", ".gst-delete", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/gst-rates-delete-data",
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
