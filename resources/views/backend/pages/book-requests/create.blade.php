
@extends('backend.layouts.master')

@section('title')
Book Request Create- Admin Panel
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Book</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('book-requests.create') }}">Request</a></li>
                    <li><span>Create Request</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->
<div class="container">
    <div class="mt-5">
        @if ((auth::guard('admin')->user()->role === 'dlc') || (auth::guard('admin')->user()->role === 'alc'))
            <a href="{{route('book-requests.view')}}"><button type="button" class="btn btn-primary mb-5"  style="float:right">View Request Book List</button></a>
        @endif
        <h1>Create Book Request</h1>
    </div>
    <form action="{{ route('book-requests.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="book_id">Select Book:</label>
            <select name="book_id" id="book_id" class="form-control" required>
                @foreach($books as $book)
                    {{-- <option value="{{ $book->id }}">{{ $book->title }} by {{ $book->author }}</option> --}}
                    <option value="{{ $book->id }}">{{ $book->title }} </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
           
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Request Book</button>
    </form>
</div>
@endsection
