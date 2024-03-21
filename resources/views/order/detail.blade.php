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

            <div class="card mb-3">
                <div class="card-body">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

                        <div class="d-flex flex-column justify-content-center gap-2 gap-sm-0">
                            <h5 class="mb-1 mt-3 d-flex flex-wrap gap-2 align-items-end">Order #{{ $order->id }}
                                @if ($order->status == 0)
                                    <span class="badge bg-label-secondary btn" data-bs-toggle="modal"
                                        data-bs-target="#accpetOrder">Pending</span>
                                    <div class="modal fade" data-bs-backdrop='static' id="accpetOrder" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content verifymodal">
                                                <div class="modal-header">
                                                    <div class="modal-title" id="modalCenterTitle">Are you
                                                        sure you want to accpet
                                                        this order?
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="body mb-2">If you will accpet this order you have to
                                                        complete this order
                                                    </div>
                                                </div>
                                                <form
                                                    action="{{ url('/dashboard/order/change/status/' . $order->id . '/' . 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="body">
                                                            <div class="row">
                                                                <div class="col mb-2">
                                                                    <input type="number" id="nameWithTitle" name="price"
                                                                        class="form-control" placeholder="Price" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="hr">

                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="first">
                                                                <a href="" class="btn" data-bs-dismiss="modal"
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
                                @endif
                                @if ($order->status == 1)
                                    <span class="badge bg-label-primary btn" data-bs-toggle="modal"
                                        data-bs-target="#startOrder">Accepted</span>
                                    <div class="modal fade" data-bs-backdrop='static' id="startOrder" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content verifymodal">
                                                <div class="modal-header">
                                                    <div class="modal-title" id="modalCenterTitle">Are you
                                                        sure you want to start
                                                        this order?
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="body">After starting you will not cancel the order</div>
                                                </div>
                                                <form
                                                    action="{{ url('/dashboard/order/change/status/' . $order->id . '/' . 2) }}"
                                                    method="POST">
                                                    @csrf


                                                    <hr class="hr">

                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="first">
                                                                <a href="" class="btn" data-bs-dismiss="modal"
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
                                @if ($order->status == 2)
                                    <span class="badge bg-label-warning btn" data-bs-toggle="modal"
                                        data-bs-target="#delivered">Started</span>
                                    <div class="modal fade" data-bs-backdrop='static' id="delivered" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content verifymodal">
                                                <div class="modal-header">
                                                    <div class="modal-title" id="modalCenterTitle">Are you
                                                        sure you want to delivered
                                                        this order?
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="body">After delivering this order you will not change this
                                                    </div>
                                                </div>
                                                <form
                                                    action="{{ url('/dashboard/order/change/status/' . $order->id . '/' . 3) }}"
                                                    method="POST">
                                                    @csrf


                                                    <hr class="hr">

                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="first">
                                                                <a href="" class="btn" data-bs-dismiss="modal"
                                                                    style="color: #a8aaae ">Cancel</a>
                                                            </div>
                                                            <div class="second">
                                                                <button type="submit"
                                                                    class="btn text-center">Delivered</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($order->status == 3)
                                    <span class="badge bg-label-info">Delivered</span>
                                @endif
                                @if ($order->status == 4)
                                    <span class="badge bg-label-success">Completed</span>
                                @endif
                                @if ($order->status == 5)
                                    <span class="badge bg-label-danger">Canceled</span>
                                @endif
                            </h5>
                            <p class="text-body">{{ $order->created_at }}</p>
                        </div>
                        <div class="d-flex align-content-center flex-wrap gap-2">
                            @if ($order->status == 0 || $order->status == 1 || $order->status == 2 || $order->status == 3)
                                <a href="{{ route('dashboard-order-conversation', $order->id) }}"
                                    class="btn btn-primary">Chat</a>
                            @endif


                            @if ($order->status == 0 || $order->status == 1)
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancel">Cancel
                                    Order</button>
                                <div class="modal fade" data-bs-backdrop='static' id="cancel" tabindex="-1"
                                    aria-hidden="true">
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
                                                action="{{ url('/dashboard/order/change/status/' . $order->id . '/' . 5) }}"
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
                                                            <a href="" class="btn" data-bs-dismiss="modal"
                                                                style="color: #a8aaae ">Close</a>
                                                        </div>
                                                        <div class="second">
                                                            <button type="submit" class="btn text-center">Cancel
                                                                Order</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Order details</h5>
                            <h5 class="card-title m-0">${{ $order->price }}</h5>
                        </div>

                        @foreach ($order->answers as $item)
                            <div class="row mb-3 px-4">
                                <div class="col-md-12">
                                    <div class="frame_text">
                                        <div class="mb-2">
                                            <h6 class="mb-1">{{ $item->question }}</h6>
                                            @if ($item->type == 'multiple_images' || $item->type == 'single_image')
                                                <div class="row">
                                                    @foreach ($item->answer as $answer)
                                                        <div class="col-3">
                                                            <a href="{{ $answer }}" target="_blank">
                                                                <img src="{{ $answer }}" class="rounded"
                                                                    alt="" style="width: 100% ; height: 150px;">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif ($item->type == 'single_video')
                                                <div class="row">
                                                    @foreach ($item->answer as $answer)
                                                        <div class="col-3">
                                                            <a href="{{ $answer }}" target="_blank">
                                                                <video src="{{ $answer }}" style="width: 100% "
                                                                    class="rounded"></video>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif ($item->type == 'document_type')
                                                <div class="row">
                                                    @foreach ($item->answer as $answer)
                                                        <div class="col-3">
                                                            <a href="{{ $answer }}" target="_blank">
                                                                <img src="https://media.istockphoto.com/id/1209500169/vector/document-papers-line-icon-pages-vector-illustration-isolated-on-white-office-notes-outline.jpg?s=612x612&w=0&k=20&c=Dt2k6dEbHlogHilWPTkQXAUxAL9sKZnoO2e055ihMO0="
                                                                    alt="" style="width: 100% ; height: 150px;">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                @foreach ($item->answer as $answer)
                                                    <span class="">{{ $answer }}</span>
                                                @endforeach
                                            @endif
                                        </div>



                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">Shipping activity</h5>
                        </div>
                        <div class="card-body">
                            <ul class="timeline pb-0 mb-0">
                                <li
                                    class="timeline-item timeline-item-transparent {{ $order->accept_timestamp == '' ? 'border-left-dashed' : 'border-primary' }}">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">Order was placed (Order ID: #{{ $order->id }})</h6>
                                            <span
                                                class="text-muted">{{ date('d F, Y - h:i A', $order->placed_timestamp) }}</span>
                                        </div>
                                        <p class="mt-2">Order has been placed successfully</p>
                                    </div>
                                </li>
                                @if ($order->status != 5)
                                    @if ($order->accept_timestamp)
                                        <li
                                            class="timeline-item timeline-item-transparent {{ $order->started_timestamp == '' ? 'border-left-dashed' : 'border-primary' }}">
                                            <span class="timeline-point timeline-point-primary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Accept</h6>
                                                    <span
                                                        class="text-muted">{{ date('d F, Y - h:i A', $order->accept_timestamp) }}</span>
                                                </div>
                                                <p class="mt-2">You successfully accpet the order</p>
                                            </div>
                                        </li>
                                    @else
                                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                                            <span class="timeline-point timeline-point-secondary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Accept</h6>
                                                    {{-- <span
                                               class="text-muted">{{ date('d F, Y - h:i A', $order->accept_timestamp) }}</span> --}}
                                                </div>
                                                {{-- <p class="mt-2">You successfully accpet the order</p> --}}
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->started_timestamp)
                                        <li
                                            class="timeline-item timeline-item-transparent {{ $order->delivered_timestamp == '' ? 'border-left-dashed' : 'border-primary' }}">
                                            <span class="timeline-point timeline-point-primary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Start</h6>

                                                    <span
                                                        class="text-muted">{{ date('d F, Y - h:i A', $order->started_timestamp) }}</span>
                                                </div>
                                                <p class="mt-2">You start the order</p>
                                            </div>
                                        </li>
                                    @else
                                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                                            <span class="timeline-point timeline-point-secondary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Start</h6>
                                                    {{-- <span
                                           class="text-muted">{{ date('d F, Y - h:i A', $order->accept_timestamp) }}</span> --}}
                                                </div>
                                                {{-- <p class="mt-2">You successfully accpet the order</p> --}}
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->delivered_timestamp)
                                        <li
                                            class="timeline-item timeline-item-transparent {{ $order->complete_timestamp == '' ? 'border-left-dashed' : 'border-primary' }}">
                                            <span class="timeline-point timeline-point-primary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Delivered</h6>

                                                    <span
                                                        class="text-muted">{{ date('d F, Y - h:i A', $order->delivered_timestamp) }}</span>
                                                </div>
                                                <p class="mt-2">You successfully delivered the order</p>
                                            </div>
                                        </li>
                                    @else
                                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                                            <span class="timeline-point timeline-point-secondary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Delivered</h6>
                                                    {{-- <span
                                       class="text-muted">{{ date('d F, Y - h:i A', $order->accept_timestamp) }}</span> --}}
                                                </div>
                                                {{-- <p class="mt-2">You successfully accpet the order</p> --}}
                                            </div>
                                        </li>
                                    @endif

                                    @if ($order->complete_timestamp)
                                        <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                            <span class="timeline-point timeline-point-primary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Completed</h6>

                                                    <span
                                                        class="text-muted">{{ date('d F, Y - h:i A', $order->complete_timestamp) }}</span>
                                                </div>
                                                <p class="mt-2">You successfully complete the order</p>
                                            </div>
                                        </li>
                                    @else
                                        <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                            <span class="timeline-point timeline-point-secondary"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Order Completed</h6>
                                                    {{-- <span
                                   class="text-muted">{{ date('d F, Y - h:i A', $order->accept_timestamp) }}</span> --}}
                                                </div>
                                                {{-- <p class="mt-2">You successfully accpet the order</p> --}}
                                            </div>
                                        </li>
                                    @endif
                                @else
                                    <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                        <span class="timeline-point timeline-point-danger"></span>
                                        <div class="timeline-event pb-0">
                                            <div class="timeline-header">
                                                <h6 class="mb-0">Order Canceled</h6>

                                                @if ($order->canceled_timestamp)
                                                    <span
                                                        class="text-muted">{{ date('d F, Y - h:i A', $order->canceled_timestamp) }}</span>
                                                @endif
                                            </div>
                                            <p class="mt-2 mb-0">Order has been canceled</p>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">Customer details</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                @if ($order->user->image)
                                    <div class="avatar me-2">
                                        <img src="{{ asset($order->user->image) }}" alt="Avatar"
                                            class="rounded-circle">


                                    </div>
                                @else
                                    <div class="avatar avatar-sm me-3"><span
                                            class="avatar-initial rounded-circle bg-label-danger">
                                            {{ strtoupper(substr($order->user->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-body text-nowrap">
                                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                                    </a>
                                    {{-- <small class="text-muted">Customer ID: #{{ $order->user->uuid }}</small> --}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-4">
                                <span
                                    class="avatar rounded-circle bg-label-success me-2 d-flex align-items-center justify-content-center"><i
                                        class="ti ti-shopping-cart ti-sm"></i></span>
                                <h6 class="text-body text-nowrap mb-0">{{ $order->user->order_count }} Orders</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6>Contact info</h6>

                            </div>
                            <p class=" mb-1">Email: {{ $order->user->email }}</p>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">Payment details</h6>
                        </div>
                        <div class="card-body">
                            @if ($order->payment)
                                <p class=" mb-1">Method: {{ $order->payment->method }}</p>
                                <p class=" mb-1">Transcript: </p>

                                <a href="{{ $order->payment->transcript }}" target="_blank">
                                    <img src="{{ $order->payment->transcript }}" width="100" height="100"
                                        alt="">

                                </a>
                            @else
                                <p class=" mb-1 text-center text-danger">Payment is pending</p>
                            @endif
                        </div>
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
