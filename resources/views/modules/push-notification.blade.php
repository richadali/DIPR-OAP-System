@extends('layouts.app_1')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Push Notification</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Push Notification</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success alert-block msg1" style="display:none;">
                            <strong>All notifications sent successfully</strong>
                    </div>
                    <div class="alert alert-danger msg2" style="display:none;">
                            <strong>Whoops!</strong> All notifications failed to send.           
                    </div>
                    <div class="alert alert-success alert-block msg3" style="display:none;">
                            <strong>Some notifications failed to send</strong>
                    </div>

                    <div class="alert alert-danger alert-block msg4" style="display:none;">
                    <strong>Whoops!</strong> Error!       
                    </div>

                    <form id="push-notification-form"  method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="answer" class="form-label"><b>Title</b></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="answer" class="form-label"><b>Body</b></label>
                            <textarea class="form-control" name="body" required></textarea>
                          </div>
                          <br>
                        <button type="submit" class="btn btn-primary btn-sm">Send Notification</button>
                    </form>

                </div>
            </div>
        </div>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<!-- <script src="{{asset('assets/js/modules/firebase.js')}}"><script> -->
<script src="{{asset('assets/js/modules/push-notification.js')}}"></script>

@endsection