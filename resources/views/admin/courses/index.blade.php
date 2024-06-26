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
                  <th>@lang('Course Id')</th>
                  <th>@lang('Link')</th>
                  <th>@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                @forelse($courses as $key => $course)
                  <tr>
                    <td>{{ $course['course_id'] }}</td>
                    <td><a href="{{ $course['link'] }}" target="_blank">View</a></td>

                    <td>
                      <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                        data-action="{{ route('admin.course.delete', $course['id']) }}">
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
        @if ($coursesAll->hasPages())
          <div class="card-footer py-4">
            @php echo paginateLinks($coursesAll) @endphp
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="modal fade" id="add-course" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('Add New course')</h5>
          <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
            <i class="las la-times"></i>
          </button>
        </div>
        <form method="post" action="{{ route('admin.course.store') }}">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-12">
                <label> @lang('Course id') </label>
                <div class="input-group">
                  <input class="form-control count" name="course_id" type="text" step="any" required>
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
  <button class="btn btn-sm btn-outline--primary add-course" type="button">
    <i class="la la-plus"></i>@lang('Add New')
  </button>
@endpush

@push('script')
  <script>
    "use strict";
    (function($) {
      $('.add-course').on('click', function() {
        var modal = $('#add-course');
        modal.modal('show');
      });
    })(jQuery);
  </script>
@endpush
