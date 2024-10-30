@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>MIPR Number</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">MIPR</li>
          <li class="breadcrumb-item active">Number</li>
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
                  <button class="nav-link w-100 active" id="mipr-no-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-mipr-no" type="button" role="tab" aria-controls="mipr-no" aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;MIPR Number</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade show active" id="bordered-justified-mipr-no" role="tabpanel" aria-labelledby="mipr-no-tab">
              
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
                    <th class="text-center" scope="col" width="15%">MIPR Number</th>
                    <th class="text-center" scope="col" width="20%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                </table>

                <!-- Modal Start -->
                <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="false">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">MIPR Number Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
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
                        <form id="mipr-no-form" method="POST">
                            @csrf
                            <input type="hidden" id="id" name="id">
                            <div class="form-group">
                                <label for="mipr_no" class="form-label"><b>MIPR Number</b></label>
                                <input type="text" class="form-control" id="mipr_no" name="mipr_no" maxlength="4" required>
                                <input type="hidden" class="form-control" id="fin_year" name="fin_year" required>
                            </div>
                            <br>
                            <div class="text-center">
                              <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                              <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
                            </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div><!-- End Modal -->
              </div>
                
              </div><!-- End Bordered Tabs Justified -->

            </div>
        </div>
      </div>
    </section>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  </main>
<style>
    .pointer {cursor: pointer;}
</style>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

<script src="{{asset('assets/js/modules/admin/mipr_no.js')}}"></script>
@endsection
