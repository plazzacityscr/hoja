@extends('layouts.app')

@section('title', '500 - Internal Server Error')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ __('500 - Internal Server Error') }}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="text-center">
          <h1 style="font-size: 120px; color: var(--gt-danger);">500</h1>
          <h3>{{ __('Something went wrong on our end.') }}</h3>
          <p>{{ __('Please try again later or contact support if the problem persists.') }}</p>
          <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> {{ __('Go to Homepage') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
