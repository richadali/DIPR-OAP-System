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
        <p><strong>Status:</strong> <span id="detailsModalStatus"></span></p>

        <!-- Progress bar -->
        <div class="container">
          <ul class="progressbar">
            <li>Created</li>
            <li>Published</li>
            <li>Billed</li>

          </ul>
        </div>

        <div id="cancellationReasonContainer" style="display: none; margin-top: 15px;">
          <p><strong>Cancellation Reason:</strong> <span id="cancellationReason"></span></p>
        </div>

        <div class="row">
          <div class="col-md-6">
            <p><strong>Issue Date:</strong> <span id="detailsModalIssueDate"></span></p>
            <p><strong>MIPR No:</strong> <span id="detailsModalMiprNo"></span></p>
            <p><strong>Department:</strong> <span id="detailsModalDepartment"></span></p>
            <p><strong>Advertisement Type:</strong> <span id="detailsModalAdvertisementType"></span></p>
            <p><strong>Newspapers:</strong> <span id="detailsModalNewspapers"></span></p>
            <p><strong>Amount:</strong> <span id="detailsModalAmount"></span></p>
            <p><strong>Reference No:</strong> <span id="detailsModalRefNo"></span></p>
            <p><strong>Reference Date:</strong> <span id="detailsModalRefDate"></span></p>
          </div>

          <div class="col-md-6">
            <p><strong>Payment By:</strong> <span id="detailsModalPaymentBy"></span></p>
            <p><strong>Number of Entries:</strong> <span id="detailsModalInsertions"></span></p>
            <p><strong>Release Order No:</strong> <span id="detailsModalReleaseOrderNo"></span></p>
            <p><strong>Release Order Date:</strong> <span id="detailsModalReleaseOrderDate"></span></p>
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
                      <th class="text-center" scope="col" width="15%">Issued On</th>
                      <th class="text-center" scope="col" width="15%">MIPR No</th>
                      <th class="text-center" scope="col" width="15%">Department</th>
                      <th class="text-center" scope="col" width="10%">Amount</th>
                      <th class="text-center" scope="col" width="15%">Actions</th>
                      <th class="text-center" scope="col" width="5%">RO</th>
                      <th class="text-center" scope="col" width="10%">Status</th>
                      <th class="text-center" scope="col" width="5%">Published</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>

                <!--Modal Start -->
                <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="false">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">user Notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="alert alert-danger msg1" style="display:none;">
                          <h7>Whoops! Title cannot be empty.</h7>
                        </div>

                        <div class="alert alert-danger msg2" style="display:none;">
                          <h7>Whoops! Body cannot be empty.</h7>
                        </div>

                        <div class="alert alert-success alert-block msg3" style="display:none;">
                          <strong>All notifications sent successfully</strong>
                        </div>
                        <div class="alert alert-danger msg4" style="display:none;">
                          <strong>Whoops!</strong> All notifications failed to send.
                        </div>
                        <div class="alert alert-success alert-block msg5" style="display:none;">
                          <strong>Some notifications failed to send</strong>
                        </div>

                        <div class="alert alert-danger alert-block msg6" style="display:none;">
                          <strong>Whoops!</strong> Error!
                        </div>

                        <form method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="answer" class="form-label"><b>Title</b></label>
                            <input type="text" class="form-control" id="title" name="title">
                          </div>
                          <div class="form-group">
                            <label for="answer" class="form-label"><b>Body</b></label>
                            <textarea class="form-control" name="body" id="body"></textarea>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="btn-send-user-notification" class="btn btn-primary btn-sm">Send
                          notification</button>
                      </div>
                    </div>
                  </div>

                </div><!-- End Disabled Backdrop Modal-->
                <div class="modal fade" id="userdocument_modal" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><span class="get-user-name"></span>'s &nbsp;<span
                            class='get-document-name'><span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <iframe class="document_path" withd="100%" height="100%"></iframe>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

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


                  <div class="col-md-6">
                    <label for="advertisementType" class="form-label"><b>Type</b></label>
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


                  <div class="col-md-6" id="categoryContainer" style="display:none">
                    <label for="category" class="form-label"><b>Category</b></label>
                    <div>
                      <select name="category" id="category" class="form-control " data-placeholder="Select a category">
                        <option value="" disabled selected>--Select category--</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <label for="issue_date" class="form-label"><b>Date of Issue</b></label>
                    <input type="text" class="form-control" id="issue_date" name="issue_date" maxlength="10" required
                      autocomplete="off">
                  </div>

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

                  <div class="col-md-6">
                    <label for="newspaper_type" class="form-label"><b>Media Type</b></label>
                    <select id="newspaper_type" name="newspaper_type" class="form-control" required>
                      <option value="" disabled selected>--Select Media Type--</option>
                      @foreach ($newspaper_types as $type)
                      <option value="{{ $type->id }}">{{ $type->news_type }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label for="newspaper" class="form-label"><b>Organization(s)</b>
                      <small class="text-muted d-block mt-1">
                        (Number next to each organization shows total ads allotted in past 7 days)
                      </small></label>
                    <div>
                      <select name="newspaper[]" id="newspaper" class="form-control select2" multiple
                        data-placeholder="Select Organization">
                        <option value="select-all">-Select All-</option> <!-- Select All option -->
                        <!-- Other options will be populated via Ajax -->
                      </select>
                    </div>
                  </div>


                  {{-- Rate Calculation --}}
                  <div id="printcalculation" style="display: none;">
                    <div class="col-md-2">
                      <label for="size" class="form-label"><b>Size(cm*col):</b></label>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-5">
                          <label for="cm"
                            class="form-label"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              cm </b></label>
                          <br>
                          <input id="cm" type="text" class="form-control" name="cm" required autocomplete="off">
                        </div>
                        <div class="col-md-5">
                          <label for="columns"
                            class="form-label"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;col</b></label>
                          <input id="columns" type="text" class="form-control" name="columns" required
                            autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6" id="videoSecondsContainer" style="display: none;">
                    <label for="seconds" class="form-label"><b>Number of Seconds:</b></label>
                    <input id="seconds" type="text" class="form-control" name="seconds" required autocomplete="off">
                  </div>


                  <div class="col-md-6" id="amountContainer" style="display:block;">
                    <label for="amount" class="form-label"><b>Amount</b></label>
                    <input id="amount" type="text" class="form-control readonly-input" name="amount" required>
                  </div>

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


                  <div class="col-md-6">
                    <label for="ref_no" class="form-label"><b>MIPR No</b></label>
                    <input type="text" class="form-control readonly-input" id="mipr_no" name="mipr_no" required
                      autocomplete='off' readonly>
                  </div>

                  <div class="col-md-6">
                    <label for="ref_no" class="form-label"><b>Reference No (Letter No)</b></label>
                    <input type="text" class="form-control" id="ref_no" name="ref_no" required autocomplete='off'>
                  </div>

                  <div class="col-md-6">
                    <label for="ref_date" class="form-label"><b>Reference Date</b></label>
                    <input type="text" class="form-control" id="ref_date" name="ref_date" required autocomplete='off'
                      readonly>
                  </div>


                  <div class="col-md-6">
                    <label for="positively" class="form-label"><b>Positively On</b></label>
                    <input id="positively" type="text" class="form-control" readonly name="positively" required
                      autocomplete='off'>
                  </div>


                  <div class="col-md-6">
                    <label for="insertions" class="form-label" id="insertions-label"><b>No of issues</b></label>
                    <input type="text" class="form-control readonly-input" id="insertions" name="insertions"
                      maxlength="10" required autocomplete='off' readonly>
                  </div>



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


                  <div class="col-md-6" id="pageInfoContainer" style="display:none;">
                    <label for="page_info" class="form-label"><b>Page Information</b></label>
                    <div>
                      <select name="page_info" id="page_info" class="form-control" data-placeholder="Select page info">
                        <option value="" disabled selected>--Select Page Info--</option>
                        @foreach($page_info as $type)
                        <option value="{{ $type->id }}">{{ $type->page_info_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
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
              <p id="modalCmContainer" style="display: none;"><b>Cms:</b> <span id="modalCm"></span></p>
              <p id="modalColumnsContainer" style="display: none;"><b>Columns:</b> <span id="modalColumns"></span></p>
              <p id="modalSecondsContainer" style="display: none;"><b>Seconds:</b> <span id="modalSeconds"></span></p>
              <p><b>Total Amount:</b> <span id="modalAmount"></span></p>
              <p><b>Reference No.:</b> <span id="modalRefNo"></span></p>
              <p><b>Reference Date:</b> <span id="modalRefDate"></span></p>
              <p><b>Positively On:</b> <span id="modalPositively"></span></p>
              <p><b>No of Insertions:</b> <span id="modalInsertions"></span></p>
              <p><b>Newspaper(s):</b> <span id="modalNewspaper"></span></p>
              <p><b>Dept. Letter No:</b> <span id="modalLetterNo"></span></p>
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