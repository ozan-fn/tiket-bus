@php
    $userRole = auth()->user()?->roles->first()?->name ?? 'agent';
@endphp

@if($userRole === 'owner')
    @include('dashboard.owner')
@elseif($userRole === 'conductor')
    @include('dashboard.conductor')
@else
    @include('dashboard.agent')
@endif
