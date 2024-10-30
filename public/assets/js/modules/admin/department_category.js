$(document).ready(function () {

    // Reset form when switching tabs
    $(document).on('click', '#add-category-tab', function (e) {
        $('#department-category-form').trigger("reset");
        $("#id").val('');
    });

    // Setup AJAX for CSRF token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Fetch and display department categories
    $.ajax({
        type: "POST",
        url: "/department-category-view-data",
        success: function (response) {
            console.log(response);
            var table = '';
            $(".user-table tbody").empty();
            $.each(response, function (indexInArray, valueOfElement) {
                table += '<tr>' +
                    '<td class="text-center">' + ++indexInArray + '</td>' +
                    '<td class="text-center">' + valueOfElement.category_name + '</td>';
                table += '<td class="text-center"><span style="color:darkblue;" data-id="' + valueOfElement.id + '" class="icon ri-edit-2-fill department-category-edit"></span> &nbsp; &nbsp;<span style="color:red;" data-id="' + valueOfElement.id + '" class="icon ri-chat-delete-fill department-category-delete"></span></td>' +
                    '</tr>';
            });
            $(".user-table tbody").append(table);
            $('.user-table').DataTable({
                destroy: true,
                processing: true,
                select: true,
                paging: true,
                lengthChange: true,
                searching: true,
                info: false,
                responsive: true,
                autoWidth: false
            });
        }
    });

    // Handle form submission for storing and updating department categories
    $('#department-category-form').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/department-category-store-data',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data['flag'] == 'Y') { // Success for creating new category
                    $(".table_msg1").show();
                    $('.table_msg1').delay(2000).fadeOut();
                    $('#department-category-form').trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data['flag'] == 'YY') { // Success for editing a category
                    $(".table_msg5").show();
                    $('.table_msg5').delay(2000).fadeOut();
                    $('#department-category-form').trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else if (data['flag'] == 'N') { // Error while creating new category
                    $(".table_msg2").show();
                    $('.table_msg2').delay(2000).fadeOut();
                    $('#department-category-form').trigger("reset");
                } else if (data['flag'] == 'VE') { // Validation Errors
                    $(".table_msg2").show();
                    $('.table_msg2').delay(2000).fadeOut();
                    $('#department-category-form').trigger("reset");
                } else if (data['flag'] == 'NN') { // Error while editing a category
                    $(".table_msg6").show();
                    $('.table_msg6').delay(2000).fadeOut();
                    $('#department-category-form').trigger("reset");
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
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

    // Edit category
    $(document).on('click', '.department-category-edit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/department-category-show-data",
            data: { id },
            cache: false,
            success: function (data) {
                $('#department-category-form').trigger("reset");
                $("#id").val(data[0]['id']);
                $("#category_name").val(data[0]['category_name']);
                $("#add-category-tab").tab('show');
            }
        });
    });

    // Delete category
    $(document).on('click', '.department-category-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/department-category-delete-data",
            data: { id },
            cache: false,
            success: function (data) {
                if (data['flag'] == 'Y') {
                    $(".table_msg3").show();
                    $('.table_msg3').delay(5000).fadeOut();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    $(".table_msg4").show();
                    $('.table_msg4').delay(1000).fadeOut();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
            }
        });
    });

});
