@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Rates of Advertisements</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Rates of Advertisements</li>
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
                  aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Rates List</button>
              </li>
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="users-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-foods" type="button" role="tab" aria-controls="profile"
                  aria-selected="false"><i class="ri-file-add-line"></i> &nbsp; Add/Edit Rates</button>
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
                      <th class="text-center" scope="col" width="15%">Advertisement Type</th>
                      <th class="text-center" scope="col" width="15%">Advertisement Category</th>
                      <th class="text-center" scope="col" width="15%">Rate</th>

                      <!-- <th class="text-center" scope="col" width="15%">Head Office/ Location</th>
                    <th class="text-center" scope="col" width="15%">Email</th>
                    <th class="text-center" scope="col" width="15%">Phone No</th> -->
                      <th class="text-center" scope="col" width="20%">Actions</th>
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
                <form id="rate-form" method="POST">
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
                    <label for="advertisementType" class="form-label"><b>Advertisement Type</b></label>
                    <select id="advertisementType" class="form-control" name="advertisementType" required>
                      <option value="">--Select Advertisement Type--</option>
                      @foreach($advertisementType as $type)
                      <option value="{{ $type->id }}">{{ $type->name }}</option>
                      @endforeach
                    </select>
                    <input type="hidden" id="advertisement_type_hidden" name="advertisement_type_hidden">
                  </div>


                  <div class="col-md-6" id="ad-category-container" style="display:none;">
                    <label for="ad_category" class="form-label"><b>Advertisement Category</b></label>
                    <select name="ad_category" id="ad_category" class="form-control">
                      <option value=''>--Select a category--</option>
                      @foreach($adCategory as $category)
                      <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                      @endforeach
                    </select>
                    <input type="hidden" name="ad_category_hidden" id="ad_category_hidden">
                    <span id="error-rate" class="error-message"></span>
                  </div>


                  <div class="col-md-6">
                    <label for="rate" class="form-label"><b>Rate</b></label>
                    <span id="rateLabel" style="display: none;"><b>(inclusive of GST)</b></span>
                    <input type="text" class="form-control" id="rate" name="rate" required>
                  </div>

                  <div class="col-md-6" id="ad-category-container">
                    <input type="hidden" name="ad_category_hidden" id="ad_category_hidden">
                    <span id="error-rate" class="error-message"></span>
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
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

<script src="{{asset('assets/js/modules/admin/rate_of_advertisement.js')}}"></script>
@endsection