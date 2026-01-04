@php
    $userRole = auth()->user()?->roles->first()?->name ?? 'user';
@endphp

@if($userRole === 'owner')
    @include('dashboard.owner')
@elseif($userRole === 'conductor')
    @include('dashboard.conductor')
@elseif($userRole === 'agent')
    @include('dashboard.agent')
@else
    @include('dashboard.user')
@endif