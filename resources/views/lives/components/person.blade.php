<div class="@if($room->person_number > 1 || count($room->records) > 1) col-md-6 @endif person-card-container" style="padding: 3px;">
    <div class="person-card">
        <div class="person-title">
            <div class="person-name">
                {{$record->person->name}}
                <span style="font-size:12px">
                    ({{$record->person->gender}}@if($record->person->education !== \App\Models\Person::EDUCATION_UNKNOWN)，{{\App\Models\Person::$educationMap[$record->person->education]}}@endif)
                </span>
            </div>
            <div class="person-type">
                {{$record->type->title}}
            </div>
        </div>
        <div class="person-detail">
            <div class="col-md-5" style="padding: 0;">
                <p>{{$record->person->department}}</p>
                <p>{{$record->person->phone_number}}</p>
            </div>
            <div class="col-md-7" style="padding: 0;text-align:right;">
                <p>{{$record->person->entered_at ? '进公寓:' . $record->person->entered_at : '&nbsp;'}}</p>
                <p>{{$record->record_at ? '本房间:' . $record->record_at : '&nbsp;'}}</p>
            </div>
        </div>
        @if($record->person->serial)
            <div class="person-detail">
                <div class="col-md-12" style="padding: 0;">
                    <p>工号: {{$record->person->serial}}</p>
                </div>
            </div>
        @endif
        @showContractDetail($record)
        <div class="person-detail">
            <p>
                @hasContract($record)
                劳动合同:{{$record->person->contract_start}}—{{$record->person->contract_end}}
                @endif
            </p>
            <p>
                @hasRentContract($record)
                房间租期:{{$record->start_at}}—{{$record->end_at}}
                @endif
            </p>
        </div>
        @endif
        <div class="person-detail">
            <p><strong>身份证号:</strong>{{$record->person->identify}}</p>
            <p><strong>退休日期(参考)：</strong>{{$record->person->retired_at}}</p>
            <p class="remark"><strong>备注：</strong>{{$record->person->remark}}</p>
        </div>
        <div class="person-actions">
            @if (Admin::user()->can('lives.quit'))
                <button data-url="{{route('lives.quit', ['id' => $record->id])}}" class="btn btn-warning btn-xs quit">退房</button>
            @endif
            @if (Admin::user()->can('lives.move'))
                <a href="{{route('lives.change', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">调房</a>
            @endif
            @if(Admin::user()->can('lives.edit'))
                <a href="{{route('lives.edit', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">修改</a>
            @endif
            @if(Admin::user()->can('lives.renew'))
                @hasRentContract($record)
                    <a href="{{route('lives.prolong', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">续签</a>
                @endif
            @endif
        </div>
    </div>
</div>