
@extends('backend.layouts.master')

@section('title')
Request Book List - Admin Panel
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Book</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Request</a></li>
                    <li><span>Requested Book List</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->
<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Book Request List</h4>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        <div class="data-tables">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Quantity</th>
                                        <th>Requested Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookRequests as $request)
                                        <tr>
                                            <td>{{ $request->title }}</td>
                                            <td>{{ $request->quantity }}</td>
                                            <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ $request->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>
@endsection