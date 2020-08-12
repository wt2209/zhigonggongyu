<form action="{{route('lives.update', ['id' => $record->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_previous_" value="{{\URL::previous()}}">
    <div class="box-body">
        <div class="fields-group">
            <div class="form-group {!! !$errors->has('person.name') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">所属类型</label>
                <div class="col-sm-8">
                    @if($errors->has('type_id'))
                        @foreach($errors->get('type_id') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <select
                            name="type_id"
                            style="width:300px;"
                            class="form-control" 
                            value="{{old('type_id')}}"
                        >
                            @foreach($types as $id=>$title)
                                <option value="{{$id}}" @if($record->type_id === $id) selected="selected" @endif>{{$title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.name') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">姓名</label>
                <div class="col-sm-8">
                    @if($errors->has('person.name'))
                        @foreach($errors->get('person.name') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input type="text" id="person_name"
                               value="{{old('person.name') ?: $record->person->name}}"
                               name="person[name]"  class="form-control" placeholder="输入 姓名">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.identify') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">身份证号</label>
                <div class="col-sm-8">
                    @if($errors->has('person.identify'))
                        @foreach($errors->get('person.identify') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input type="text" style="width:300px;"
                               value="{{old('person.identify') ?: $record->person->identify}}"
                               id="person_identify" name="person[identify]"
                               class="form-control" placeholder="请首先输入身份证号，以便查找此人信息">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.gender') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">性别</label>
                <div class="col-sm-8">
                    @if($errors->has('person.gender'))
                        @foreach($errors->get('person.gender') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    @if(old('person.gender'))
                        <label class="radio-inline">
                            <input type="radio" name="person[gender]" class="gender" value="男" @if(old('person.gender') == '男') checked @endif > 男
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="person[gender]" class="gender" value="女" @if(old('person.gender') == '女') checked @endif> 女
                        </label>
                    @else
                        <label class="radio-inline">
                            <input type="radio" name="person[gender]" class="gender" value="男" @if($record->person->gender == '男') checked @endif > 男
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="person[gender]" class="gender" value="女" @if($record->person->gender == '女') checked @endif> 女
                        </label>
                    @endif
                </div>
            </div>

            <div class="form-group {!! !$errors->has('person.education') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">学历</label>
                <div class="col-sm-8">
                    @if($errors->has('person.education'))
                        @foreach($errors->get('person.education') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    @if(old('person.education'))
                        @foreach(\App\Models\Person::$educationMap as $key => $edu)
                            <label class="radio-inline">
                                @if($key == old('person.education'))
                                    <input type="radio" name="person[education]" class="education" value="{{$key}}" checked> {{$edu}}
                                @else
                                    <input type="radio" name="person[education]" class="education" value="{{$key}}"> {{$edu}}
                                @endif
                            </label>
                        @endforeach
                    @else
                        @foreach(\App\Models\Person::$educationMap as $key => $edu)
                            <label class="radio-inline">
                                <input type="radio" name="person[education]" class="education" value="{{$key}}" @if($record->person->education == $key) checked @endif> {{$edu}}
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="form-group {!! !$errors->has('person.department') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2 control-label">部门</label>
                <div class="col-sm-8">
                    @if($errors->has('person.department'))
                        @foreach($errors->get('person.department') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input type="text"
                               value="{{old('person.department') ?: $record->person->department}}"
                               id="person_department"
                               name="person[department]" class="form-control" placeholder="输入 部门">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.phone_number') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">电话</label>
                <div class="col-sm-8">
                    @if($errors->has('person.phone_number'))
                        @foreach($errors->get('person.phone_number') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input type="text"
                               id="person_phone_number"
                               value="{{old('person.phone_number') ?: $record->person->phone_number}}"
                               name="person[phone_number]"  class="form-control" placeholder="输入 电话">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.entered_at') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">入住公寓时间</label>
                <div class="col-sm-8">
                    @if($errors->has('person.entered_at'))
                        @foreach($errors->get('person.entered_at') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input style="width: 200px" type="text"
                               id="person_entered_at"
                               value="{{old('person.entered_at') ?: $record->person->entered_at}}"
                               name="person[entered_at]" class="form-control" placeholder="格式：2018-9-4">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('record_at') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">入住本房间</label>
                <div class="col-sm-8">
                    @if($errors->has('record_at'))
                        @foreach($errors->get('record_at') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input style="width: 200px" type="text"
                               id="person_entered_at"
                               value="{{old('record_at') ?: $record->record_at}}"
                               name="record_at" class="form-control" placeholder="格式：2018-9-4">
                    </div>
                </div>
            </div>

            <div class="form-group {!! !$errors->has('person.contract_start') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">劳动合同起始日</label>
                <div class="col-sm-8">
                    @if($errors->has('person.contract_start'))
                        @foreach($errors->get('person.contract_start') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input style="width: 200px" type="text"
                               value="{{old('person.contract_start') ?: $record->person->contract_start}}"
                               id="person_contract_start"
                               name="person[contract_start]" class="form-control" placeholder="格式：2018-9-4">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('person.contract_end') ? '' : 'has-error' !!}  ">
                <label class="col-sm-2  control-label">劳动合同结束日</label>
                <div class="col-sm-8">
                    @if($errors->has('person.contract_end'))
                        @foreach($errors->get('person.contract_end') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="form-inline">
                        <div class="input-group">
                            <input style="width: 200px" type="text"
                                   id="person_contract_end"
                                   value="{{old('person.contract_end', $record->person->contract_end) === '无固定期'
                                        ? \App\Models\Person::CONTRACT_RETIRE_END
                                        : old('person.contract_end', $record->person->contract_end)}}"
                                   name="person[contract_end]" class="form-control" placeholder="格式：2018-9-4">
                        </div>
                        <div class="checkbox" style="margin-left: 10px;">
                            <label>
                                <input type="checkbox" id="no-deadline"
                                   @if (old('person.contract_end', $record->person->contract_end) === '无固定期')
                                       checked
                                   @endif
                                > 无固定期
                            </label>
                        </div>
                        <script>
                            if ($('#person_contract_end').val() == '{{\App\Models\Person::CONTRACT_RETIRE_END}}') {
                                $('#no-deadline').attr('checked', 'checked');
                                $('#person_contract_end').attr('readonly', 'readonly');
                            }
                            $('#no-deadline').click(function() {
                                if($(this).prop("checked")) {
                                    $('#person_contract_end').val('{{\App\Models\Person::CONTRACT_RETIRE_END}}')
                                        .attr('readonly', 'readonly');
                                } else {
                                    $('#person_contract_end').val('').removeAttr('readonly');
                                }
                            })
                        </script>
                    </div>
                </div>
            </div>

            @if($record->type->has_contract)
            <div class="form-group {!! !$errors->has('start_at') ? '' : 'has-error' !!}  ">
                <label for="start_at" class="col-sm-2  control-label">租期开始日</label>
                <div class="col-sm-8">
                    @if($errors->has('start_at'))
                        @foreach($errors->get('start_at') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input style="width: 200px" type="text"
                               value="{{old('start_at') ?: $record->start_at}}"
                               name="start_at" class="form-control" placeholder="格式：2018-9-4">
                    </div>
                </div>
            </div>
            <div class="form-group {!! !$errors->has('end_at') ? '' : 'has-error' !!}  ">
                <label for="end_at" class="col-sm-2  control-label">租期结束日</label>
                <div class="col-sm-8">
                    @if($errors->has('end_at'))
                        @foreach($errors->get('end_at') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <div class="input-group">
                        <input style="width: 200px" type="text"
                               value="{{old('end_at') ?: $record->end_at}}"
                               name="end_at" class="form-control" placeholder="格式：2018-9-4">
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group {!! !$errors->has('person.remark') ? '' : 'has-error' !!}  ">
                <label for="person_remark" class="col-sm-2  control-label">备注</label>
                <div class="col-sm-8">
                    @if($errors->has('person.remark'))
                        @foreach($errors->get('person.remark') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                        @endforeach
                    @endif
                    <textarea name="person[remark]" class="form-control" rows="5" placeholder="输入 备注">{{old('person.remark') ?: $record->person->remark}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="btn-group pull-right">
                <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 保存">保存</button>
            </div>
        </div>
    </div>
    <!-- /.box-footer -->
</form>
