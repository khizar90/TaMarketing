@extends('layouts1.base')
@section('title', 'Pending Order')
@section('main', 'Order Managements')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-3">Orders</h5>
                        <div class="">
                            {{-- <button class="btn btn-primary btn-sm" id="clearFiltersBtn">Clear Filter</button> --}}
                        </div>
                    </div>



                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif




                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">


                        <div class="row me-2">
                            <div class="col-md-2">
                                <div class="me-3">

                                </div>
                            </div>
                            <div class="col-md-10">
                                {{-- <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                        <label class="user_search">
                                            <input type="text" class="form-control" id="searchInput"
                                                placeholder="Search.." value="" aria-controls="DataTables_Table_0">
                                        </label>
                                    </div>

                                </div> --}}
                            </div>
                        </div>

                        <table class="table border-top dataTable" id="usersTable">
                            <thead>
                                <tr>

                                    <th>Order by</th>
                                    <th>ORder id</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="searchResults">
                                @foreach ($orders as $item)
                                    <tr class="odd">

                                        <td class="sorting_1">
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                @if ($item->user->image)
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar avatar-sm me-3"><img
                                                                src="{{ asset($item->user->image) }}" alt="Avatar"
                                                                class="rounded-circle">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar avatar-sm me-3"><span
                                                                class="avatar-initial rounded-circle bg-label-danger">
                                                                {{ strtoupper(substr($item->user->name, 0, 2)) }}</span>
                                                        </div>
                                                    </div>
                                                @endif



                                                <div class="d-flex flex-column"><a href=""
                                                        class="text-body text-truncate"><span
                                                            class="fw-semibold user-name-text">{{ $item->user->name }}</span></a><small
                                                        class="text-muted">&#64;{{ $item->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>



                                        <td>#{{ $item->id }}</td>
                                        {{-- <td class="account-status text-start">
                                            @if ($item->status == 0)
                                                <button class="badge bg-label-secondary btn" data-bs-toggle="modal"
                                                    data-bs-target="#verifyModal{{ $item->id }}"
                                                    text-capitalized="">pending
                                                </button>
                                            @endif
                                            @if ($item->status == 1)
                                                <button class="badge bg-label-primary btn" data-bs-toggle="modal"
                                                    data-bs-target="#startOrder{{ $item->id }}"
                                                    text-capitalized="">Accepted
                                                </button>
                                                <div class="modal fade" data-bs-backdrop='static'
                                                    id="startOrder{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm"
                                                        role="document">
                                                        <div class="modal-content verifymodal">
                                                            <div class="modal-header">
                                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                                    sure you want to start
                                                                    this order?
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="body">After starting you will not cancel the
                                                                    order</div>
                                                            </div>
                                                            <form
                                                                action="{{ url('/dashboard/order/change/status/' . $item->id . '/' . 2) }}"
                                                                method="POST">
                                                                @csrf


                                                                <hr class="hr">

                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="first">
                                                                            <a href="" class="btn"
                                                                                data-bs-dismiss="modal"
                                                                                style="color: #a8aaae ">Cancel</a>
                                                                        </div>
                                                                        <div class="second">
                                                                            <button type="submit"
                                                                                class="btn text-center">Start</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->status == 2)
                                                <button class="badge bg-label-warning btn" data-bs-toggle="modal"
                                                    data-bs-target="#delivered{{ $item->id }}"
                                                    text-capitalized="">Started
                                                </button>
                                                <div class="modal fade" data-bs-backdrop='static'
                                                    id="delivered{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm"
                                                        role="document">
                                                        <div class="modal-content verifymodal">
                                                            <div class="modal-header">
                                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                                    sure you want to delivered
                                                                    this order?
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="body">After delivering this order you will
                                                                    not change this</div>
                                                            </div>
                                                            <form
                                                                action="{{ url('/dashboard/order/change/status/' . $item->id . '/' . 3) }}"
                                                                method="POST">
                                                                @csrf


                                                                <hr class="hr">

                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="first">
                                                                            <a href="" class="btn"
                                                                                data-bs-dismiss="modal"
                                                                                style="color: #a8aaae ">Cancel</a>
                                                                        </div>
                                                                        <div class="second">
                                                                            <button type="submit"
                                                                                class="btn text-center">Deliver</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->status == 3)
                                                <span class="badge bg-label-info " text-capitalized="">Delivered
                                                </span>
                                            @endif

                                            @if ($item->status == 4)
                                                <span class="badge bg-label-success" text-capitalized="">Completed
                                                </span>
                                            @endif
                                            @if ($item->status == 5)
                                                <span class="badge bg-label-danger"  text-capitalized="">Canceled
                                                </span>
                                            @endif

                                            @if ($item->status == 0 || $item->status == 1)
                                                <button class="badge bg-label-danger btn" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $item->id }}"
                                                    text-capitalized="">cancel
                                                </button>
                                            @endif


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="verifyModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you
                                                                sure you want to accpet
                                                                this order?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body mb-2">If you will accpet this order you have
                                                                to
                                                                complete this order
                                                            </div>
                                                        </div>
                                                        <form
                                                            action="{{ url('/dashboard/order/change/status/' . $item->id . '/' . 1) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="body">
                                                                    <div class="row">
                                                                        <div class="col mb-2">
                                                                            <input type="number" id="nameWithTitle"
                                                                                name="price" class="form-control"
                                                                                placeholder="Price" required />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr class="hr">

                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Cancel</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <button type="submit"
                                                                            class="btn text-center">Accept</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>



                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you sure
                                                                you
                                                                want to cancel
                                                                this order?
                                                            </div>
                                                        </div>
                                                        <form
                                                            action="{{ url('/dashboard/order/change/status/' . $item->id . '/' . 5) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <textarea id="" name="reason" class="form-control" rows="3" placeholder="Reason" required></textarea>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr class="hr">

                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Close</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <button type="submit"
                                                                            class="btn text-center">Cancel Order</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td> --}}


                                        <td class="detailbtn">
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="{{ url('/dashboard/order/detail/' . $item->status . '/' . $item->id) }}"
                                                    class="dropdown-item">Detail
                                                </a>
                                                @if ($item->status == 1 || $item->status == 2 || $item->status == 3 || $item->status == 0)
                                                    <a href="{{ route('dashboard-order-conversation', $item->id) }}"
                                                        class="dropdown-item">Chat
                                                    </a>
                                                @endif
                                            </div>
                                        </td>



                                        {{-- <td class="" style="">
                                            <div class="d-flex align-items-center">


                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->uuid }}"
                                                    class="text-body delete-record">
                                                    <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                </a>
                                            </div>


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $user->uuid }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you
                                                                sure you want to delete
                                                                this account?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After delete this account user cannot
                                                                access anything in application</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn"
                                                                        data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center" href="">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>


                        {{-- <div id="paginationContainer">
                            <div class="row mx-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                        aria-live="polite">Showing {{ $users->firstItem() }} to
                                        {{ $users->lastItem() }}
                                        of
                                        {{ $users->total() }} entries</div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                        @if ($users->hasPages())
                                            {{ $users->links('pagination::bootstrap-4') }}
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {
                $('#searchInput').keyup(function() {

                });
            });
        </script>
    @endsection
