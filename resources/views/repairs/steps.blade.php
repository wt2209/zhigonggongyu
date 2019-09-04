<div class="box" style="border: none;margin-bottom: 10px;">
    <div class="box-body">
        <div class="el-steps el-steps--horizontal">
            @foreach (\App\Models\Repair::$steps as $key => $step)
                <div class="el-step is-horizontal is-center" style="flex-basis: 25%; margin-right: 0px;">
                    <a href="{{route($step['name'])}}" style="text-decoration: none;">
                        <div class="el-step__head
                            @if ($key < $currentStep)
                                is-finish
                            @elseif ($key == $currentStep)
                                is-process
                            @else
                                is-wait
                            @endif ">
                            <div class="el-step__line" style="margin-right: 0px;">
                                <i class="el-step__line-inner"></i>
                            </div>
                            <div class="el-step__icon is-text"><!---->
                                <div class="el-step__icon-inner">{{$key}}</div>
                            </div>
                        </div>
                        <div class="el-step__main">
                            <div class="el-step__title
                                @if ($key < $currentStep)
                                    is-finish
                                @elseif ($key == $currentStep)
                                    is-process
                                @else
                                    is-wait
                                @endif ">
                                {{$step['label']}}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>