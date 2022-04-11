@extends("layouts.admin")

@section('content')

    <section class="section">
        <div class="row justify-content-center boxes-list jsBoxesList">
        </div>
    </section>

@endsection

@section('models')
    <div class="col-12 col-sm-6 col-md-4 mb-3 boxes-item jsBoxe">
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <a class="text-dark text-left boxe-url" href="">
                    <h5 class="boxe-title"></h5>
                </a>
                <div class="d-flex boxe-data-list">
                    <div class="mr-2 boxe-data-item jsBoxeDataItem d-none">
                        <a class="text-dark boxe-data-url" href="">
                            <span class="font-weight-medium boxe-data-title"></span>
                            <span class="boxe-data-value"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
