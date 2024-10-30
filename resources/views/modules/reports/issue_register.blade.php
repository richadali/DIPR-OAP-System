@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Issue Register</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Issue Register Management</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body1">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-3" align=center>
                            <label for="from" class="form-label" > Issue Register From: </label>
                            <div class="input-group">
                                <input id="from" type="text" class="form-control" name="from" required autocomplete="off" placeholder="dd-mm-yyyy" readonly>
                                <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            </div>
                        </div>
                        <div class="col-md-3" align=center>
                            <label for="to" class="form-label">Issue Register To:</label>
                            <div class="input-group">
                                <input id="to" type="text" class="form-control" name="to" required autocomplete="off" placeholder="dd-mm-yyyy" readonly>
                                <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-center">
                            <div class="col-md-2" align=center>
                                <input type="button" class="btn btn-primary btn-get-issue-register" value="Get Issue Register" name="btn-get-issue-register" id="btn-get-issue-register">
                            </div>
                          
                    </div>
                </div>
                <div class="row justify-content-center">
               
                  <div class="col-md-3" align=center>
                      <div id="printButtonPlaceholder" ></div>
                  </div>
              </div>
            </div>
        </div>
        <!-- <section class="section">
         
          <div class="loader-overlay" style="display: none;">
              <div class="loader">
                  <div class="spinner-border" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>
          </div>

      </section> -->

          <div class="card">
            <div class="card-body">
              <!-- Bordered Tabs Justified -->
              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="myTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="user-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-food" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Issue Register</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade show active" id="bordered-justified-food" role="tabpanel" aria-labelledby="user-tab">
              
                <div class="alert alert-success alert-block table_msg3" style="display:none;">
                          <strong>Data deleted successfully</strong>
                  </div>
                  <div class="alert alert-danger table_msg4" style="display:none;">
                          <strong>Whoops!</strong> There were some problems with the application.            
                  </div>

                <table class="issueRegister-table" width="100%"> 
                <thead>
                  <tr>
                    <th class="text-center" scope="col" width="10%">Mipr No</th>
                    <th class="text-center" scope="col" width="20%">Date of Issue</th>
                    <th class="text-center" scope="col" width="40%">Name of Department Concerned</th>
                    <th class="text-center" scope="col" width="20%">Ref. No & Date</th>
                    <th class="text-center" scope="col" width="">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                
                </tbody>
                </table>
                <!-- <tr class="loader-row" style="display: none;">
                    <td colspan="10" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr> -->

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
                      <button type="button" id="btn-send-user-notification" class="btn btn-primary btn-sm">Send notification</button>
                    </div>
                  </div>
                </div>

              </div><!-- End Disabled Backdrop Modal-->
                <div class="modal fade" id="userdocument_modal" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title"><span class="get-user-name"></span>'s &nbsp;<span class='get-document-name'><span></h5>
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
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  </main>
<style>
    .pointer {cursor: pointer;}
</style>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">


<script src="{{asset('assets/js/modules/reports/issue_register.js')}}"></script>
@endsection