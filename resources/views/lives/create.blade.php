@extends('lives.common._header')

@section('lives.content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{$pageTitle}}</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{route('lives.index')}}" class="btn btn-sm btn-default">
                        <i class="fa fa-list"></i>
                        &nbsp;列表
                    </a>
                </div> <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{\URL::previous()}}" class="btn btn-sm btn-default form-history-back">
                        <i class="fa fa-arrow-left"></i>
                        &nbsp;返回
                    </a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        @include('lives.form._create_form')
    </div>
@endsection

@push('lives.scripts')
    <script>
        $(function() {
            $('#person_identify').on('input', function () {
                if ($(this).val().length == 18) {
                    $.get('{{route('people.identify')}}', {
                        identify: $(this).val()
                    }, function (data) {
                        if (data.status === 1) {
                            let person = data.data;
                            $('#person_name').val(person.name);
                            $('#person_department').val(person.department);
                            $('#person_phone_number').val(person.phone_number);
                            $('#person_entered_at').val(person.entered_at);
                            $('#person_contract_start').val(person.contract_start);
                            if (person.contract_end === '无固定期') {
                                $('#no-deadline').trigger('click')
                            } else {
                                $('#person_contract_end').val(person.contract_end);
                            }
                            $('.gender').attr('checked',false).each(function () {
                                if ($(this).val() == person.gender) {
                                    $(this)[0].checked = true
                                }
                            })
                            $('.education').attr('checked',false).each(function () {
                                if ($(this).val() == person.education) {
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
