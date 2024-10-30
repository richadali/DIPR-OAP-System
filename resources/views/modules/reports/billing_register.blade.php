@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Billing Register</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Billing Register Management</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="container">
              <div class="row justify-content-center">
                <!-- Existing date fields -->
                <div class="col-md-3" align="center">
                  <label for="from" class="form-label">Bill Date From:</label>
                  <div class="input-group">
                    <input id="from" type="text" class="form-control" name="from" autocomplete="off"
                      placeholder="dd-mm-yyyy" readonly>
                    <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                  </div>
                </div>
                <div class="col-md-3" align="center">
                  <label for="to" class="form-label">Bill Date To:</label>
                  <div class="input-group">
                    <input id="to" type="text" class="form-control" name="to" autocomplete="off"
                      placeholder="dd-mm-yyyy" readonly>
                    <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                  </div>
                </div>
              </div>
              <br>
              <div class="row justify-content-center">
                <!-- New searchable dropdowns for department and newspaper -->
                <div class="col-md-3" align="center">
                  <label for="department" class="form-label">Department:</label>
                  <select id="department" class="form-control department" data-placeholder="Select Department">
                    <option value="" selected disabled>Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3" align="center">
                  <label for="newspaper" class="form-label">Newspaper:</label>
                  <select id="newspaper" class="form-control newspaper" data-placeholder="Select Newspaper">
                    <option value="" selected disabled>Select Newspaper</option>
                    @foreach($newspapers as $newspaper)
                    <option value="{{ $newspaper->id }}">{{ $newspaper->news_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <br>
              <div class="row justify-content-center">
                <!-- Radio buttons for time period -->
                <div class="col-md-6" align="center">
                  <label class="form-label">Time Period:</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="timePeriod" id="monthly" value="monthly">
                    <label class="form-check-label" for="monthly">Monthly</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="timePeriod" id="quarterly" value="quarterly">
                    <label class="form-check-label" for="quarterly">Quarterly</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="timePeriod" id="yearly" value="yearly">
                    <label class="form-check-label" for="yearly">Yearly</label>
                  </div>
                </div>
              </div>
              <br>
              <div class="row justify-content-center">
                <!-- Existing button -->
                <div class="col-md-3" align="center">
                  <input type="button" class="btn btn-primary btn-get-billing-register" value="Get Billing Register"
                    id="btn-get-billing-register">
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-3" align="center">
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
                      <th class="text-center" scope="col" width="20%">Branch of the Department</th>
                      <th class="text-center" scope="col" width="15%">Newspapers isssued</th>
                      <th class="text-center" scope="col" width="8%">Release Order No.</th>
                      <th class="text-center" scope="col" width="20%">Date</th>
                      <th class="text-center" scope="col" width="8%">Bill No</th>
                      <th class="text-center" scope="col" width="8%">Bill Date</th>
                      <th class="text-center" scope="col" width="8%">Amount</th>
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