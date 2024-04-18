@php
$notifications = auth()->user()->unreadNotifications;
 @endphp
<div class="nav-item dropdown d-none d-md-flex me-3">
    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
       aria-label="Show notifications">
        <i class="ti ti-bell"></i>
        <span class="badge bg-red notification-count text-white">{{$notifications->count()}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title fw-bolder">সর্বশেষ আপডেট</h3>
            </div>
            <div class="card-body" style="max-height: 350px;max-width: 350px;overflow-y: scroll">
                <div class="list-group list-group-flush list-group-hoverable notification-list">
                    <div class="loader-container text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    {{--@foreach($notifications as $notification)
                        <div class="list-group-item py-1">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="status-dot {{$notification->read_at ? '' : 'status-dot-animated bg-red'}} d-block"></span>
                                </div>
                                <div class="col text-truncate">
                                    {{ $notification->data['title'] }}
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        {!! $notification->data['message'] !!}
                                    </div>
                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="list-group-item-actions">
                                        <i class="ti ti-star"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach--}}
                </div>
            </div>
        </div>
    </div>
</div>
