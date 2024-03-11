@foreach ($users as $user)
    <tr class="odd">

        <td class="sorting_1">
            <div class="d-flex justify-content-start align-items-center user-name">
                @if ($user->image)
                    <div class="avatar-wrapper">
                        <div class="avatar avatar-sm me-3"><img
                                src="{{ asset($user->image != '' ? $user->image : 'user.png') }}" alt="Avatar"
                                class="rounded-circle">
                        </div>
                    </div>
                @else
                    <div class="avatar-wrapper">
                        <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded-circle bg-label-danger">
                                {{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                    </div>
                @endif



                <div class="d-flex flex-column"><a href="" class="text-body text-truncate"><span
                            class="fw-semibold user-name-text">{{ $user->name }}</span></a><small
                        class="text-muted">&#64;{{ $user->email }}</small>
                </div>
            </div>
        </td>


 
        <td class="account-status text-start">

            <button class="badge bg-label-secondary btn" data-bs-toggle="modal"
                data-bs-target="#verifyModal{{ $user->uuid }}" text-capitalized="">Pending

            </button>


            <div class="modal fade" data-bs-backdrop='static' id="verifyModal{{ $user->uuid }}" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content verifymodal">
                        <div class="modal-header">
                            <div class="modal-title" id="modalCenterTitle">Are you
                                sure you want to approve
                                this account?
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="body">If you will approve this account after
                                that
                                this user will access ta marketing app.</div>
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
                                        href="{{ route('dashboard-get-verify', $user->uuid) }}">APPROVED</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </td>

    </tr>
@endforeach
