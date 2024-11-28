@extends('layouts.app_1')

@section('content')

<style>
  .badge {
    min-width: 80px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border-radius: 15px;
  }

  .readonly-input {
    background-color: #f0f0f0;
    color: #000;
  }

  .readonly-input:focus {
    background-color: #e0e0e0;
    color: #000;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 34px;
    height: 20px;
  }

  /* Hide default HTML checkbox */
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 20px;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    border-radius: 50%;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:checked+.slider:before {
    transform: translateX(14px);
  }

  .container {
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .progressbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    counter-reset: step;
    padding: 0;
    margin: 20px 0;
    width: 100%;
  }

  .progressbar li {
    list-style: none;
    position: relative;
    text-align: center;
    cursor: pointer;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .progressbar li:before {
    content: counter(step);
    counter-increment: step;
    width: 30px;
    height: 30px;
    line-height: 30px;
    border: 2px solid #ddd;
    border-radius: 50%;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    z-index: 1;
  }

  /* Line between each step */
  .progressbar li:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(15px);
    /* Adjusts for circle width */
    width: calc(100% - 30px);
    /* Adjusts for spacing between circles */
    height: 2px;
    background-color: #ddd;
    /* Default color */
    z-index: 0;
  }

  /* Active step */
  .progressbar li.active {
    color: green;
  }

  .progressbar li.active:before {
    border-color: green;
    background-color: green;
    color: white;
  }

  /* Reset the line after the last active step to gray */
  .progressbar li.active+li::after {
    background-color: #ddd;
    /* Reset line color */
  }
</style>

<div class="modal fade" id="advertisementDetailsModal" tabindex="-1" aria-labelledby="advertisementModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="advertisementModalLabel">Advertisement Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Progress bar -->
        <div class="container">
          <ul class="progressbar"
            style="margin-top: 5px; padding: 20px; border: 1px solid #d7fadf; background-color: #d7fadf; border-radius: 8px;">
            <li>Created</li>
            <li>Published</li>
            <li>Billed</li>

          </ul>
        </div>

        <div id="cancellationReasonContainer"
          style="display: none; margin-top: 5px; padding: 15px; border: 1px solid #f8d7da; background-color: #f8d7da; border-radius: 8px;">
          <div style="display: flex; align-items: center;">
            <!-- Red Icon -->
            <div style="color: #d9534f; font-size: 2.5rem; margin-right: 15px;">
              <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
              <h5 style="color: #d9534f; margin: 0;">Advertisement Cancelled</h5>
              <p style="margin: 5px 0; font-size: 1rem;">
                <strong>Reason:</strong> <span id="cancellationReason"></span>
              </p>
            </div>
          </div>
        </div>


        <div class="row mt-3">
          <div class="col-md-6">
            <p><strong>Issue On:</strong> <span id="detailsModalIssueDate"></span></p>
            <p><strong>Department:</strong> <span id="detailsModalDepartment"></span></p>

            <p><strong>Reference No:</strong> <span id="detailsModalRefNo"></span></p>
            <p><strong>Advertisement Type:</strong> <span id="detailsModalAdvertisementType"></span></p>
            <p><strong>Newspapers:</strong> <span id="detailsModalNewspapers"></span></p>
            <p><strong>Amount:</strong> <span id="detailsModalAmount"></span></p>

          </div>

          <div class="col-md-6">
            <p><strong>MIPR No:</strong> <span id="detailsModalMiprNo"></span></p>
            <p><strong>Payment By:</strong> <span id="detailsModalPaymentBy"></span></p>
            <p><strong>Reference Date:</strong> <span id="detailsModalRefDate"></span></p>
            <p><strong>Number of Entries:</strong> <span id="detailsModalInsertions"></span></p>
            <p><strong>CM:</strong> <span id="detailsModalCM"></span></p>
            <p><strong>Columns:</strong> <span id="detailsModalColumns"></span></p>
            <p><strong>Remarks:</strong> <span id="detailsModalRemarks"></span></p>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>



