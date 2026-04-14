@extends('layouts.app')

@section('title', 'Hoja - Dashboard')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="dashboard_graph">
      <div class="row x_title">
        <div class="col-md-6">
          <h3>{{ __('Welcome to Hoja') }}</h3>
        </div>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{ __('Dashboard') }}</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>{{ __('This is the main dashboard page.') }}</p>

            <!-- Stats Tiles -->
            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_390">
                  <div class="x_title">
                    <h2>{{ __('Quick Stats') }}</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget">
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span>{{ __('Total Users') }}</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span>{{ __('Active users') }}</span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span>{{ __('Total Projects') }}</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span>{{ __('Active projects') }}</span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span>{{ __('Total Analyses') }}</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span>{{ __('Completed analyses') }}</span>
                              </div>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="x_panel tile fixed_height_390">
                  <div class="x_title">
                    <h2>{{ __('Recent Activity') }}</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget">
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span>{{ __('System') }}</span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">{{ __('Welcome to Hoja') }}</a>
                              </h2>
                              <div class="byline">
                                <span>{{ date('Y-m-d H:i:s') }}</span>
                              </div>
                              <p class="excerpt">
                                {{ __('Hoja is a modern PHP application built with Leaf framework and Gentelella dashboard template.') }}
                              </p>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<!-- Page-specific scripts -->
@endsection
