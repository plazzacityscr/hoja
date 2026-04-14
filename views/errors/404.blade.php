@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ __('404 - Page Not Found') }}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="text-center">
          <h1 style="font-size: 120px; color: var(--gt-gray-300);">404</h1>
          <h3>{{ __('The page you are looking for does not exist.') }}</h3>
          <p>{{ __('Please check the URL or go back to the homepage.') }}</p>
          <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> {{ __('Go to Homepage') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
