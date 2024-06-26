@extends('admin.layouts.app')

@section('panel')
  <div class="row">
    <div class="col-12">
      <div class="card mt-30">
        <div class="card-header">
          <h5 class="card-title mb-0">@lang('Commission Details')

          </h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.commission.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Max Monthly Commission')</label>
                  <input class="form-control" name="max_month" type="text" value="{{ $commission->max_month }}"
                    required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">@lang('Max Day Commission')</label>
                  <input class="form-control" name="max_day" type="text" value="{{ $commission->max_day }}" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Commission Indirect')</label>
                  <input class="form-control" name="indirect" type="text" value="{{ $commission->indirect }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Commission Direct')</label>
                  <input class="form-control" name="direct" type="text" value="{{ $commission->direct }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Commission Direct')</label>
                  <input class="form-control" name="direct" type="text" value="{{ $commission->direct }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Commission Direct')</label>
                  <input class="form-control" name="direct" type="text" value="{{ $commission->direct }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Right')</label>
                  <input class="form-control" name="right" type="text" value="{{ $commission->right }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>@lang('Left')</label>
                  <input class="form-control" name="left" type="text" value="{{ $commission->left }}">
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12">
                <div class="form-group">
                  <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
