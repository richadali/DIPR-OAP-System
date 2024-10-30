$(document).ready(function () {
    $(document).on("click", "#add-department-tab", function (e) {
        $("#department-form").trigger("reset");
        $("#id").val("");
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "POST",
        url: "/department-view-data",
        success: function (response) {
            console.log(response);
            var table = "";
            $(".department-table tbody").empty();
            $.each(response, function (indexInArray, valueOfElement) {
                table +=
                    "<tr>" +
                    '<td class="text-center">' +
                    ++indexInArray +
                    "</td>" +
                    '<td class="text-center">' +
                    valueOfElement.dept_name +
                    "</td>" +
                    '<td class="text-center">' +
                    valueOfElement.category.category_name +
                    "</td>";
                table +=
                    '<td class="text-center"><span style="color:darkblue;" data-id="' +
                    valueOfElement.id +
                    '" class="icon ri-edit-2-fill department-edit"></span> &nbsp; &nbsp;<span style="color:red;" data-id="' +
                    valueOfElement.id +
                    '" class="icon ri-chat-delete-fill department-delete"></span> </td>' +
                    "</tr>";
            });
            $(".department-table tbody").append(table);
            $(".department-table").DataTable({
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

    $("#department-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "/department-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "Y") {
                    
                    $(".table_msg1").show();
                    $(".table_msg1").delay(2000).fadeOut();
                    $("#department-form").trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data["flag"] == "YY") {
                    
                    $(".table_msg5").show();
                    $(".table_msg5").delay(2000).fadeOut();
                    $("#department-form").trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data["flag"] == "N") {
                    
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#department-form").trigger("reset");
                } else if (data["flag"] == "VE") {
                    
                    $(".table_msg2").show();
                    $(".table_msg2").delay(2000).fadeOut();
                    $("#department-form").trigger("reset");
                } else if (data["flag"] == "NN") {
                    
                    $(".table_msg6").show();
                    $(".table_msg6").delay(2000).fadeOut();
                    $("#department-form").trigger("reset");
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

    $(document).on("click", ".department-edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/department-show-data",
            data: { id },
            cache: false,
            success: function (data) {
                $("#department-form").trigger("reset");
                $("#id").val(data[0]["id"]);
                $("#dept_name").val(data[0]["dept_name"]);
                $("#category_id").val(data[0]["category_id"]);
                $("#add-department-tab").tab("show");
            },
        });
    });

    $(document).on("click", ".department-delete", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/department-delete-data",
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
