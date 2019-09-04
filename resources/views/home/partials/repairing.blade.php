<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            正在维修
            @if ($repairingCount = count($repairing))
                <span class="pull-right badge bg-yellow" style="margin-left:5px;">
                    {{ $repairingCount }}
                </span>
            @endif
        </h3>
    </div>
    <div class="box-body" style="height:151px;">
        <ul class="repairing-list">
            @foreach($repairing as $repair)
            <li>
                {{ $repair->created_at }} ： {{ $repair->location }} -- {{ $repair->content }}
            </li>
            @endforeach
        </ul>
    </div>
</div>
