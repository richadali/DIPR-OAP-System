@extends('layouts.app_1')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Push Notification Settings</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Push Notification Settings</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success alert-block msg1" style="display:none;">
                            <strong>Server key added</strong>
                    </div>
                    <div class="alert alert-danger msg2" style="display:none;">
                            <strong>Whoops!</strong> Something went wrong.           
                    </div>
                    <div class="alert alert-success alert-block msg3" style="display:none;">
                            <strong>Server key updated</strong>
                    </div>
                    <div class="alert alert-danger msg4" style="display:none;">
                            <strong>Whoops!</strong> Something went wrong.           
                    </div>
                    <form id="push-notification-setting-form" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="key" class="form-label"><b>Server Key</b></label>
                            <input type="text" class="form-control" name="key" id="keys" value={{ $keys[0]['keys'] }} required>
                        </div>
                          <br>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </form>

                </div>
            </div>
        </div>
        <!-- <script src="{{asset('assets/js/modules/firebase.js')}}"><script> -->
        <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
        <script src="{{asset('assets/js/modules/push-notification.js')}}"></script>
@endsection