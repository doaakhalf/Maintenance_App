<li class="nav-item dropdown">
<a class="nav-link" data-toggle="dropdown" href="#">
<i class="far fa-bell"></i>

<span style="@if($notificationCount==0) display:none; @else display:block; @endif" class="badge badge-danger navbar-badge" id="notifications-counter-badge">{{$notificationCount}}</span>

</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notifications">
   <div class="dropdown-item dropdown-header"><span  id="notifications-counter">{{$notificationCount}}</span><span> Notifications</span></div> 
    <div class="dropdown-divider"></div>
  
    @foreach(Auth::user()->unreadNotifications()->get() as $notification)
        
        <a href="{{$notification->data['url']}}" style="overflow: auto;" class="dropdown-item">
       
        <i class="fas fa-envelope mr-2"></i>  {{isset($notification->data['name'])? $notification->data['name'] :$notification->data['title']}}
        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans()}}</span>
        </a>
    @endforeach
    <a href="#" id="last-noti" class="dropdown-item dropdown-footer">See All Notifications</a>
</div>

</li>