<main id="main" class="main">

  <div class="pagetitle">
    <h1>Advertisements</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Advertisement Management</li>
      </ol>
    </nav>
    <div class="col-md-12 text-end">
      <span id="mipr-number" class="text-secondary">
        <h5>Last Issued MIPR No: <b><span id="mipr-last">Fetching...</span></b></h5>
      </span>
    </div>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <!-- Bordered Tabs Justified -->
            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="myTab" role="tablist">
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 active" id="user-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-food" type="button" role="tab" aria-controls="home"
                  aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Advertisement List</button>
              </li>
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="users-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-foods" type="button" role="tab" aria-controls="profile"
                  aria-selected="false"><i class="ri-file-add-line"></i> &nbsp; Create/Edit Advertisement</button>
              </li>
            </ul>
            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
              <div class="tab-pane fade show active" id="bordered-justified-food" role="tabpanel"
                aria-labelledby="user-tab">

                <div class="alert alert-success alert-block table_msg3" style="display:none;">
                  <strong>Data deleted successfully</strong>
                </div>
                <div class="alert alert-danger table_msg4" style="display:none;">
                  <strong>Whoops!</strong> There were some problems with the application.
                </div>

                <table class="user-table" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center" scope="col" width="5%">#</th>
                      <th class="text-center" scope="col" width="10%">Issued On</th>
                      <th class="text-center" scope="col" width="10%">MIPR No</th>
                      <th class="text-center" scope="col" width="20%">Department</th>
                      <th class="text-center" scope="col" width="15%">Actions</th>
                      <th class="text-center" scope="col" width="5%">RO</th>
                      <th class="text-center" scope="col" width="10%">Status</th>
                      <th class="text-center" scope="col" width="5%">Published</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade" id="bordered-justified-foods" role="tabpanel" aria-labelledby="users-tab">
                <form id="advertisement-form" method="POST">
                  @csrf
                  <input type="hidden" id="id" name="id">
                  <div class="alert alert-success alert-block table_msg1" style="display:none;">
                    <strong>Data submitted successfully</strong>
                  </div>
                  <div class="alert alert-danger table_msg2" style="display:none;">
                    <strong>Whoops!</strong> There were some problems with your input.
                  </div>
                  <div class="alert alert-success alert-block table_msg5" style="display:none;">
                    <strong>Data edited successfully</strong>
                  </div>
                  <div class="alert alert-danger table_msg6" style="display:none;">
                    <strong>Whoops!</strong> There were some problems with your input.
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <label for="ref_no" class="form-label"><b>MIPR No</b></label>
                      <input type="text" class="form-control readonly-input" id="mipr_no" name="mipr_no" required
                        autocomplete='off' readonly>
                    </div>
                    <div class="col-md-6">
                      <label for="issue_date" class="form-label"><b>Date of Issue</b></label>
                      <input type="text" class="form-control" id="issue_date" name="issue_date" maxlength="10" required
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <label for="department_category" class="form-label"><b>Department Category</b></label>
                      <select id="department_category" name="department_category" class="form-control" required>
                        <option value="" disabled selected>--Select Dept Category--</option>
                        @foreach ($department_categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="department" class="form-label"><b>Department</b></label>
                      <select id="department" name="department" class="form-control select2" required>
                      </select>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <label for="ref_no" class="form-label"><b>Reference No (Letter No)</b></label>
                      <input type="text" class="form-control" id="ref_no" name="ref_no" required autocomplete='off'>
                    </div>
                    <div class="col-md-6">
                      <label for="ref_date" class="form-label"><b>Reference Date</b></label>
                      <input type="text" class="form-control" id="ref_date" name="ref_date" required autocomplete='off'
                        readonly>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <label for="payment_by" class="form-label"><b>Payment By</b></label>
                      <div>
                        <select name="payment_by" id="payment_by" class="form-control" data-placeholder="Select">
                          <option value="" disabled selected>--Select Department--</option>
                          <option value="D">DIPR</option>
                          <option value="C">Concerned Department</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="advertisementType" class="form-label"><b>Advertisement Type</b></label>
                      <div>
                        <select name="advertisementType" id="advertisementType" class="form-control "
                          data-placeholder="Select a subject">
                          <option value="">--Select Advertisement Type--</option>
                          @foreach($advertisementType as $type)
                          <option value="{{ $type->id }}">{{ $type->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6" id="categoryContainer" style="display:none">
                      <label for="category" class="form-label"><b>Category</b></label>
                      <div>
                        <select name="category" id="category" class="form-control "
                          data-placeholder="Select a category">
                          <option value="" disabled selected>--Select category--</option>
                          @foreach($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6" id="subjectContainer" style="display:none">
                      <label for="subject" class="form-label"><b>Subject</b></label>
                      <div>
                        <select name="subject" id="subject" class="form-control" data-placeholder="Select a subject">
                          <option value="" disabled selected>--Select Subject--</option>
                          @foreach($subjects as $subject)
                          <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6" id="colorContainer" style="display:none">
                      <label for="color" class="form-label"><b>Color</b></label>
                      <div>
                        <select name="color" id="color" class="form-control " data-placeholder="Select a color">
                          <option value="" disabled selected>--Select Color--</option>
                          @foreach($color as $type)
                          <option value="{{ $type->id }}">{{ $type->color_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-6" id="pageInfoContainer" style="display:none;">
                      <label for="page_info" class="form-label"><b>Page Information</b></label>
                      <div>
                        <select name="page_info" id="page_info" class="form-control"
                          data-placeholder="Select page info">
                          <option value="" disabled selected>--Select Page Info--</option>
                          @foreach($page_info as $type)
                          <option value="{{ $type->id }}">{{ $type->page_info_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6" id="messageContainer" style="display:none">
                    <label for="messageSubject" class="form-label"><b>Message Subject</b></label>
                    <input type="text" name="message_subject" id="message_subject" class="form-control"
                      placeholder="Enter message subject"><br>
                    <label for="messageBody" class="form-label"><b>Message Body</b></label>
                    <textarea name="message_body" id="message_body" class="form-control" rows="6"
                      placeholder="Enter message body">
                      <p>Sir/Madam,</p>
                      <p>With reference to the above, please find enclosed herewith the 'Message' of the Hon'ble Governor of Meghalaya on the occasion of the <em><strong>'Seng Kut Snem'</strong></em> to be published in your esteemed Newspaper on the <span style="text-decoration: underline;"><strong>23rd Novermber, 2024</strong></span> <strong>in the FRONT PAGE positively in the</strong> <strong>size 10cms x 3columns.</strong></p>
<p><span style="text-decoration: underline;"><em><strong>Please note that as per the Warrant of Precedence, the message of the Hon'ble Governor should be placed on top followed below by the messages of the Hon'ble Chief Minister and Hon'ble Speaker.</strong></em></span></p>
<p>Bill may be addressed to Joint Secretary to the Governor of Meghalaya, Governor's Secretariat, Meghalaya, Shillong, through DIPR for arranging of payment.</p>
                    </textarea><br>

                    <input type="text" name="message_copy_to" id="message_copy_to" class="form-control"
                      placeholder="Enter copy to">

                  </div>


                  <div class="col-md-12 mt-3 mb-3">
                    <b>Assign Organization(s) to Publishing Dates</b>
                    <div class="border p-4 rounded">
                      <div class="row">
                        <div class="col-6">
                          <small class="text-muted d-block mt-1">
                            *Number next to each organization shows total ads allotted in past 7 days
                          </small>
                        </div>
                        <div class="col-6">
                          <div class="text-end mb-3">
                            <button type="button" class="btn btn-outline-primary" onclick="addAssignedNewsRow()">
                              <i class="fas fa-plus-circle"></i> Add Row
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- Table for Date and Newspaper Assignments -->
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="assignedNewsTable">
                          <thead class="thead-light">
                            <tr>
                              <th width="15%">Positively On</th>
                              <th width="75%">Organization(s)</th>
                              <th width="10%">Action</th>
                            </tr>
                          </thead>
                          <tbody id="assignedNewsTableBody"></tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="mt-4">
                    <b>Insertion Counts</b>
                    <table class="table table-bordered" id="insertionCountsTable">
                      <thead>
                        <tr>
                          <th>Organization(S)</th>
                          <th>Insertions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Rows will be dynamically added -->
                      </tbody>
                    </table>
                  </div>

                  <div class="col-md-12">
                    <label for="remarks" class="form-label"><b>Remarks</b></label>
                    <input id="remarks" type="text" class="form-control" name="remarks" autocomplete='off'>
                  </div>

                  <br>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
                  </div>
                </form>
              </div>

            </div><!-- End Bordered Tabs Justified -->

          </div>
        </div>
      </div>
      <!-- Modal for Form Submission Success -->
      <div class="modal fade" id="formSubmissionModal" tabindex="-1" role="dialog"
        aria-labelledby="formSubmissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="formSubmissionModalLabel">Issue Register Details </h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <p><b>Date of Issue:</b> <span id="modalIssueDate"></span></p>
              <p><b>Department:</b> <span id="modalDepartment"></span></p>
              <p><b>Type:</b> <span id="modalAdvertisementType"></span></p>
              <p id="modalCategoryContainer" style="display: none;"><b>Category:</b> <span id="modalCategory"></span>
              </p>
              <p><b>Reference No.:</b> <span id="modalRefNo"></span></p>
              <p><b>Reference Date:</b> <span id="modalRefDate"></span></p>
              <p><b>Positively On:</b> <span id="modalPositively"></span></p>
              <p><b>Remarks:</b> <span id="modalRemarks"></span></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
            </div>
          </div>
        </div>
      </div>
      <!--End of Modal -->


  </section>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
</main>
<style>
  .pointer {
    cursor: pointer;
  }
</style>



<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.js"
  integrity="sha512-shDVoXhqpazAEKzSzJQTn5mAtynJ5eIl8pNX2Ah25/GZvZWDEJ/EKiVwfu7DGo8HnIwxlbu4xPi+C0SsHWCCNw=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{asset('assets/js/modules/user/advertisement.js')}}"></script>

@endsection