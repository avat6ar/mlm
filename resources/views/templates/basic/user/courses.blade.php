@extends($activeTemplate . 'layouts.master')
@section('content')
  <div class="dashboard-inner">
    <div class="mb-4">
      <h3 class="mb-2">Courses</h3>
    </div>
    <div class="mb-4 w-100 text-center">
      <img src="{{ asset('assets/images/user/Artboard 4.webp') }}" alt="image" width="150" height="150"
        class="mx-auto">
    </div>
    <div class="row">
      @foreach ($iframs as $ifram)
        <div class="col-6">
          <iframe src={{ $ifram }} style="border:0;width:100%;height:405px" allow="encrypted-media"
            allowfullscreen></iframe>
        </div>
      @endforeach
    </div>
  </div>
@endsection
