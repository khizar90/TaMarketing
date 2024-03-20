@extends('layouts1.base')
@section('title', 'Form')
@section('main', 'Payment Method')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/tagify/tagify.css" />

@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-3">Zelle List</h5>
                        <div class="">
                            @if ($zelle == null)
                                <button class="btn btn-secondary add-new btn-primary" tabindex="0"
                                    aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addNewBus"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                            class="d-none d-sm-inline-block">Add New Zelle</span></span></button>
                            @endif

                        </div>
                    </div>
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible mt-1" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible mt-1" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('delete'))
                        <div class="alert alert-danger alert-dismissible mt-1" role="alert">
                            {{ session()->get('delete') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="table border-top dataTable" id="usersTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Name</th>
                                    <th>Phonenumber</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr class="odd">
                                    @if ($zelle != null)
                                        <td class="">
                                            {{$zelle->name}}
                                        </td>
                                        <td class="">
                                            {{$zelle->phonenumber}}
                                        </td>
                                        <td class="detailbtn">
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="" data-bs-toggle="modal" data-bs-target="#edit"
                                                    class="dropdown-item">Edit
                                                </a>
                                                <a href="" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    class="dropdown-item">Delete
                                                </a>

                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static' id="deleteModal"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you sure
                                                                you
                                                                want to delete
                                                                this question?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After deleting the question you will add
                                                                a
                                                                new question</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn" data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center" href="{{ route('dashboard-zelle-delete', $zelle->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static' id="edit" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Edit
                                                                Zelle
                                                            </h5>
                                                        </div>
                                                        <form action="{{ route('dashboard-zelle-edit', $zelle->id) }}" enctype="multipart/form-data" id="addBusForm"
                                                            method="POST">
                                                            @csrf

                                                            <div class="modal-body">


                                                                <div class="row">
                                                                    <div class="col mb-2">
                                                                        <label for="nameWithTitle" class="form-label">Name</label>
                                                                        <input type="text" id="nameWithTitle" name="name" class="form-control"
                                                                            placeholder="Name" value="{{$zelle->name}}" required />
                                                                            <label for="nameWithTitle" class="form-label">Phonenumber</label>
                                                                        <input type="text" id="nameWithTitle" name="phonenumber" value= "{{$zelle->phonenumber}}" class="form-control"
                                                                            placeholder="Phonenumber" required />
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-label-secondary"
                                                                    id="closeButton" data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    @endif

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add New Zelle</h5>
                            </div>
                            <form action="{{ route('dashboard-zelle-add') }}" id="addBusForm" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label for="nameWithTitle" class="form-label">Name</label>
                                            <input type="text" id="nameWithTitle" name="name" class="form-control"
                                                placeholder="Name" required />
                                                <label for="nameWithTitle" class="form-label">Phonenumber</label>
                                            <input type="text" id="nameWithTitle" name="phonenumber" class="form-control"
                                                placeholder="Phonenumber" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" id="closeButton"
                                        data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
    @section('script')
        <script src="/assets/vendor/libs/tagify/tagify.js"></script>
        <script src="/assets/js/forms-tagify.js"></script>
        <script></script>

    @endsection
