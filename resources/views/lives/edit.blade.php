@extends('lives.common._header')

@section('lives.content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{$pageTitle}}</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{route('lives.index')}}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                </div> <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{\URL::previous()}}" class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        @include('lives.form._edit_form')
    </div>
@endsection

@push('lives.scripts')
    <script>
        $(function() {
            $('#person_identify').on('input', function () {
                if ($(this).val().length == 18) {
                    $.post('{{route('people.identify')}}', {
                        identify: $(this).val(),
                        _token: '{{csrf_token()}}'
                    }, function (data) {
                        if (data) {
                            $('#person_name').val(data.name);
                            $('#person_department').val(data.department);
                            $('#person_phone_number').val(data.phone_number);
                            $('#person_entered_at').val(data.entered_at);
                            $('#person_contract_start').val(data.contract_start);
                            $('#person_contract_end').val(data.contract_end);
                            $('.gender').attr('checked',false).each(function () {
                                if ($(this).val() == data.gender) {
                                    $(this)[0].checked = true
                                }
                            })
                            $('.education').attr('checked',false).each(function () {
                                if ($(this).val() == data.education) {
                                    $(this)[0].checked = true
                                }
                            })
                        }
                    })
                }
            })
        })
    </script>
    @endpush
