$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Fetch and display MIPR File Numbers
    $.ajax({
        type: "POST",
        url: "/mipr-file-no-view-data",
        success: function (response) {
            console.log(response);
            var table = '';
            $(".user-table tbody").empty();
            $.each(response, function (indexInArray, valueOfElement) {
                table += '<tr>' +
                    '<td class="text-center">' + ++indexInArray + '</td>' +
                    '<td class="text-center">' + valueOfElement.mipr_file_no + '</td>';
                table += '<td class="text-center"><span style="color:darkblue;" data-id="' + valueOfElement.id + '" class="icon ri-edit-2-fill mipr-file-no-edit"></span></td>'
                '</tr>';
            });
            $(".user-table tbody").append(table);
            $('.user-table').DataTable({
                destroy: true,
                processing: true,
                select: true,
                paging: false,
                lengthChange: true,
                searching: false,
                info: false,
                responsive: true,
                autoWidth: false
            });
        }
    });

    // Handle form submission for editing MIPR File Number
    $('#mipr-file-no-form').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/mipr-file-no-store-data',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data['flag'] == 'YY') { // Success for editing MIPR file number
                    $(".table_msg5").show();
                    $('.table_msg5').delay(2000).fadeOut();
                    $('#mipr-file-no-form').trigger("reset");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
                else if (data['flag'] == 'NN') { // Error while editing MIPR file number
                    $(".table_msg6").show();
                    $('.table_msg6').delay(2000).fadeOut();
                    $('#mipr-file-no-form').trigger("reset");
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

    // Handle clicking the edit icon
    $(document).on('click', '.mipr-file-no-edit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/mipr-file-no-show-data",
            data: { id },
            cache: false,
            success: function (data) {
                $('#mipr-file-no-form').trigger("reset");
                $("#id").val(data[0]['id']);
                $("#mipr_file_no").val(data[0]['mipr_file_no']);
                $('#disablebackdrop').modal('show');
            }
        });
    });

});
