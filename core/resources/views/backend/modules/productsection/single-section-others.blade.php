@extends('backend.layouts.master')

@section('meta')
    <title>All Products - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h3><span class="badge bg-primary">{{ $singleSection->name }}</span></h2>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{ route('sections.index') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Back To Sections</a>
        </div>
    </div>


    <div class="card">
        <div class="card-body p-2">
            <div class="row">
                <div class="mb-3 text-center">
                    <a href="#"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add To New Product This Sections"><button
                            type="button" class="btn btn-success">All Latest Products</button></a>
                </div>

            </div>
        </div>
    </div>
    <div class="card table-list-card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">

                    </div>
                </div>
            </div>
            <div class="table-responsive">

                <table id="productTable" class="table  table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th class="no-sort">Name</th>
                            <th class="no-sort">Category</th>
                            <th class="no-sort">Author</th>
                            <th class="no-sort">Publisher</th>
                            <th class="no-sort">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (productSection($singleSection->section_type) as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->english_name ?? '' }}</td>
                                <td>{!! implode('<br>', $item->categories->pluck('name')->unique()->toArray()) !!}</td>
                                <td>{!! implode('<br>', $item->authors->pluck('name')->unique()->toArray()) !!}</td>
                                <td>{{ $item->publisher->name ?? '' }}</td>
                                <td>{{ $item->current_price ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>




            </div>
        </div>
    </div>



    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" class="img-fluid" alt="Image Preview">
                </div>
            </div>
        </div>
    </div>
@endsection

