<div class="col-md-6 room">
    <div class="panel panel-default">
        <div class="panel-heading" style="overflow: hidden">
            <h3 class="panel-title col-md-5 room-title ">
                {{$room->title}} 
                <span style="font-size: 12px;">({{$room->type->title}})</span>
                @if ($room->unpayed) 
                  <a target="_blank" 
                     href="{{route('bills.index', ['location'=>$room->title, '495dc4886075acfb17bfde981a9f5b94'=>'no'])}}" 
                     class="label label-danger"
                     style="font-size: 10px;"
                  >有未缴费用</a> 
                @endif
            </h3>
            <div class="room-remark col-md-7">
                <span style="display: block;width:100%;min-height: 24px;" ondblclick="showEditInput(this)">{{$room->remark}}</span>
                <input type="text" name="remark" data-id="{{$room->id}}" onblur="editRemark(this)"
                        class="form-control" value="{{$room->remark}}">
            </div>
        </div>
        <div class="panel-body" style="padding:5px;">
            @foreach($room->records as $record)
                @component('lives.components.person', ['room'=>$room, 'record'=>$record])
                @endcomponent
            @endforeach
            @if (count($room->records) < $room->person_number)
                @for ($i = 0; $i < $room->person_number - count($room->records); $i++)
                    <div class="@if($room->person_number > 1) col-md-6 @endif person-card-container" style="padding: 3px;">
                        <div class="person-card">
                            <div class="person-add">
                                <a href="{{route('lives.create', ['room_id'=>$room->id])}}">
                                    <i class="fa fa-plus avatar-uploader-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
            @if($room->person_number === 0) 
                <div class="col-md-12 person-card-container" style="padding: 3px;">
                    <div class="person-card">
                        <div class="person-add">
                            {{$room->type->title}}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>