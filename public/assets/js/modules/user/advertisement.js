$(document).ready(function () {
    $(".select2").select2();

    $(document).on("click", "#users-tab", function (e) {
        $("#email").prop("disabled", false);
        $("#advertisement-form").trigger("reset");
        $("#id").val("");
        getCurrentMIPR();
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
            minDate: 0,
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
            minDate: null,
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

                var isPublishedChecked = advertisement.is_published
                    ? "checked"
                    : "";
                table +=
                    "<tr class='advertisement-row' data-id='" +
                    advertisement.id +
                    "'>" +
                    '<td class="text-center">' +
                    (indexInArray + 1) +
                    "</td>" +
                    '<td class="text-center">' +
                    issueDate +
                    "</td>" +
                    '<td class="text-center">' +
                    advertisement.mipr_no +
                    "</td>" +
                    '<td class="text-center" width="30%">' +
                    advertisement.department.dept_name +
                    "</td>" +
                    '<td class="text-center">' +
                    (advertisement.amount !== null
                        ? advertisement.amount
                        : "NA") +
                    "</td>" +
                    '<td class="text-center">' +
                    (advertisement.status === "Cancelled"
                        ? '&nbsp;<span style="color:green;font-size:20px;" data-id="' +
                          advertisement.id +
                          '" class="icon ri-eye-fill advertisement-view" title="View Details"></span>'
                        : '<span style="color:darkblue;" data-id="' +
                          advertisement.id +
                          '" class="icon ri-edit-2-fill advertisement-edit"></span> &nbsp; &nbsp;' +
                          '<span style="color:red;font-size:20px;" data-id="' +
                          advertisement.id +
                          '" class="icon ri-close-line advertisement-delete"></span> &nbsp; &nbsp;' +
                          '<span style="color:green;font-size:20px;" data-id="' +
                          advertisement.id +
                          '" class="icon ri-eye-fill advertisement-view" title="View Details"></span>') +
                    "</td>" +
                    "<td align=center>" +
                    (advertisement.status === "Cancelled"
                        ? ""
                        : '<a href="/reports/release_order/' +
                          advertisement.id +
                          '" style="color: black; font-size: 20px;" data-id="' +
                          advertisement.id +
                          '" class="icon ri-download-fill release_order" target="_blank"></a>') +
                    "</td>" +
                    '<td class="text-center">' +
                    '<span class="badge rounded-pill ' +
                    (advertisement.status === "Cancelled"
                        ? "bg-danger"
                        : advertisement.status === "Billed"
                        ? "bg-success"
                        : "bg-secondary") +
                    '">' +
                    (advertisement.status === "Cancelled"
                        ? "Cancelled"
                        : advertisement.status === "Billed"
                        ? "Billed"
                        : "Pending") +
                    "</span>" +
                    "</td>" +
                    '<td class="text-center">' +
                    '<label class="switch">' +
                    '<input type="checkbox" class="publish-switch" data-id="' +
                    advertisement.id +
                    '" ' +
                    isPublishedChecked +
                    ">" +
                    '<span class="slider round"></span>' +
                    "</label>" +
                    "</td>" +
                    "</tr>";
            });

            $(".user-table tbody").append(table);

            // Initialize DataTable
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

            // Eye icon click event for opening the modal
            $(".advertisement-view").on("click", function (e) {
                e.stopPropagation(); // Prevent triggering the row click
                var advertisementId = $(this).data("id");
                showAdvertisementDetails(advertisementId);
            });
        },
    });

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
        console.log(
            "Selected advertisementType:",
            $("#advertisementType").val()
        );
        $("#amount").val("");
        $("#cm").val("");
        $("#columns").val("");
        $("#seconds").val("");

        updateAmount();
    });

    function updateAmount() {
        var category = $("#category").val();
        var cm = $("#cm").val();
        var columns = $("#columns").val();
        var advertisementType = $("#advertisementType").val();

        if (advertisementType === "6") {
            console.log("advertisementType = Video Radio");
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
                        $("#amount").val(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching amount:", error);
                        $("#amount").val("");
                    },
                });
            } else {
                $("#amount").val("");
            }
        } else if (advertisementType === "8") {
            console.log("advertisementType = Online Media");
            $.ajax({
                type: "POST",
                url: "/getAmount",
                data: {
                    advertisementType: advertisementType,
                },
                success: function (response) {
                    $("#amount").val(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching amount:", error);
                    $("#amount").val("");
                },
            });
        } else if (advertisementType === "7") {
            console.log("advertisementType = Print");
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
                        $("#amount").val(response);
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

                $("#department").val(data[0]["department"]);
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

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: "Are you sure?",
            text: "You will need to provide a reason for cancellation.",
            input: "textarea",
            inputLabel: "Cancellation Reason",
            inputPlaceholder: "Enter the reason for cancellation...",
            inputAttributes: {
                "aria-label": "Enter the reason for cancellation",
            },
            showCancelButton: true,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, keep it",
            icon: "warning",
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with AJAX request
                $.ajax({
                    type: "POST",
                    url: "/advertisement-delete-data",
                    data: {
                        id: id,
                        cancellation_reason: result.value,
                    },
                    cache: false,
                    success: function (data) {
                        if (data["flag"] == "Y") {
                            Swal.fire({
                                title: "Cancelled!",
                                text: "The advertisement has been cancelled.",
                                icon: "success",
                            }).then(() => {
                                // Optionally, show a message and reload the page
                                $(".table_msg3").show();
                                $(".table_msg3").delay(5000).fadeOut();
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "The Advertisement has a Bill associated with it. It cannot be deleted!",
                                icon: "error",
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred: " + xhr.responseText,
                            icon: "error",
                        });
                    },
                });
            }
        });
    });

    $("#title").click(function (e) {
        $(".msg1").hide();
    });

    $("#body").click(function (e) {
        $(".msg2").hide();
    });

    function getCurrentMIPR() {
        $.ajax({
            url: "/get-current-mipr",
            type: "GET",
            success: function (response) {
                console.log("MIPR:" + response.mipr_no);
                console.log("MIPR:" + response.last_mipr_no);
                $("#mipr_no").val(response.mipr_no);
                $("#mipr-last").text(response.last_mipr_no);
            },
            error: function (xhr) {
                console.error("Error fetching current MIPR number:", xhr);
            },
        });
    }

    getCurrentMIPR();

    $("#department_category").on("change", function () {
        var categoryId = $(this).val();
        $.ajax({
            type: "POST",
            url: "/get-departments-by-category",
            data: { category_id: categoryId },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                var departmentDropdown = $("#department");
                departmentDropdown.empty();
                departmentDropdown.append(
                    '<option value="" disabled selected>Select Department</option>'
                );

                $.each(response, function (index, department) {
                    departmentDropdown.append(
                        '<option value="' +
                            department.id +
                            '">' +
                            department.dept_name +
                            "</option>"
                    );
                });

                // Reinitialize Select2 after appending new options
                departmentDropdown.select2({
                    placeholder: "Select Department",
                    allowClear: true,
                });
            },
        });
    });

    $("#newspaper_type").on("change", function () {
        var typeId = $(this).val();
        $.ajax({
            type: "POST",
            url: "/get-newspapers-by-type",
            data: { type_id: typeId },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                var newspaperDropdown = $("#newspaper");
                newspaperDropdown.empty(); // Clear existing options

                // Append "Select All" option
                newspaperDropdown.append(
                    '<option value="select-all">Select All</option>'
                );

                // Loop through the response and add newspaper options dynamically
                $.each(response, function (index, newspaper) {
                    let optionText = `${newspaper.name} (${newspaper.advertisement_count})`;

                    newspaperDropdown.append(
                        '<option value="' +
                            newspaper.id +
                            '">' +
                            optionText +
                            "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            },
        });
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

    const newsTypeSelect = document.getElementById("newspaper_type");
    const amountContainer = document.getElementById("amountContainer");

    typeSelect.addEventListener("change", function () {
        if (typeSelect.value === "7") {
            printCalculationDiv.style.display = "block";
            categoryContainer.style.display = "block";
            videoSecondsContainer.style.display = "none";
            subjectContainer.style.display = "block";
            colorContainer.style.display = "block";
            amountInput.readOnly = true;
            cmInput.disabled = false;
            columnsInput.disabled = false;
            secondsInput.disabled = true;
            categorySelect.disabled = false;
            subjectSelect.disabled = false;
            colorSelect.disabled = false;
            insertionsLabel.innerHTML = "<b>No of issues</b>";
        } else if (typeSelect.value === "6") {
            printCalculationDiv.style.display = "none";
            categoryContainer.style.display = "none";
            videoSecondsContainer.style.display = "block";
            subjectContainer.style.display = "none";
            colorContainer.style.display = "none";
            amountInput.readOnly = true;
            cmInput.disabled = true;
            columnsInput.disabled = true;
            secondsInput.disabled = false;
            categorySelect.disabled = true;
            subjectSelect.disabled = true;
            colorSelect.disabled = true;
            insertionsLabel.innerHTML = "<b>No of days</b>";
        } else if (typeSelect.value === "8") {
            printCalculationDiv.style.display = "none";
            categoryContainer.style.display = "none";
            videoSecondsContainer.style.display = "none";
            subjectContainer.style.display = "none";
            colorContainer.style.display = "none";
            amountInput.readOnly = true;
            cmInput.disabled = true;
            columnsInput.disabled = true;
            secondsInput.disabled = true;
            categorySelect.disabled = true;
            subjectSelect.disabled = true;
            colorSelect.disabled = true;
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
            insertionsLabel.innerHTML = "<b>No of days</b>";
        }
    });

    typeSelect.dispatchEvent(new Event("change"));

    categorySelect.addEventListener("change", function () {
        if (categorySelect.value === "1") {
            pageInfoContainer.style.display = "block";
            page_info.readOnly = false;
            page_info.disabled = false;
        } else {
            pageInfoContainer.style.display = "none";
            page_info.readOnly = true;
            page_info.disabled = true;
        }
    });

    categorySelect.dispatchEvent(new Event("change"));

    newsTypeSelect.addEventListener("change", function () {
        if (newsTypeSelect.value === "2") {
            amountInput.readOnly = false;
            amountInput.classList.remove("readonly-input");
        } else {
            amountInput.readOnly = true;
            amountInput.classList.add("readonly-input");
        }
    });

    categorySelect.dispatchEvent(new Event("change"));

    $(document).on("change", ".publish-switch", function () {
        var advertisementId = $(this).data("id");
        console.log(advertisementId);
        var isPublished = $(this).is(":checked") ? 1 : 0;

        $.ajax({
            type: "POST",
            url: "/update-advertisement-publish-status",
            data: {
                id: advertisementId,
                is_published: isPublished,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log("Advertisement status updated:", response);
            },
            error: function (xhr, status, error) {
                console.error("Error updating status:", error);
            },
        });
    });
});

// Function to fetch advertisement details
function showAdvertisementDetails(advertisementId) {
    $.ajax({
        type: "GET",
        url: "/advertisement/" + advertisementId,
        success: function (response) {
            console.log(response);

            var issueDate = formatDate(response.issue_date);
            $("#detailsModalStatus").text(response.status);
            $("#detailsModalIssueDate").text(issueDate);
            $("#detailsModalMiprNo").text(response.mipr_no);
            $("#detailsModalDepartment").text(response.department.dept_name);
            $("#detailsModalAdvertisementType").text(
                response.advertisement_type.name
            );
            $("#detailsModalNewspapers").text(
                response.assigned_news
                    .map((assignedNews) => assignedNews.empanelled.news_name)
                    .join(", ")
            );
            $("#detailsModalAmount").text(
                response.amount !== null ? response.amount : "NA"
            );
            $("#detailsModalRefNo").text(response.ref_no);
            $("#detailsModalRefDate").text(response.ref_date);
            $("#detailsModalPaymentBy").text(
                response.payment_by === "C" ? "Concerned Department" : "DIPR"
            );
            $("#detailsModalRemarks").text(response.remarks || "NA");

            if (response.cancellation_reason) {
                $("#cancellationReasonContainer").show();
                $("#cancellationReason").text(response.cancellation_reason);
            } else {
                $("#cancellationReasonContainer").hide();
            }

            $("#detailsModalInsertions").text(response.no_of_entries || "NA");
            $("#detailsModalReleaseOrderNo").text(
                response.release_order_no || "NA"
            );
            $("#detailsModalReleaseOrderDate").text(
                response.release_order_date || "NA"
            );
            $("#detailsModalCM").text(
                response.cm !== null ? response.cm : "NA"
            );
            $("#detailsModalColumns").text(
                response.columns !== null ? response.columns : "NA"
            );

            if (response.status === "Cancelled") {
                $("#detailsModalStatus").text("Cancelled").css("color", "red");
                $("#cancellationReason").text(
                    response.cancellation_reason || "No reason provided."
                );
                $("#cancellationReasonContainer").show();
                $(".progressbar").hide();
            } else {
                $("#cancellationReasonContainer").hide();
                $(".progressbar").show();

                // Reset all steps in the progress bar
                $(".progressbar li").removeClass("active");

                // Set the "Pending" step as active by default
                $(".progressbar li").eq(0).addClass("active");

                // Mark the "Published" step as active only if is_published is true
                if (response.is_published) {
                    $(".progressbar li").eq(1).addClass("active");
                }

                // Mark the "Billed" step as active only if the status is "Billed"
                if (response.status === "Billed") {
                    $(".progressbar li").eq(2).addClass("active");
                }
            }

            $("#advertisementDetailsModal").modal("show");
        },
        error: function (xhr, status, error) {
            console.error("Error fetching advertisement details:", error);
        },
    });
}

function formatDate(dateString) {
    var date = new Date(dateString);
    var day = String(date.getDate()).padStart(2, "0");
    var month = String(date.getMonth() + 1).padStart(2, "0");
    var year = date.getFullYear();
    return day + "-" + month + "-" + year;
}

//---------------- VALIDATING FIELDS

document.getElementById("seconds").addEventListener("input", function (e) {
    let value = e.target.value;
    e.target.value = value.replace(/[^0-9]/g, "");
});
document.getElementById("cm").addEventListener("input", function (e) {
    let value = e.target.value;
    e.target.value = value.replace(/[^0-9]/g, "");
});

document.getElementById("columns").addEventListener("input", function (e) {
    let value = e.target.value;
    e.target.value = value.replace(/[^0-9]/g, "");
});
