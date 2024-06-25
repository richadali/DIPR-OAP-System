
@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>FAQ</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">FAQ</li>
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
                  <button class="nav-link w-100 active" id="faq-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;FAQ</button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-faq" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="ri-file-add-line"></i>  &nbsp; Create/Edit FAQ</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="faq-tab">
                <div class="alert alert-success alert-block table_msg3" style="display:none;">
                          <strong>Data deleted successfully</strong>
                  </div>
                  <div class="alert alert-danger table_msg4" style="display:none;">
                          <strong>Whoops!</strong> There were some problems with the application.            
                  </div>
                  <div class="alert alert-success table_msg7" style="display:none;">
                    <strong>Status changed successfully</strong>             
                  </div>
                  
                <table class="faq_table" width="100%"> 
                <thead>
                  <tr>
                    <th class="text-center" scope="col" width="10%">#</th>
                    <th class="text-center" scope="col" width="25%">Question</th>
                    <th class="text-center" scope="col" width="25%">Answer</th>
                    <th class="text-center" scope="col" width="30%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
               </div>

                <div class="tab-pane fade" id="bordered-justified-faq" role="tabpanel" aria-labelledby="faqs-tab">
                  <form id="faq-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-success alert-block table_msg1" style="display:none;">
                            <strong>Data submiited successfully</strong>
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
                    <input type="hidden" id="id" name="id">
                      <div class="col-md-6">
                        <label for="question" class="form-label"><b>Question</b></label>
                        <input type="text" class="form-control" name="question" id="question" required>
                      </div>
                      <div class="col-md-6">
                        <label for="answer" class="form-label"><b>Answer</b></label>
                        <input type="text" class="form-control" name="answer" id="answer" required>
                      </div>
                    <br>
                      <div class="text-center">
                        <button type="submit"  class="btn btn-primary btn-sm">Submit</button>
                        <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
                      </div>
                </form>
                </div>
                </div>
              </div><!-- End Bordered Tabs Justified -->

            </div>
        </div>
      </div>
    </section>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  </main>
  <script src="{{asset('assets/js/modules/faq.js')}}"></script>
@endsection