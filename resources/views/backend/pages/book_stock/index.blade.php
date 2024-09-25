
@extends('backend.layouts.master')

@section('title')
Book Stock- Admin Panel
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Book</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.book.index') }}">Book Stock</a></li>
                    <li><span>book list</span></li>
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
                    
                    
                    <h1>Book Stock</h1>

                    <!-- Show buttons for ALC and OKCL challans -->
                    {{-- <div class="mb-3">
                        <button id="showAlcChallans" class="btn btn-primary">Show ALC Challans</button>
                        <button id="showOkclChallans" class="btn btn-secondary">Show OKCL Challans</button>
                    </div> --}}
                
                    <!-- Book Stock Table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User List</th>
                                <th>Total Book</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="bookStockTable">
                            @foreach($book_data as $stock)
                                <tr>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->totalbook }}</td>
                                    <td>{{ $stock->totalsum}}</td>
                                    <td><button type="button" class="btn btn-primary">View</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>


@endsection

@section('scripts')

    <!-- JavaScript to toggle between ALC and OKCL challans -->
    <script>
        document.getElementById('showAlcChallans').addEventListener('click', function() {
            window.location.href = "{{ route('challans.alc') }}";
        });

        document.getElementById('showOkclChallans').addEventListener('click', function() {
            window.location.href = "{{ route('challans.okcl') }}";
        });
    </script>
@endsection