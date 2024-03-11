@extends('layouts1.base')
@section('title', 'Form')
@section('main', 'Form Management')
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
                        <h5 class="card-title mb-3">Questions List</h5>
                        <div class="">
                            <button class="btn btn-secondary add-new btn-primary" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                data-bs-target="#addNewBus"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Add New Question</span></span></button>
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

                                    <th>Question</th>
                                    <th>Required</th>
                                    <th>type</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>





                                @foreach ($questions as $item)
                                    <tr class="odd">
                                        <td class="">
                                            {{ $item->question }}
                                        </td>
                                        <td>
                                            {{ $item->is_required == 0 ? 'Optional' : 'Required' }}
                                        </td>
                                        @if (
                                            $item->type == 'document_type' ||
                                                $item->type == 'single_image' ||
                                                $item->type == 'multiple_images' ||
                                                $item->type == 'single_video')
                                            <td class="text-danger">
                                                {{ $item->type }}
                                            </td>
                                        @else
                                            <td class="">
                                                {{ $item->type }}
                                            </td>
                                        @endif

                                        <td class="detailbtn">
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#edit{{ $item->id }}" class="dropdown-item">Edit
                                                </a>
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $item->id }}"
                                                    class="dropdown-item">Delete
                                                </a>
                                                @if ($item->type == 'dropdown')
                                                    <a href="#" class="dropdown-item dropdownmodal"
                                                        data-question_id="{{ $item->id }}"
                                                        data-options="{{ $item->options }}">Detail
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
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
                                                                    <a class="btn text-center"
                                                                        href="{{ route('dashboard-question-delete', $item->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static' id="edit{{ $item->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Edit
                                                                Question
                                                            </h5>
                                                        </div>
                                                        <form action="{{ route('dashboard-question-edit', $item->id) }}"
                                                            enctype="multipart/form-data" id="addBusForm" method="POST">
                                                            @csrf

                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Question</label>
                                                                        <input type="text" id="nameWithTitle"
                                                                            name="question" value="{{ $item->question }}"
                                                                            class="form-control"
                                                                            placeholder="Question Title" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col mb-2">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Required</label>
                                                                        <div class="col-sm-12">
                                                                            <select id="defaultSelect" class="form-select"
                                                                                name="is_required" required>
                                                                                <option value="1"
                                                                                    {{ $item->is_required == 1 ? 'selected' : '' }}>
                                                                                    Required</option>
                                                                                <option value="0"
                                                                                    {{ $item->is_required == 0 ? 'selected' : '' }}>
                                                                                    Not Required
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col mb-2">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Type</label>
                                                                        <div class="col-sm-12">
                                                                            <select id="defaultSelect" class="form-select"
                                                                                name="type" required>
                                                                                <option value="text_input"
                                                                                    {{ $item->type == 'text_input' ? 'selected' : '' }}>
                                                                                    text_input
                                                                                </option>
                                                                                <option value="number_input"
                                                                                    {{ $item->type == 'number_input' ? 'selected' : '' }}>
                                                                                    number_input
                                                                                </option>
                                                                                <option value="text_notes"
                                                                                    {{ $item->type == 'text_notes' ? 'selected' : '' }}>
                                                                                    text_notes
                                                                                </option>
                                                                                <option value="dropdown"
                                                                                    {{ $item->type == 'dropdown' ? 'selected' : '' }}>
                                                                                    dropdown
                                                                                </option>
                                                                                <option value="date_picker"
                                                                                    {{ $item->type == 'date_picker' ? 'selected' : '' }}>
                                                                                    date_picker
                                                                                </option>
                                                                                @if ($single_image == null)
                                                                                    <option value="single_image"
                                                                                        {{ $item->type == 'single_image' ? 'selected' : '' }}>
                                                                                        single_image
                                                                                    </option>
                                                                                @endif
                                                                                @if ($multiple_images == null)
                                                                                    <option value="multiple_images"
                                                                                        {{ $item->type == 'multiple_images' ? 'selected' : '' }}>
                                                                                        multiple_images
                                                                                    </option>
                                                                                @endif
                                                                                @if ($single_video == null)
                                                                                    <option value="single_video"
                                                                                        {{ $item->type == 'single_video' ? 'selected' : '' }}>
                                                                                        single_video
                                                                                    </option>
                                                                                @endif
                                                                                @if ($document_type == null)
                                                                                    <option value="document_type"
                                                                                        {{ $item->type == 'document_type' ? 'selected' : '' }}>
                                                                                        document_type
                                                                                    </option>
                                                                                @endif






                                                                            </select>
                                                                        </div>
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



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add New Question</h5>
                            </div>
                            <form action="{{ route('dashboard-question-add') }}" id="addBusForm" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label for="nameWithTitle" class="form-label">Question</label>
                                            <input type="text" id="nameWithTitle" name="question"
                                                class="form-control" placeholder="Question Title" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label for="nameWithTitle" class="form-label">Required</label>
                                            <div class="col-sm-12">
                                                <select id="defaultSelect" class="form-select" name="is_required"
                                                    required>
                                                    <option value="1">Required</option>
                                                    <option value="0">Optional</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label for="nameWithTitle" class="form-label">Type</label>
                                            <div class="col-sm-12">
                                                <select id="defaultSelect" class="form-select" name="type" required>
                                                    <option value="text_input">text_input</option>
                                                    <option value="number_input">number_input</option>
                                                    <option value="text_notes">text_notes</option>
                                                    <option value="dropdown">dropdown</option>

                                                    <option value="date_picker">date_picker</option>
                                                    {{-- <option value="time_picker">time_picker</option> --}}
                                                    {{-- <option value="location_picker">location_picker</option> --}}
                                                    @if ($single_image == null)
                                                        <option value="single_image">single_image</option>
                                                    @endif
                                                    @if ($multiple_images == null)
                                                        <option value="multiple_images">multiple_images</option>
                                                    @endif
                                                    @if ($single_video == null)
                                                        <option value="single_video">single_video</option>
                                                    @endif
                                                    @if ($document_type == null)
                                                        <option value="document_type">document_type</option>
                                                    @endif
                                                </select>
                                            </div>
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
                <div class="modal fade" data-bs-backdrop='static' id="dropdown" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add Options</h5>
                            </div>
                            <form action="" id="optionsForm" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label for="TagifyBasic" class="form-label">Optons</label>
                                            <input id="TagifyBasic" required class="form-control" name="options"
                                                value="" />
                                        </div>
                                    </div>

                                </div>
                                <!-- Basic -->

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
        <script>
            $(document).ready(function() {
                $('.dropdownmodal').on('click', function(e) {
                    e.preventDefault();
                    var question_id = $(this).data('question_id');
                    var options = $(this).data('options');
                    console.log(options);
                    console.log(question_id);
                    $('#TagifyBasic').val(options);
                    $('#optionsForm').attr('action', "question/options" + "/" + question_id);
                    $('#dropdown').modal('show');
                });

            });
        </script>

    @endsection
