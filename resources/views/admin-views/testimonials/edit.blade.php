@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Update Testimonial'))

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
                        <h1 class="page-header-title">{{\App\CPU\translate('Testimonial Update')}}</h1>
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs mb-4">
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#"
                                       id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.testimonials.update',[$notification['id']])}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              enctype="multipart/form-data">
                            @csrf
                            @foreach(json_decode($language) as $lang)
                                <?php
                                if (count($notification['translations'])) {
                                    $translate = [];
                                    foreach ($notification['translations'] as $t) {

                                        if ($t->locale == $lang && $t->key == "title") {
                                            $translate[$lang]['title'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "position") {
                                            $translate[$lang]['position'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "description") {
                                            $translate[$lang]['description'] = $t->value;
                                        }

                                    }
                                }
                                ?>
                                <div class="{{$lang != 'en'? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang}}_title">{{\App\CPU\translate('title')}}
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="title[]"
                                               id="{{$lang}}_title"
                                               value="{{$translate[$lang]['title']??$notification['title']}}"
                                               class="form-control" placeholder="{{\App\CPU\translate('New Product')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="{{$lang}}_position">{{\App\CPU\translate('position')}}
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="position[]"
                                               id="{{$lang}}_position"
                                               value="{{$translate[$lang]['position']??$notification['position']}}"
                                               class="form-control" placeholder="{{\App\CPU\translate('New Product')}}"
                                               required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                    <div class="form-group pt-4">
                                        <label class="input-label">{{\App\CPU\translate('description')}}
                                            ({{strtoupper($lang)}})</label>
                                        <textarea name="description[]" class="form-control"
                                                  required>{!! $translate[$lang]['description']??$notification['description'] !!}</textarea>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group" style="text-align: left">
                                <label>{{\App\CPU\translate('Image')}}</label>
                                <small style="color: red">* ( {{\App\CPU\translate('Ratio')}} 3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                           for="customFileEg1">{{\App\CPU\translate('Choose file')}}</label>
                                </div>
                                <hr>
                                <center>
                                    <img style="width: 40%;border: 1px solid; border-radius: 10px;" id="viewer"
                                         src="{{asset('storage/testimonials')}}/{{$notification['image']}}"
                                         alt="image"/>
                                </center>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Update')}}</button>
                        </form>
                    </div>
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
