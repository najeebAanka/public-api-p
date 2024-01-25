@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Add Testimonial'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Testimonial')}}</li>
            </ol>
        </nav>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h1 class="page-header-title">{{\App\CPU\translate('Testimonial')}} </h1>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.testimonials.store')}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              enctype="multipart/form-data">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs mb-4">
                                @foreach(json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}"
                                           href="#"
                                           id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            @foreach(json_decode($language) as $lang)
                                <div class=" {{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="form-group">
                                        <label for="title">{{ \App\CPU\translate('Title')}} ({{strtoupper($lang)}}
                                            )</label>
                                        <input type="text" name="title[]" class="form-control" id="title"
                                               value="{{old('title')}}"
                                               placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('LUX')}}" {{$lang == $default_lang? 'required':''}}>
                                    </div>
                                    <div class="form-group">
                                        <label for="position">{{ \App\CPU\translate('Position')}} ({{strtoupper($lang)}}
                                            )</label>
                                        <input type="text" name="position[]" class="form-control" id="position"
                                               value="{{old('position')}}"
                                               placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Manager')}}" {{$lang == $default_lang? 'required':''}}>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}
                                            ({{strtoupper($lang)}})</label>
                                        <textarea name="description[]" class="form-control" required></textarea>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Image')}} </label>
                                <small style="color: red"> ( {{\App\CPU\translate('Ratio')}} 3:1 )</small>
                                <div class="custom-file" style="text-align: left">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                           for="customFileEg1">{{\App\CPU\translate('Choose file')}}</label>
                                </div>
                                <hr>
                                <center>
                                    <img style="width: 20%; border: 1px solid; border-radius: 10px;" id="viewer"
                                         onerror="this.src='{{asset('/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('/assets/admin/img/900x400/img1.jpg')}}" alt="image"/>
                                </center>
                            </div>
                            <hr>
                            <button type="submit"
                                    class="btn btn-primary">{{\App\CPU\translate('Save')}} </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <hr>
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>{{ \App\CPU\translate('Testimonial_Table')}}</div>
                                <div class="mx-1"><h5 style="color: red;">({{ $notifications->total() }})</h5></div>
                            </div>
                            <div style="width: 40vw;">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search by Title')}}"
                                               aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                                class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>#{{\App\CPU\translate('sl')}} </th>
                                <th style="width: 50%">{{\App\CPU\translate('Title')}} </th>
                                <th style="width: 50%">{{\App\CPU\translate('Position')}} </th>
                                <th>{{\App\CPU\translate('Description')}} </th>
                                <th>{{\App\CPU\translate('Image')}} </th>
                                <th>{{\App\CPU\translate('Status')}} </th>
                                <th style="width: 10%">{{\App\CPU\translate('Action')}} </th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$notifications->firstItem()+ $key}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$notification['title']}}
                                    </span>
                                    </td><td>
                                        {{$notification['position']}}
                                    </td>
                                    <td style="white-space: pre-wrap;">
                                        {{substr($notification['description'],0,50)}} ...
                                    </td>
                                    <td>
                                        <img style="height: 75px"
                                             onerror="this.src='{{asset('/assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset('storage/testimonials')}}/{{$notification['image']}}">
                                        {{--<span class="d-block font-size-sm">{{$banner['image']}}</span>--}}
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="status"
                                                   id="{{$notification['id']}}" {{$notification->status == 1?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.testimonials.edit',[$notification['id']])}}">{{\App\CPU\translate('Edit')}} </a>
                                                <a class="dropdown-item delete" href="javascript:"
                                                   id="{{$notification['id']}}')">{{\App\CPU\translate('Delete')}} </a>

                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <table>
                            <tfoot>
                            {!! $notifications->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.testimonials.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    location.reload();
                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure delete this')}} ?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes, delete it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.testimonials.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Testimonial deleted successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });

    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
