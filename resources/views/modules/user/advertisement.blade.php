@extends('layouts.app_1')

@section('content')


<main id="main" class="main">

  <div class="pagetitle">
    <h1>Advertisements</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Advertisement Management</li>
      </ol>
    </nav>
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
                      <th class="text-center" scope="col" width="15%">Date of Issue</th>
                      <th class="text-center" scope="col" width="15%">Department Name</th>
                      <th class="text-center" scope="col" width="10%">Type</th>
                      <th class="text-center" scope="col" width="15%">Issued Newspaper</th>
                      <th class="text-center" scope="col" width="10%">Amount (₹)</th>
                      <th class="text-center" scope="col" width="10%">Actions</th>
                      <th class="text-center" scope="col" width="10%">Release Order</th>
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
                    <input type="text" class="form-control" id="issue_date" name="issue_date" maxlength="10" required>
                  </div>

                  <div class="col-md-6">
                    <label for="department" class="form-label"><b>Department</b></label>
                    <input type="text" class="form-control" id="department" name="department" required
                      autocomplete='off'>
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

                  {{-- <div class="col-md-6">
                    <label for="size" class="form-label"><b>Size</b></label>
                    <input id="size" type="text" class="form-control" name="size" required autocomplete="off">
                  </div> --}}

                  <div class="col-md-6">
                    <label for="base_amount" class="form-label"><b>Amount</b></label>
                    <input id="base_amount" type="text" class="form-control" name="base_amount" disabled required readonly>
                  </div>

                  <div class="col-md-6">
                    <label for="gst_rate" class="form-label"><b>GST (%)</b></label>
                    <input id="gst_rate" type="text" class="form-control" name="gst_rate" disabled required readonly>
                  </div>

                  <div class="col-md-6">
                    <label for="amount" class="form-label"><b>Grand Total</b></label>
                    <input id="amount" type="text" class="form-control" name="amount" disabled required required>
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
                    <label for="ref_no" class="form-label"><b>Reference No (MIPR No)</b></label>
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
                    <input type="text" class="form-control" id="insertions" name="insertions" maxlength="10" required disabled
                      autocomplete='off' readonly>
                  </div>

                  <div class="col-md-6">
                    <label for="newspaper" class="form-label"><b>Newspaper(s)</b></label>
                    <div>
                      <select name="newspaper[]" id="newspaper" class="form-control select2" multiple
                        data-placeholder="Select Newspaper">
                        <!-- <option value="" disabled selected>Select Newspaper</option> -->
                        @foreach($newspapers as $newspaper)
                        <option value="{{ $newspaper->id }}">{{ $newspaper->news_name }}</option>
                        @endforeach
                      </select>
                    </div>
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


                  <div class="col-md-6" id="pageInfoContainer" style="display:none">
                    <label for="page_info" class="form-label"><b>Page Information</b></label>
                    <div>
                      <select name="page_info" id="page_info" class="form-control " data-placeholder="Select page info">
                        <option value="" disabled selected>--Select Page Info--</option>
                        @foreach($page_info as $type)
                        <option value="{{ $type->id }}">{{ $type->page_info_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  {{-- <div class="col-md-6">
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
                  </div> --}}

                  <div class="col-md-6">
                    <label for="letter_no" class="form-label"><b>Departmental Letter No</b></label>
                    <input id="letter_no" type="text" class="form-control" name="letter_no" autocomplete='off' required>
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
              <p><b>Amount:</b> <span id="modalAmount"></span></p>
              <p><b>Subject:</b> <span id="modalSubject"></span></p>
              <p><b>Reference No.:</b> <span id="modalRefNo"></span></p>
              <p><b>Reference Date:</b> <span id="modalRefDate"></span></p>
              <p><b>Positively On:</b> <span id="modalPositively"></span></p>
              <p><b>No of Insertions:</b> <span id="modalInsertions"></span></p>
              <p><b>Newspaper(s):</b> <span id="modalNewspaper"></span></p>
              <p><b>Dept. Letter No:</b> <span id="modalLetterNo"></span></p>
              <p><b>Remarks:</b> <span id="modalRemarks"></span></p>

              <!-- Add other form fields here -->

              <!-- View Release Order Button -->
              <!-- <a href="#" id="viewReleaseOrderBtn" class="btn btn-primary">View Release Order</a> -->
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