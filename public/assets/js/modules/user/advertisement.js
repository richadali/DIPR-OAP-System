$(document).ready(function () {
    $(".select2").select2();

    $(document).on("click", "#users-tab", function (e) {
        $("#advertisement-form").trigger("reset");
        $("#id").val("");
        getCurrentMIPR();
    });

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

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
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

            $(".advertisement-view").on("click", function (e) {
                e.stopPropagation();
                var advertisementId = $(this).data("id");
                showAdvertisementDetails(advertisementId);
            });
        },
    });

    $("#advertisement-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.forEach(function (value, key) {
            if (key.startsWith("assigned_news")) {
                formData.delete(key);
            }
        });
        var newspaperData = [];
        $(".assigned-news-row").each(function () {
            var positively = $(this).find(".positively-datepicker").val();
            var selectedNewspapers = $(this)
                .find("select[name$='[newspaper][]']")
                .val();
            var cm = $(this).find(".cm-input").val();
            var columns = $(this).find(".columns-input").val();
            var seconds = $(this).find(".seconds-input").val();

            if (selectedNewspapers && positively) {
                selectedNewspapers.forEach(function (newspaperId) {
                    newspaperData.push({
                        newspaper_id: newspaperId,
                        positively: positively,
                        cm: cm || null,
                        columns: columns || null,
                        seconds: seconds || null,
                    });
                });
            }
        });
        formData.append("newspaper", JSON.stringify(newspaperData));

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
                    $("#modalSubject").text(
                        $("#subject option:selected").text()
                    );
                    $("#modalRefNo").text($("#ref_no").val());
                    $("#modalRefDate").text($("#ref_date").val());
                    $("#modalRemarks").text($("#remarks").val());

                    var advertisementType = $("#advertisementType").val();
                    if (advertisementType === "7") {
                        $("#modalCategoryContainer").show();
                    } else if (advertisementType === "6") {
                        $("#modalCategoryContainer").hide();
                    } else {
                        $("#modalCategoryContainer").hide();
                    }

                    $("#formSubmissionModal").modal("show");
                } else if (
                    data["flag"] == "N" ||
                    data["flag"] == "VE" ||
                    data["flag"] == "NN"
                ) {
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

    $("#advertisementType").on("change", function () {
        console.log(
            "Selected advertisementType:",
            $("#advertisementType").val()
        );
        $("#cm").val("");
        $("#columns").val("");
        $("#seconds").val("");
    });

    $("#formSubmissionModal").on("hidden.bs.modal", function () {
        window.location.reload();
    });

    $(document).on("click", ".advertisement-edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");

        $.ajax({
            type: "POST",
            url: "/advertisement-edit-data",
            data: { id },
            cache: false,
            success: function (data) {
                console.log("Complete Data Response:", data);

                // Skip form reset to avoid interfering with select2 values
                $("#advertisement-form")[0].reset(); // Skip triggering reset, manually set values instead
                $("#id").val(data[0]["id"]);

                if (data[0]["advertisement_type"]) {
                    $("#advertisementType").val(
                        data[0]["advertisement_type"]["id"]
                    );
                }
                if (data[0]["ad_category"]) {
                    $("#category").val(data[0]["ad_category"]["id"]);
                }

                initializeAdvertisementFormLogic();

                $("#issue_date").val(formatDateFromDB(data[0]["issue_date"]));

                var categoryId = data[0]["department"]["category_id"];
                $("#department_category").val(categoryId).trigger("change");

                var departmentInterval = setInterval(function () {
                    if ($("#department option").length > 1) {
                        $("#department")
                            .val(data[0]["department"]["id"])
                            .trigger("change");
                        clearInterval(departmentInterval);
                    }
                }, 100);

                $("#payment_by").val(data[0]["payment_by"]);
                $("#mipr_no").val(data[0]["mipr_no"]);
                $("#ref_no").val(data[0]["ref_no"]);
                $("#ref_date").val(formatDateFromDB(data[0]["ref_date"]));
                if (data[0]["color"]) {
                    $("#color").val(data[0]["color"]["id"]);
                }
                if (data[0]["page_info"]) {
                    $("#page_info").val(data[0]["page_info"]["id"]);
                }

                $("#remarks").val(data[0]["remarks"]);
                $("#users-tab").tab("show");

                // Grouping and populating rows based on assigned_news data
                const assignedNewsData = data[0]["assigned_news"];
                const groupedData = {};

                // Group the data based on `positively_on`, `cm`, `columns`, and `seconds`
                assignedNewsData.forEach((newsItem) => {
                    const key = `${newsItem["positively_on"]}-${newsItem["cm"]}-${newsItem["columns"]}-${newsItem["seconds"]}`;

                    if (!groupedData[key]) {
                        groupedData[key] = {
                            positively_on: newsItem["positively_on"],
                            cm: newsItem["cm"],
                            columns: newsItem["columns"],
                            seconds: newsItem["seconds"],
                            newspapers: [],
                        };
                    }

                    // Add newspaper details to the group
                    groupedData[key].newspapers.push(newsItem["empanelled_id"]);
                });

                // Now loop through the grouped data to populate rows
                Object.values(groupedData).forEach((group, index) => {
                    addAssignedNewsRow(); // Create a new row for this group

                    const rowIndex = assignedNewsIndex - 1; // Adjust row index based on the increment

                    // Set values dynamically for each row
                    $(`#positively-${rowIndex}`).val(group.positively_on);

                    // Set cm and columns based on advertisement type
                    if (data[0]["advertisement_type"]["id"] === 7) {
                        $(`#cm-${rowIndex}`).val(group.cm);
                        $(`#columns-${rowIndex}`).val(group.columns);
                        $(`#seconds-${rowIndex}`).val(""); // Empty seconds for Print type
                    } else if (data[0]["advertisement_type"]["id"] === 6) {
                        $(`#seconds-${rowIndex}`).val(group.seconds);
                        $(`#cm-${rowIndex}`).val(""); // Empty cm for Seconds type
                        $(`#columns-${rowIndex}`).val(""); // Empty columns for Seconds type
                    }

                    // Handle the newspaper selection dynamically
                    const newspaperSelect = $(
                        `select[name="assigned_news[${rowIndex}][newspaper][]"]`
                    );
                    console.log("Newspaper IDs:", group.newspapers); // Debug log to check newspaper ids

                    // Destroy previous select2 instance and reinitialize it
                    newspaperSelect.select2("destroy").empty();

                    // Add the options dynamically based on available newspapers (empanelled news)
                    group.newspapers.forEach((newspaperId) => {
                        const newspaperData = data[0]["assigned_news"].find(
                            (item) => item.empanelled_id == newspaperId
                        );
                        if (newspaperData) {
                            const option = new Option(
                                newspaperData.empanelled.news_name,
                                newspaperId,
                                true,
                                true
                            );
                            newspaperSelect.append(option);
                        }
                    });

                    // Re-initialize select2 after populating the options
                    newspaperSelect.select2();
                });

                // Initialize select2 only once after all rows are populated and options are set
                $(".select2").each(function () {
                    $(this).select2();
                });
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
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

                departmentDropdown.select2({
                    placeholder: "Select Department",
                    allowClear: true,
                });
            },
        });
    });

    function initializeAdvertisementFormLogic() {
        const typeSelect = $("#advertisementType");
        const categorySelect = $("#category");

        typeSelect.on("change", function () {
            if (typeSelect.val() === "7") {
                $("#categoryContainer").show();
                $("#subjectContainer").show();
                $("#colorContainer").show();
                $("#category").prop("disabled", false);
                $("#subject").prop("disabled", false);
                $("#color").prop("disabled", false);
            } else if (typeSelect.val() === "6") {
                $("#categoryContainer").hide();
                $("#subjectContainer").hide();
                $("#colorContainer").hide();
                $("#category").prop("disabled", true);
                $("#subject").prop("disabled", true);
                $("#color").prop("disabled", true);
            } else if (typeSelect.val() === "8") {
                $("#categoryContainer").hide();
                $("#subjectContainer").hide();
                $("#colorContainer").hide();
                $("#category").prop("disabled", true);
                $("#subject").prop("disabled", true);
                $("#color").prop("disabled", true);
                $("#pageInfoContainer").hide();
                $("#page_info").prop("readOnly", true).prop("disabled", true);
            } else {
                $("#categoryContainer").hide();
                $("#category").prop("disabled", true);
                $("#subject").prop("disabled", true);
                $("#color").prop("disabled", true);
            }
        });

        categorySelect.on("change", function () {
            if (categorySelect.val() === "1") {
                $("#pageInfoContainer").show();
                $("#page_info").prop("readOnly", false).prop("disabled", false);
            } else {
                $("#pageInfoContainer").hide();
                $("#page_info").prop("readOnly", true).prop("disabled", true);
            }
        });

        // Trigger the initial change events
        typeSelect.trigger("change");
        categorySelect.trigger("change");
    }

    // Call the function
    initializeAdvertisementFormLogic();
});

