@extends('admin.layouts.app')

@section('panel')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive--md table-responsive">
            <table class="table--light style--two table">
              <thead>
                <tr>
                  <th>@lang('Code')</th>
                  <th>@lang('Value')</th>
                  <th>@lang('User Name')</th>
                  <th>@lang('Expire')</th>
                  <th>@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                @forelse($codes as $key => $code)
                  <tr>
                    <td>{{ __($code->code) }}</td>
                    <td>{{ __($code->value) }}</td>
                    <td>{{ $code->username ?? 'null' }}</td>
                    <td>{{ boolval($code->expire) ? 'Yes' : 'No' }}</td>

                    <td>
                      <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                        data-action="{{ route('admin.code.delete', $code->id) }}">
                        @lang('Delete')
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                  </tr>
                @endforelse

              </tbody>
            </table><!-- table end -->
          </div>
        </div>
        @if ($codes->hasPages())
          <div class="card-footer py-4">
            @php echo paginateLinks($codes) @endphp
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="modal fade" id="add-code" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('Add New Code')</h5>
          <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
            <i class="las la-times"></i>
          </button>
        </div>
        <form method="post" action="{{ route('admin.code.save') }}">
          @csrf
          <div class="modal-body">
            <input class="form-control code_id" name="id" type="hidden">
            <div class="row">
              <div class="form-group col-12">
                <label> @lang('Count') </label>
                <div class="input-group">
                  <input class="form-control count" name="count" type="number" step="any" required>
                </div>
              </div>
              <div class="form-group col-12">
                <label> @lang('Value') </label>
                <div class="input-group">
                  <input class="form-control count" name="value" type="number" step="any" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
  <button class="btn btn-sm btn-outline--primary add-code" type="button">
    <i class="la la-plus"></i>@lang('Add New')
  </button>
@endpush

@push('script')
  <script>
    "use strict";
    (function($) {
      $('.add-code').on('click', function() {
        var modal = $('#add-code');
        modal.modal('show');
      });
    })(jQuery);
  </script>
@endpush
