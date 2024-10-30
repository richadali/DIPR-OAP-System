@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>GST Rates List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">GST Rates</li>
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
                                <button class="nav-link w-100 active" id="gst-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-justified-gst" type="button" role="tab"
                                    aria-controls="home" aria-selected="true"><i class="bi bi-card-list"></i>
                                    &nbsp;GST Rates</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="gst-add-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-justified-gst-add" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false"><i class="ri-file-add-line"></i>
                                    &nbsp; Add/Edit GST Rate</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade show active" id="bordered-justified-gst" role="tabpanel"
                                aria-labelledby="gst-tab">

                                <div class="alert alert-success alert-block table_msg3" style="display:none;">
                                    <strong>Data deleted successfully</strong>
                                </div>
                                <div class="alert alert-danger table_msg4" style="display:none;">
                                    <strong>Whoops!</strong> There were some problems with the application.
                                </div>

                                <table class="gst-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="5%">#</th>
                                            <th class="text-center" scope="col" width="15%">GST Rate (%)</th>
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
                                                <h5 class="modal-title">GST Notification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger msg1" style="display:none;">
                                                    <h7>Whoops! GST rate cannot be empty.</h7>
                                                </div>
                                                <div class="alert alert-success alert-block msg3" style="display:none;">
                                                    <strong>All notifications sent successfully</strong>
                                                </div>
                                                <div class="alert alert-danger msg4" style="display:none;">
                                                    <strong>Whoops!</strong> All notifications failed to send.
                                                </div>

                                                <form method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="gst-rate" class="form-label"><b>GST Rate
                                                                (%)</b></label>
                                                        <input type="text" class="form-control" id="gst-rate"
                                                            name="gst_rate">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" id="btn-send-gst-notification"
                                                    class="btn btn-primary btn-sm">Send notification</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Disabled Backdrop Modal-->

                            </div>

                            <div class="tab-pane fade" id="bordered-justified-gst-add" role="tabpanel"
                                aria-labelledby="gst-add-tab">
                                <form id="gst-form" method="POST">
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
                                        <label for="gst_rate" class="form-label"><b>GST Rate (%)</b></label>
                                        <input type="number" class="form-control" id="gst_rate" name="gst_rate"
                                            required>
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

<script src="{{asset('assets/js/modules/admin/gst_rates.js')}}"></script>
@endsection