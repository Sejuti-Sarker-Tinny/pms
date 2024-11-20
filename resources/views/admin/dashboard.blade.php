@extends('layouts.admin.dashboard')
@section('contents')

@if(Auth::user()->ban_status_on=='0')

@elseif(Auth::user()->ban_status_on=='1')

<h3 class="text-center">Your account banned!</h3>
@endif                               



@endsection






