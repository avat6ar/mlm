@extends('admin.layouts.app')

@section('panel')
  <div class="row">
    <div class="col-12">
      <div class="row gy-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">@lang('Information of')
            </h5>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.users.add') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Referral Username')</label>
                    <input class="form-control" name="referral" type="text" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">@lang('Position')</label>
                    <select class="form-control" id="position" name="position" required>
                      <option value="">@lang('Select position')*</option>
                      @foreach (mlmPositions() as $k => $v)
                        <option value="{{ $k }}">{{ __($v) }}</option>
                      @endforeach
                    </select>
                    <span id="position-test"><span class="text--danger"></span></span>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Username Position')</label>
                    <input class="form-control" name="user_position" type="text" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('First Name')</label>
                    <input class="form-control" name="firstname" type="text" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">@lang('Last Name')</label>
                    <input class="form-control" name="lastname" type="text" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Email') </label>
                    <input class="form-control" name="email" type="email" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Mobile Number') </label>
                    <div class="input-group">
                      <span class="input-group-text mobile-code"></span>
                      <input class="form-control checkUser" id="mobile" name="mobile" type="number"
                        value="{{ old('mobile') }}" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>@lang('Address')</label>
                    <input class="form-control" name="address" type="text">
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="form-group">
                    <label>@lang('City')</label>
                    <input class="form-control" name="city" type="text">
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="form-group">
                    <label>@lang('State')</label>
                    <input class="form-control" name="state" type="text">
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="form-group">
                    <label>@lang('Zip/Postal')</label>
                    <input class="form-control" name="zip" type="text">
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="form-group">
                    <label>@lang('Country')</label>
                    <select class="form-control" name="country">
                      @foreach ($countries as $key => $country)
                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">
                          {{ __($country->country) }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-xl-3 col-md-6 col-12">
                  <label>@lang('Email Verification')</label>
                  <input name="ev" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox">
                </div>

                <div class="form-group col-xl-3 col-md-6 col-12">
                  <label>@lang('User Active')</label>
                  <input name="ua" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                    data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('DeActive')" type="checkbox">
                </div>

                <div class="form-group col-xl-3 col-md-6 col-12">
                  <label>@lang('Mobile Verification')</label>
                  <input name="sv" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                    data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                    type="checkbox">

                </div>
                <div class="form-group col-xl-3 col-md- col-12">
                  <label>@lang('2FA Verification') </label>
                  <input name="ts" data-width="100%" data-height="50" data-onstyle="-success"
                    data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')"
                    data-off="@lang('Disable')" type="checkbox">
                </div>
                <div class="form-group col-xl-3 col-md- col-12">
                  <label>@lang('KYC') </label>
                  <input name="kv" data-width="100%" data-height="50" data-onstyle="-success"
                    data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')"
                    data-off="@lang('Unverified')" type="checkbox">
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

  @push('script')
    <script>
      (function($) {
        "use strict"
        $('.bal-btn').click(function() {
          var act = $(this).data('act');
          $('#addSubModal').find('input[name=act]').val(act);
          if (act == 'add') {
            $('.type').text('Add');
          } else {
            $('.type').text('Subtract');
          }
        });
        let mobileElement = $('.mobile-code');
        $('select[name=country]').change(function() {
          mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

        $('select[name=country]').val('{{ @$user->country_code }}');
        let dialCode = $('select[name=country] :selected').data('mobile_code') ?? "93";
        let mobileNumber = '';
        mobileNumber = mobileNumber.replace(dialCode, '');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);

      })(jQuery);
    </script>
  @endpush
