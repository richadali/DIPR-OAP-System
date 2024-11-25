@extends('layouts.app_1')

@section('content')

<style>
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
</style>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Bills</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Bill Management</li>
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
                  aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Bill List</button>
              </li>
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="users-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-foods" type="button" role="tab" aria-controls="profile"
                  aria-selected="false"><i class="ri-file-add-line"></i> &nbsp; Create/Edit Bill</button>
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

                <table class="bill-table" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center" scope="col" width="5%">#</th>
                      <th class="text-center" scope="col" width="10%">MIPR No</th>
                      <th class="text-center" scope="col" width="25%">Department</th>
                      <th class="text-center" scope="col" width="15%">Issued</th>
                      <th class="text-center" scope="col" width="10%">Bill No</th>
                      <th class="text-center" scope="col" width="10%">Bill Date</th>
                      <th class="text-center" scope="col" width="15%">Actions</th>
                      <th class="text-center" scope="col" width="5%"><small>Forwarding Letter</small></th>

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
                <form id="bill-form" method="POST">
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
                    <label for="ad_id" class="form-label"><b>Dept Reference No (Letter No)</b></label>
                    <div>
                      <select name="ad_id" id='ad_id' class="form-control select2"
                        data-placeholder="Select an advertisement" required>
                        <option value="" disabled selected>--Select reference no--</option>
                        @foreach($advertisements as $advertisement)
                        <option value="{{ $advertisement->id }}">{{$advertisement->ref_no }}
                          ({{$advertisement->department->dept_name}})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="empanelled_id" class="form-label"><b>Organization </b></label>
                    <div>
                      <select name="empanelled_id" id='empanelled_id' class="form-control "
                        data-placeholder="Select a advertisement" required>
                        <option value="" disabled selected>--Select Organization--</option>

                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <label for="amount" class="form-label"><b>Amount</b></label>
                    <div>
                      <input type="text" name="amount" id="amount" class="form-control" data-placeholder="Amount"
                        required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="gst_rate" class="form-label"><b>GST Rate</b></label>
                    <div>
                      <select name="gst_rate" id="gst_rate" class="form-control" required>
                        <option value="" selected disabled>Select GST Rate</option>
                        <option value="NA">NA</option>
                        @foreach($gstRates as $rate)
                        <option value="{{ $rate->rate }}">{{ $rate->rate }}%</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="total_amount" class="form-label"><b>Total Amount</b></label>
                    <div>
                      <input type="text" name="total_amount" id="total_amount" class="form-control readonly-input"
                        data-placeholder="Total Amount" required readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="bill_no" class="form-label"><b>Bill No</b></label>
                    <input type="text" class="form-control" id="bill_no" name="bill_no" required autocomplete='off'>
                  </div>

                  <div class="col-md-6">
                    <label for="bill_date" class="form-label"><b>Bill Date</b></label>
                    <input type="text" class="form-control" id="bill_date" name="bill_date" required autocomplete='off'
                      readonly>
                  </div>

                  <div class="col-md-6">
                    <label for="bill_date" class="form-label"><b>Bill Memo No</b></label>
                    <input type="text" class="form-control" id="bill_memo_no" name="bill_memo_no" required
                      autocomplete='off'>
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

<script src="{{asset('assets/js/modules/bills/bills.js')}}"></script>
@endsection