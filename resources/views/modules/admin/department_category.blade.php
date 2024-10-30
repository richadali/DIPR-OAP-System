@extends('layouts.app_1')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Department Category List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Department Categories</li>
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
                                <button class="nav-link w-100 active" id="department-category-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-justified-category" type="button" role="tab"
                                    aria-controls="home" aria-selected="true"><i class="bi bi-card-list"></i>
                                    &nbsp;Department Categories</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="add-category-tab" data-bs-toggle="tab"
                                    data-bs-target="#bordered-justified-add-category" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false"><i class="ri-file-add-line"></i>
                                    &nbsp; Add/Edit Department Category</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade show active" id="bordered-justified-category" role="tabpanel"
                                aria-labelledby="department-category-tab">

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
                                            <th class="text-center" scope="col" width="75%">Category Name</th>
                                            <th class="text-center" scope="col" width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="bordered-justified-add-category" role="tabpanel"
                                aria-labelledby="add-category-tab">
                                <form id="department-category-form" method="POST">
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
                                        <label for="category_name" class="form-label"><b>Category Name</b></label>
                                        <input type="text" class="form-control" id="category_name" name="category_name" required>
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

<script src="{{asset('assets/js/modules/admin/department_category.js')}}"></script>
@endsection
