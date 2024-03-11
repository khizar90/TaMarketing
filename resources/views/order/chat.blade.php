@extends('layouts1.base')
@section('title', 'Chat')
@section('main', 'Order Chat')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/css/pages/app-chat.css" />


@endsection
@section('content')

    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-chat card overflow-hidden">
                <div class="row g-0">
                    <!-- Chat History -->
                    <div class="col app-chat-history bg-body">
                        <div class="chat-history-wrapper">
                            <div class="chat-history-header border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex overflow-hidden align-items-center">
                                        <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2"
                                            data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                                        <div class="flex-shrink-0 avatar">
                                            <img src="{{ $findUser->image != '' ? $findUser->image : asset('Placeholder_image.png') }}"
                                                alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay
                                                data-target="#app-chat-sidebar-right" />
                                        </div>
                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                            <h6 class="m-0">{{ $findUser->name }}</h6>
                                            {{-- <small class="user-status text-muted">{{ $findUser->email }}</small> --}}
                                        </div>
                                    </div>
                                    <div class="d-flex overflow-hidden align-items-center">

                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                            <h6 class="m-0">Order ID #{{ $order->id }}</h6>
                                            {{-- <small class="user-status text-muted">{{ $findUser->email }}</small> --}}
                                        </div>
                                    </div>
                                    {{-- {{-- @if ($ticket->status == 1) --}}
                                    <div>
                                        @if ($order->status == 0)
                                            <span class="badge bg-label-secondary" data-bs-toggle="modal"
                                                data-bs-target="#accpetOrder">Pending</span>
                                            <div class="modal fade" data-bs-backdrop='static' id="accpetOrder"
                                                tabindex="-1" aria-hidden="true">
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
                                        @endif
                                        @if ($order->status == 1)
                                            <span class="badge bg-label-primary" data-bs-toggle="modal"
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
                                                            <div class="body">After starting you will not cancel the order
                                                            </div>
                                                        </div>
                                                        <form
                                                            action="{{ url('/dashboard/order/change/status/' . $order->id . '/' . 2) }}"
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
                                        @if ($order->status == 2)
                                            <span class="badge bg-label-warning" data-bs-toggle="modal"
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
                                                            <div class="body">After delivering this order you will not
                                                                change
                                                                this
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
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
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
                                            <span class="badge bg-label-info">Delivere</span>
                                        @endif
                                        @if ($order->status == 4)
                                            <span class="badge bg-label-success">Completed</span>
                                        @endif
                                        @if ($order->status == 5)
                                            <span class="badge bg-label-danger">Canceled</span>
                                        @endif
                                        @if ($order->status == 0 || $order->status == 1)
                                            <span class="badge bg-label-danger" data-bs-toggle="modal"
                                                data-bs-target="#cancel">Cancel Order</span>

                                            <div class="modal fade" data-bs-backdrop='static' id="cancel"
                                                tabindex="-1" aria-hidden="true">
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
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Close</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <button type="submit"
                                                                            class="btn text-center">Cancel
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
                            <div class="chat-history-body bg-body" id="chat-history-body">
                                <ul class="list-unstyled chat-history" id="list-unstyled">
                                    @foreach ($conversation as $message)
                                        @if ($message->send_by == 'admin')
                                            <li class="chat-message chat-message-right">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        @if ($message->attachment)
                                                            <img src="{{ $message->attachment }}" alt=""
                                                                class="rounded"
                                                                style="max-width: 100% ; max-height: 40vh">
                                                        @endif
                                                        @if ($message->message)
                                                            <div class="chat-message-text">
                                                                <p class="mb-0">{{ $message->message }}</p>
                                                            </div>
                                                        @endif
                                                        <div class="text-end text-muted mt-1">
                                                            {{--                                                            <i class="ti ti-checks ti-xs me-1 text-success"></i> --}}
                                                            <small>{{ date('d/m/Y, h:i:s', $message->time) }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="user-avatar flex-shrink-0 ms-3">
                                                        <div class="avatar avatar-sm">
                                                            <img src="/assets/img/avatars/1.png" alt="Avatar"
                                                                class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="chat-message">
                                                <div class="d-flex overflow-hidden">
                                                    <div class="user-avatar flex-shrink-0 me-3">
                                                        <div class="avatar avatar-sm">
                                                            <img src="{{ $findUser->image != '' ? $findUser->image : asset('Placeholder_image.png') }}"
                                                                alt="Avatar" class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="chat-message-wrapper flex-grow-1">
                                                        @if ($message->attachment)
                                                            <img src="{{ $message->attachment }}" alt=""
                                                                class="rounded"
                                                                style="max-width: 100% ; max-height: 40vh">
                                                        @endif
                                                        @if ($message->message)
                                                            <div class="chat-message-text">
                                                                <p class="mb-0">{{ $message->message }}</p>
                                                            </div>
                                                        @endif

                                                        <div class="text-muted mt-1">
                                                            <small>{{ date('d/m/Y, h:i:s', $message->time) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @if ($order->status != 5)
                                <div class="chat-history-footer shadow-sm">
                                    <form class=" d-flex justify-content-between align-items-center" id="messageForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $findUser->uuid }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <input class="form-control message-input border-0 me-3 shadow-none"
                                            placeholder="Type your message here" name="message" />
                                        <div class="message-actions d-flex align-items-center">

                                            <label for="attach-doc" class="form-label mb-0">
                                                <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                                                <input type="file" name="attachment" id="attach-doc" hidden="">
                                            </label>

                                            <button type="submit" class="btn btn-primary d-flex send-msg-btn"
                                                id="sendMessage">
                                                <i class="ti ti-send me-md-1 me-0" id="sendicon"></i>
                                                <span class="align-middle" id="sending">Send</span>


                                                <span class="align-middle spinner-border text-dark" style="display: none"
                                                    id="loader" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>


                    <div class="app-overlay"></div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    @endsection

    @section('script')


        <!-- Page JS -->
        <script src="/assets/js/app-chat.js"></script>
        <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
        <script>
            function scrollToBottom() {
                var chatHistory = document.getElementById('chat-history-body');
                chatHistory.scrollTop = chatHistory.scrollHeight;

            }
            $(document).ready(function() {
                $(document).on('submit', '#messageForm', function(e) {
                    e.preventDefault();
                    var loader = $('#loader');
                    var sending = $('#sending');
                    var sendicon = $('#sendicon');

                    loader.show()
                    sendicon.hide();
                    sending.hide();

                    var formData = new FormData(this);
                    $('.message-input').val('');

                    $.ajax({
                        type: "POST",
                        url: '{{ route('dashboard-order-send-message') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            loader.hide()
                            sendicon.show();
                            sending.show();
                            console.log(response);

                            var newMessage = `
                                <li class="chat-message chat-message-right">
                                    <div class="d-flex overflow-hidden">
                                        <div class="chat-message-wrapper flex-grow-1">
                                            ${response.attachment ? `<img src="${response.attachment}" alt="" class="rounded" style="max-width: 100%; max-height: 40vh">` : ''}
                                            ${response.message ? `<div class="chat-message-text"><p class="mb-0">${response.message}</p></div>` : ''}
                                            <div class="text-end text-muted mt-1">
                                                <small>Just now</small>
                                            </div>
                                        </div> 
                                        <div class="user-avatar flex-shrink-0 ms-3">
                                            <div class="avatar avatar-sm">
                                                <img src="/assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                        </div>
                                    </div>
                                 </li>
                                `;

                            $('#list-unstyled').append(newMessage);
                            scrollToBottom();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });
            });
        </script>
    @endsection
