@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
@endsection

@section('page-style')
@endsection

@section('content')
    <div class="col-xl-12 col-md-12 col-12">
        <div class="card card-statistics">
            <div class="card-header">
                <h4 class="card-title">{{trans('admin.statistics')}}</h4>
                <div class="d-flex align-items-center">
                    <p class="card-text font-small-2 me-25 mb-0"></p>
                </div>
            </div>
            <div class="card-body statistics-body">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="d-flex flex-row">
                            <div class="my-auto">
                                <h4 class="fw-bolder mb-0">{{$usersCount}}</h4>
                                <p class="card-text font-small-3 mb-0">{{trans('admin.usersCount')}}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('vendor-script')

@endsection
@section('page-script')
@endsection
