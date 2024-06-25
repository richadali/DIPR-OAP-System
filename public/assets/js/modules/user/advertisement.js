$(document).ready(function () {
    $(".select2").select2();


    $(document).on("click", "#users-tab", function (e) {
        $("#email").prop("disabled", false);
        $("#user-form").trigger("reset");
        $("#id").val("");
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $(document).ready(function () {
        $("#issue_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true,
        });

        $("#ref_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeYear: true,
            changeMonth: true,
        });

        $("#positively").multiDatesPicker({
            dateFormat: "dd-mm-yy",
            changeYear: false,
            changeMonth: true,
            minDate: 0,
            onSelect: function () {
                updateInsertions();
            },
            onClose: function () {
                updateInsertions();
            },
            onChangeMonthYear: function () {
                updateInsertions();
            },
        });

        function updateInsertions() {
            var selectedDates = $("#positively").val();
            var insertions = 0; // Start with 0 insertions
            if (selectedDates) {
                var datesArray = selectedDates.split(",");
                insertions = datesArray.length;
            }
            console.log("Number of insertions:", insertions);
            $("#insertions").val(insertions);
        }
    });

    $.ajax({
        type: "POST",
        url: "/advertisement-view-data",
        success: function (response) {
            console.log(response);
            var table = "";
            $(".user-table tbody").empty();
            $.each(response, function (indexInArray, advertisement) {
                var newspaperNames = advertisement.assigned_news
                    .map(function (assignedNews) {
                        return assignedNews.empanelled.news_name;
                    })
                    .join("\n");

                var issueDate = formatDate(advertisement.issue_date);
                var baseUrl = "{{ url('/') }}";

                table +=
                    "<tr>" +
                    '<td class="text-center">' +
                    (indexInArray + 1) +
                    "</td>" +
                    '<td class="text-center">' +
                    issueDate +
                    "</td>" +
                    '<td class="text-center" width="30%">' +
                    advertisement.hod +
                    "</td>" +
                    '<td class="text-center" width="30%">' +
                    advertisement.advertisement_type.name +
                    "</td>" +
                    '<td class="text-center" >' +
                    newspaperNames +
                    "</td>" +
                    '<td class="text-center">' +
                    advertisement.amount +
                    "</td>";

                table +=
                    '<td class="text-center"><span style="color:darkblue;" data-id="' +
                    advertisement.id +
                    '"class="icon ri-edit-2-fill advertisement-edit"></span> &nbsp; &nbsp;<span style="color:red;font-size:20px;" data-id="' +
                    advertisement.id +
                    '" class="icon ri-close-line advertisement-delete"></span> &nbsp; &nbsp;  </td><td align=center><a href="/reports/release_order/' +
                    advertisement.id +
                    '" style="color: black; font-size: 20px;" data-id="' +
                    advertisement.id +
                    '" class="icon ri-download-fill release_order" target="_blank"></a></td>' +
                    "</tr>";
            });
            $(".user-table tbody").append(table);
            $(".user-table").DataTable({
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

    $("#advertisement-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var positivelyDates = $("#positively").multiDatesPicker("getDates");
        formData.set("positively", JSON.stringify(positivelyDates));
        for (const [key, value] of formData.entries()) {
            console.log(key + " : " + value);
        }
        $.ajax({
            type: "POST",
            url: "/advertisement-store-data",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data["flag"] == "Y" || data["flag"] == "YY") {
                    // Success for creation or editing of advertisement
                    $(".table_msg1")
                        .toggle(data["flag"] == "Y")
                        .delay(2000)
                        .fadeOut();
                    $(".table_msg5")
                        .toggle(data["flag"] == "YY")
                        .delay(2000)
                        .fadeOut();

                    $("#modalIssueDate").text($("#issue_date").val());
                    $("#modalDepartment").text($("#department").val());
                    $("#modalAdvertisementType").text(
                        $("#advertisementType option:selected").text()
                    );
                    $("#modalCategory").text(
                        $("#category option:selected").text()
                    );
                    $("#modalCm").text($("#cm").val());
                    $("#modalColumns").text($("#columns").val());
                    $("#modalSeconds").text($("#seconds").val());
                    $("#modalAmount").text($("#amount").val());
                    $("#modalSubject").text(
                        $("#subject option:selected").text()
                    );
                    $("#modalRefNo").text($("#ref_no").val());
                    $("#modalRefDate").text($("#ref_date").val());
                    $("#modalPositively").text($("#positively").val());
                    $("#modalInsertions").text($("#insertions").val());
                    $("#modalNewspaper").text(
                        $("#newspaper option:selected").text()
                    );
                    $("#modalLetterNo").text($("#letter_no").val());
                    $("#modalRemarks").text($("#remarks").val());

                    var advertisementType = $("#advertisementType").val();
                    if (advertisementType === "7") {
                        $("#modalCategoryContainer").show();
                        $("#modalCmContainer").show();
                        $("#modalColumnsContainer").show();
                        $("#modalSecondsContainer").hide();
                        $("#modalCm").text($("#cm").val());
                        $("#modalColumns").text($("#columns").val());
                    } else if (advertisementType === "6") {
                        $("#modalCategoryContainer").hide();
                        $("#modalCmContainer").hide();
                        $("#modalColumnsContainer").hide();
                        $("#modalSecondsContainer").show();
                        $("#modalSeconds").text($("#seconds").val());
                    } else {
                        $("#modalCategoryContainer").hide();
                        $("#modalSizeContainer").hide();
                        $("#modalCmContainer").hide();
                        $("#modalColumnsContainer").hide();
                        $("#modalSecondsContainer").hide();
                    }

                    $("#formSubmissionModal").modal("show");
                } else if (
                    data["flag"] == "N" ||
                    data["flag"] == "VE" ||
                    data["flag"] == "NN"
                ) {
                    // Error while creation or editing of advertisement
                    $(".table_msg2").show().delay(2000).fadeOut();
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

    $("#category").on("change", function () {
        updateAmount();
    });

    $("#cm").on("keyup", function () {
        updateAmount();
    });

    $("#columns").on("keyup", function () {
        updateAmount();
    });
    $("#seconds").on("keyup", function () {
        updateAmount();
    });
    $("#advertisementType").on("change", function () {
        $("#amount").val("");
        $("#base_amount").val("");
        $("#gst_rate").val("");
        $("#cm").val("");
        $("#columns").val("");
        $("#seconds").val("");
    });

    function updateAmount() {
        var category = $("#category").val();
        var cm = $("#cm").val();
        var columns = $("#columns").val();
        var advertisementType = $("#advertisementType").val();

        if (advertisementType === "6") {
            var seconds = $("#seconds").val();
            if (seconds) {
                $.ajax({
                    type: "POST",
                    url: "/getAmount",
                    data: {
                        seconds: seconds,
                        advertisementType: advertisementType,
                    },
                    success: function (response) {
                        $("#amount").val(response.amount);
                        $("#base_amount").val(response.base_amount);
                        $("#gst_rate").val(response.gst_rate);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching amount:", error);
                        $("#amount").val("");
                    },
                });
            } else {
                $("#amount").val("");
            }
        } else if (advertisementType === "7") {
            if (category && cm && columns) {
                $.ajax({
                    type: "POST",
                    url: "/getAmount",
                    data: {
                        cm: cm,
                        columns: columns,
                        category: category,
                        advertisementType: advertisementType,
                    },
                    success: function (response) {
                        $("#amount").val(response.amount);
                        $("#base_amount").val(response.base_amount);
                        $("#gst_rate").val(response.gst_rate);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching amount:", error);
                        $("#amount").val("");
                    },
                });
            } else {
                $("#amount").val("");
            }
        } else {
            $("#amount").val("");
        }
    }

    $("#formSubmissionModal").on("hidden.bs.modal", function () {
        window.location.reload();
    });

    $(document).on("click", ".advertisement-edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var value;
        $.ajax({
            type: "POST",
            url: "/advertisement-edit-data",
            data: { id },
            cache: false,
            success: function (data) {
                $("#advertisement-form").trigger("reset");
                $("#id").val(data[0]["id"]);

                $("#issue_date").val(formatDateFromDB(data[0]["issue_date"]));

                $("#department").val(data[0]["hod"]);
                if (data[0]["advertisement_type"]) {
                    $("#advertisementType").val(
                        data[0]["advertisement_type"]["id"]
                    );
                }
                if (data[0]["ad_category"]) {
                    $("#category").val(data[0]["ad_category"]["id"]);
                }
                $("#size").val(data[0]["size"]);
                $("#amount").val(data[0]["amount"]);

                if (data[0]["subject"]) {
                    $("#subject").val(data[0]["subject"]["id"]);
                }
                $("#ref_no").val(data[0]["ref_no"]);
                $("#ref_date").val(formatDateFromDB(data[0]["ref_date"]));
                $("#positively").val(
                    formatDateFromDB(data[0]["positively_on"])
                );
                $("#insertions").val(data[0]["no_of_entries"]);
                console.log("Assigned Newspapers");
                var selectedNewspapers = data[0]["assigned_news"].map(function (
                    news
                ) {
                    console.log(news["empanelled"]["news_name"]);
                    return news["empanelled"]["id"];
                });

                // Set the selected options for the newspaper field
                $("#newspaper").val(selectedNewspapers);
                $(".select2").select2();
                $("#remarks").val(data[0]["remarks"]);
                $("#users-tab").tab("show");
            },
        });
    });

    function formatDateFromDB(f_date) {
        var f_date = new Date(f_date);
        var formattedDate = $.datepicker.formatDate("dd-mm-yy", f_date);
        return formattedDate;
    }

    $(document).on("click", ".advertisement-delete", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "/advertisement-delete-data",
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
                    alert(
                        "The Advertisement has a Bill associated with it. It cannot be deleted!"
                    );
                    // $(".table_msg4").show();
                    // $('.table_msg4').delay(1000).fadeOut();
                    //   setTimeout(function(){
                    //     window.location.reload();
                    //  }, 1500);
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

document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("advertisementType");
    const printCalculationDiv = document.getElementById("printcalculation");
    const videoSecondsContainer = document.getElementById(
        "videoSecondsContainer"
    );
    const subjectContainer = document.getElementById("subjectContainer");
    const colorContainer = document.getElementById("colorContainer");
    const pageInfoContainer = document.getElementById("pageInfoContainer");
    const amountInput = document.getElementById("amount");
    const cmInput = document.getElementById("cm");
    const columnsInput = document.getElementById("columns");
    const secondsInput = document.getElementById("seconds");
    const categoryContainer = document.getElementById("categoryContainer");
    const categorySelect = document.getElementById("category");
    const subjectSelect = document.getElementById("subject");
    const colorSelect = document.getElementById("color");
    const pageInfoSelect = document.getElementById("page_info");
    const insertionsLabel = document.getElementById("insertions-label");

    typeSelect.addEventListener("change", function () {
        if (typeSelect.value === "7") {
            printCalculationDiv.style.display = "block";
            categoryContainer.style.display = "block";
            videoSecondsContainer.style.display = "none";
            subjectContainer.style.display = "block";
            colorContainer.style.display = "block";
            pageInfoContainer.style.display = "block";
            amountInput.readOnly = true;
            cmInput.disabled = false;
            columnsInput.disabled = false;
            secondsInput.disabled = true;
            categorySelect.disabled = false;
            subjectSelect.disabled = false;
            colorSelect.disabled = false;
            pageInfoSelect.disabled = false;
            insertionsLabel.innerHTML = "<b>No of issues</b>";
        } else if (typeSelect.value === "6") {
            printCalculationDiv.style.display = "none";
            categoryContainer.style.display = "none";
            videoSecondsContainer.style.display = "block";
            subjectContainer.style.display = "none";
            colorContainer.style.display = "none";
            pageInfoContainer.style.display = "none";
            amountInput.readOnly = true;
            cmInput.disabled = true;
            columnsInput.disabled = true;
            secondsInput.disabled = false;
            categorySelect.disabled = true;
            subjectSelect.disabled = true;
            colorSelect.disabled = true;
            pageInfoSelect.disabled = true;
            insertionsLabel.innerHTML = "<b>No of days</b>";
        } else if (typeSelect.value === "8") {
            printCalculationDiv.style.display = "none";
            categoryContainer.style.display = "none";
            videoSecondsContainer.style.display = "none";
            subjectContainer.style.display = "none";
            colorContainer.style.display = "none";
            pageInfoContainer.style.display = "none";
            amountInput.readOnly = false;
            amountInput.disabled = false;
            cmInput.disabled = true;
            columnsInput.disabled = true;
            secondsInput.disabled = true;
            categorySelect.disabled = true;
            subjectSelect.disabled = true;
            colorSelect.disabled = true;
            pageInfoSelect.disabled = true;
            insertionsLabel.innerHTML = "<b>No of days</b>";
        } else {
            printCalculationDiv.style.display = "none";
            categoryContainer.style.display = "none";
            videoSecondsContainer.style.display = "none";
            amountInput.readOnly = true;
            cmInput.disabled = true;
            columnsInput.disabled = true;
            secondsInput.disabled = true;
            categorySelect.disabled = true;
            subjectSelect.disabled = true;
            colorSelect.disabled = true;
            pageInfoSelect.disabled = true;
            insertionsLabel.innerHTML = "<b>No of days</b>";
        }
    });

    typeSelect.dispatchEvent(new Event("change"));
});