document.addEventListener("DOMContentLoaded", function () {
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
                $(".progressbar li").removeClass("active");
                $(".progressbar li").eq(0).addClass("active");
                if (response.is_published) {
                    $(".progressbar li").eq(1).addClass("active");
                }
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

let assignedNewsIndex = 1; // Keeps track of the row number.
const insertionCounts = {};
const newspaperData = {};
let advertisementType = $("#advertisementType").val(); // Assume this dropdown exists on the page.

function updateInsertionTable() {
    const tableBody = $("#insertionCountsTable tbody");
    tableBody.empty();

    Object.entries(insertionCounts).forEach(([newspaperId, count]) => {
        if (count > 0) {
            const newspaperName =
                newspaperData[newspaperId] || `Unknown Newspaper`;
            tableBody.append(`
                <tr>
                    <td>${newspaperName}</td>
                    <td>${count}</td>
                </tr>
            `);
        }
    });
}

function updateInsertions() {
    Object.keys(insertionCounts).forEach((key) => (insertionCounts[key] = 0));

    $(".assigned-news-row").each(function () {
        const positivelyInput = $(this).find(".positively-datepicker");
        const newspaperSelect = $(this).find("select");

        const selectedDate = positivelyInput.val();
        const selectedNewspapers = newspaperSelect.val();

        if (selectedDate && selectedNewspapers) {
            selectedNewspapers.forEach((newspaperId) => {
                if (!insertionCounts[newspaperId]) {
                    insertionCounts[newspaperId] = 0;
                }
                insertionCounts[newspaperId] += 1;
            });
        }
    });

    updateInsertionTable();
}

function populateNewspaperDropdown(rowIndex) {
    const newspaperSelect = $(
        `select[name="assigned_news[${rowIndex}][newspaper][]"]`
    );

    $.ajax({
        url: "/get-newspapers",
        type: "POST",
        data: { type_id: advertisementType },
        success: function (data) {
            newspaperSelect.empty();
            newspaperSelect.append(
                '<option value="select-all">- Select All -</option>'
            );

            data.forEach(function (newspaper) {
                newspaperData[newspaper.id] = newspaper.name;
                newspaperSelect.append(
                    `<option value="${newspaper.id}">${newspaper.name} (${newspaper.advertisement_count})</option>`
                );
            });

            newspaperSelect.on("change", function () {
                if (newspaperSelect.val().includes("select-all")) {
                    newspaperSelect.val(data.map((item) => item.id));
                    newspaperSelect.trigger("change.select2");
                }
                updateInsertions();
            });

            newspaperSelect.trigger("change.select2");
        },
        error: function () {
            alert("Error fetching newspaper data.");
        },
    });
}

function addAssignedNewsRow() {
    const tableBody = document.querySelector("#assignedNewsTableBody");

    const newRow = document.createElement("tr");
    newRow.classList.add("assigned-news-row", "align-middle");
    const rowId =
        assignedNewsIndex === 1 ? "first-row" : `row-${assignedNewsIndex}`;

    // Determine the advertisement type dynamically
    const advertisementType = $("#advertisementType").val();

    let dynamicFields = "";

    if (advertisementType === "7") {
        dynamicFields = `
            <div class="col-2">
                <input type="text" id="cm-${assignedNewsIndex}" name="assigned_news[${assignedNewsIndex}][cm]" class="form-control cm-input" placeholder="CM" required>
            </div>
            <div class="col-2">
                <input type="text" id="columns-${assignedNewsIndex}" name="assigned_news[${assignedNewsIndex}][columns]" class="form-control columns-input" placeholder="Columns" required>
            </div>
        `;
    } else if (advertisementType === "6") {
        dynamicFields = `
            <div class="col-4">
                <input type="text" id="seconds-${assignedNewsIndex}" name="assigned_news[${assignedNewsIndex}][seconds]" class="form-control seconds-input" placeholder="Seconds" required>
            </div>
        `;
    } else {
        dynamicFields = `
            <div class="col-12">
                <select name="assigned_news[${assignedNewsIndex}][newspaper][]" class="form-control select2" multiple required>
                    <option value="select-all">- Select All -</option>
                </select>
            </div>
        `;
    }

    newRow.innerHTML = `
        <td>
            <input type="text" id="positively-${assignedNewsIndex}" name="assigned_news[${assignedNewsIndex}][positively]" class="form-control positively-datepicker" required>
        </td>
        <td>
            <div class="row">
                ${
                    advertisementType === "7" || advertisementType === "6"
                        ? `
                    <div class="col-8">
                        <select name="assigned_news[${assignedNewsIndex}][newspaper][]" class="form-control select2" multiple required>
                            <option value="select-all">- Select All -</option>
                        </select>
                    </div>
                    ${dynamicFields}
                `
                        : dynamicFields
                }
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeAssignedNewsRow(this)" title="Remove this row" ${
                assignedNewsIndex === 1 ? "disabled" : ""
            }>
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

    newRow.id = rowId;
    tableBody.appendChild(newRow);

    // Initialize datepicker for the new row
    $(`#positively-${assignedNewsIndex}`).datepicker({
        dateFormat: "dd-mm-yy",
        onSelect: updateInsertions,
        onClose: updateInsertions,
    });

    // Initialize Select2 for the newspaper dropdown
    $(
        `select[name="assigned_news[${assignedNewsIndex}][newspaper][]"]`
    ).select2();

    // Populate the newspaper dropdown with data from the server
    populateNewspaperDropdown(assignedNewsIndex);

    // Add input validation for CM, Columns, and Seconds
    if (advertisementType === "7") {
        document
            .getElementById(`cm-${assignedNewsIndex}`)
            .addEventListener("input", function (e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, "");
            });

        document
            .getElementById(`columns-${assignedNewsIndex}`)
            .addEventListener("input", function (e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, "");
            });
    }

    if (advertisementType === "6") {
        document
            .getElementById(`seconds-${assignedNewsIndex}`)
            .addEventListener("input", function (e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, "");
            });
    }

    assignedNewsIndex++;
    updateRemoveButtonState(); // Check if the first row needs to be disabled
}

// Update the remove button state for the first row
function updateRemoveButtonState() {
    const firstRowButton = $("#assignedNewsTableBody tr:first-child button");
    if (firstRowButton.length > 0) {
        firstRowButton.prop("disabled", true);
    }
}

function removeAssignedNewsRow(button) {
    const row = button.closest("tr");

    if (row.id === "first-row") {
        alert("The first row cannot be deleted.");
        return;
    }

    row.remove();
    updateInsertions();
    updateRemoveButtonState(); // Recheck the first row button state
}

$(document).ready(function () {
    advertisementType = $("#advertisementType").val();
    $("#advertisementType").on("change", function () {
        advertisementType = $(this).val();
        $("#assignedNewsTable tbody").empty();
        addAssignedNewsRow();
    });

    addAssignedNewsRow();
});

function formatDate(dateString) {
    var date = new Date(dateString);
    var day = String(date.getDate()).padStart(2, "0");
    var month = String(date.getMonth() + 1).padStart(2, "0");
    var year = date.getFullYear();
    return day + "-" + month + "-" + year;
}
