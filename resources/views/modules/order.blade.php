@extends('layouts.app_1')

@section('content')

<main id="main" class="main">
@csrf
    <div class="pagetitle">
      <h1>Orders</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Orders</li>
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
                  <button class="nav-link w-100 active" id="order-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-food" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="bi bi-card-list"></i> &nbsp;Orders List</button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="orders-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-orders" type="button" role="tab" aria-controls="profile" aria-selected="false" readonly><i class="ri-file-add-line"></i>  &nbsp; View Orders</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade show active" id="bordered-justified-food" role="tabpanel" aria-labelledby="order-tab">
              
                <div class="alert alert-success alert-block table_msg3" style="display:none;">
                          <strong>Data deleted successfully</strong>
                  </div>
                  <div class="alert alert-danger table_msg4" style="display:none;">
                          <strong>Whoops!</strong> There were some problems with the application.            
                  </div>

                <table class="order-table" width="100%"> 
                <thead>
                  <tr>
                    <th class="text-center" scope="col" width="10%">Order ID</th>
                    <th class="text-center" scope="col" width="15%">Client</th>
                    <th class="text-center" scope="col" width="5%">Tax</th>
                    <th class="text-center" scope="col" width="15%">Payment Status</th>
                    <th class="text-center" scope="col" width="10%">Active</th>
                    <th class="text-center" scope="col" width="15%">Updated At</th>
                    <th class="text-center" scope="col" width="20%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
               </div>

                <div class="tab-pane fade" id="bordered-justified-orders" role="tabpanel" aria-labelledby="orders-tab">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                          <div class="card-body">
                          <h5 class="card-title">View Orders</h5>
                          <form id="form1">
                            <div class="row mb-3">
                              <label class="col-sm-2"><b>Order ID</b></label>
                                <div class="col-sm-4">
                                  <span class="order_id "></span>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>Client Name</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="client_name"></label>
                              </div>
                            </div>
                            
                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 "><b>Active</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="active_status"></label>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>Phone no</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="phone_no"> </label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 "><b>Date of Order</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="date_of_order"></label>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>Updated at</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="updated_at"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 "><b>Hint</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="hint"></label>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>Status</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="status"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2  payment_type"><b>Mode of Payment</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class=""></label>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>Table/ Conference Name</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="table_conference"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2  payment_type"><b>Start Time</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="start_time"></label>
                              </div>
                              <label for="inputText" class="col-sm-2 "><b>End Time</b></label>
                                <div class="col-sm-4">
                                <label for="inputText" class="end_time"></label>
                              </div>
                            </div>

                          </form>
                          </div>
                    </div>
                  </div>
                    <div class="col-lg-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Food</h5>
                          <table class="table table-hover" id="food_tables">
                          <thead>
                            <tr>
                              <th scope="col" class="text-center">Name</th>
                              <th scope="col" class="text-center">Price</th>
                              <th scope="col" class="text-center">Quantiy</th>
                              <th scope="col" class="text-center">Total</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Add on</h5>
                          <table class="table table-hover" id="item_tables">
                          <thead>
                            <tr>
                              <th scope="col" class="text-center">Name</th>
                              <th scope="col" class="text-center">Price</th>
                              <th scope="col" class="text-center">Quantiy</th>
                              <th scope="col" class="text-center">Total</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-12">
                    <div class="card">
                          <div class="card-body">
                          <h5 class="card-title">Sub Total</h5>
                          <form id="form2">
                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Table/Conference Price</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="table_conference_price"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Food Price</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="food_total_price"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Item Price</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="item_total_price"></label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Tax</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="">-</label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Coupon Applied</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="">-</label>
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-7"><b></b></label>
                              <label for="inputText" class="col-sm-3"><b>Sub Total</b></label>
                                <div class="col-sm-2">
                                <label for="inputText" class="sub_total"></label>
                              </div>
                            </div>
                          </form>
                    </div>                    
                    </div>

                  </div>
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
<script src="{{asset('assets/js/modules/order.js')}}"></script>
@endsection