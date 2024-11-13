@extends('layouts.app_1')

@section('content')
<style>
  .card-header {
    font-weight: bold;
    font-size: 1.25rem;
  }

  .input-group-text {
    background-color: #f8f9fa;
    border: none;
  }

  .form-label {
    font-weight: bold;
    font-size: 1rem;
    color: #333;
  }

  .form-control {
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
  }

  select.form-control {
    height: 2.75rem;
  }

  .btn-get-billing-register {
    font-weight: bold;
    background-color: #007bff;
    border: none;
  }

  .btn-get-billing-register:hover {
    background-color: #0056b3;
  }

  #department,
  #newspaper {
    width: 100%;
    padding: 0.75rem;
  }

  #department,
  #newspaper,
  #from,
  #to {
    max-width: 100%;
  }
</style>
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Billing Register <span class="fs-6 text-secondary">(Bills paid by DIPR)</span></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Billing Register Management</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-lg mt-4 pt-3">
          <div class="card-body">
            <div class="container">
              <!-- Date Fields Row -->
              <div class="row justify-content-center mb-4">
                <div class="col-md-3 text-center">
                  <label for="from" class="form-label">Bill Date From:</label>
                  <div class="input-group">
                    <input id="from" type="text" class="form-control" name="from" autocomplete="off"
                      placeholder="dd-mm-yyyy" readonly>
                    <span class="input-group-text bg-light"><i class="ri-calendar-line"></i></span>
                  </div>
                </div>
                <div class="col-md-3 text-center">
                  <label for="to" class="form-label">Bill Date To:</label>
                  <div class="input-group">
                    <input id="to" type="text" class="form-control" name="to" autocomplete="off"
                      placeholder="dd-mm-yyyy" readonly>
                    <span class="input-group-text bg-light"><i class="ri-calendar-line"></i></span>
                  </div>
                </div>
                <div class="col-md-2 text-center align-self-end">
                  <label class="form-label">&nbsp;</label> <!-- Empty label for alignment -->
                  <button class="btn btn-success w-100" id="clearDates" type="button">Clear Dates</button>
                </div>
              </div>

              <!-- Departments Dropdown Row -->
              <div class="row justify-content-center mb-3">
                <div class="col-md-8">
                  <label for="department" class="form-label">Departments:</label>
                  <select id="department" class="form-control department" data-placeholder="Select Departments"
                    multiple>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- Newspapers Dropdown Row -->
              <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                  <label for="newspaper" class="form-label">Organizations:</label>
                  <select id="newspaper" class="form-control newspaper" data-placeholder="Select Organizations"
                    multiple>
                    @foreach($newspapers as $newspaper)
                    <option value="{{ $newspaper->id }}">{{ $newspaper->news_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- Action Buttons Row -->
              <div class="row justify-content-center mb-3">
                <div class="col-md-4 text-center">
                  <input type="button" class="btn btn-primary btn-get-billing-register w-100"
                    value="Get Billing Register" id="btn-get-billing-register">
                </div>
              </div>

              <!-- Print Button Placeholder Row -->
              <div class="row justify-content-center">
                <div class="col-md-4 text-center">
                  <div id="printButtonPlaceholder"></div>
                </div>
              </div>
            </div>
          </div>
        </div>




        <div class="card">
          <div class="card-body">

            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="myTab" role="tablist">
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 active" id="user-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-food" type="button" role="tab" aria-controls="home"
                  aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Billing Register</button>
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

                <table class="billingRegister-table" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center" scope="col" width="3%">#</th>
                      <th class="text-center" scope="col" width="20%">Department</th>
                      <th class="text-center" scope="col" width="15%">Isssued Organizations</th>
                      <th class="text-center" scope="col" width="8%">RO No.</th>
                      <th class="text-center" scope="col" width="5%">RO Date</th>
                      <th class="text-center" scope="col" width="8%">Bill No</th>
                      <th class="text-center" scope="col" width="8%">Bill Date</th>
                      <th class="text-center" scope="col" width="10%">Amount</th>
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
            </div>

          </div>
        </div>
      </div>

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


<script src="{{asset('assets/js/modules/reports/billing_register.js')}}"></script>
@endsection