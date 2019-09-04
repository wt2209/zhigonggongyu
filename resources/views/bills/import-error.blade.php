<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">导入结果</h3>
    </div>
    <div class="box-body">
        @if ($errors->has('excel'))
            <div class="alert alert-danger alert-dismissible">
                <h4><i class="icon fa fa-ban"></i> 导入错误</h4>
                @foreach($errors->get('excel') as $excel)
                    <p>错误：{{ $excel }}</p>
                @endforeach
                <br>
                <p>本次导入已全部取消</p>
            </div>
        @else
            <div class="alert alert-success alert-dismissible">
                <h4><i class="icon fa fa-ban"></i> 导入成功</h4>
                <p>没有错误，本次导入已成功</p>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('bills.import') }}" class="btn btn-success btn-sm ">重新导入</a>
                <a href="{{ route('bills.index') }}" class="btn btn-primary btn-sm ">返回明细</a>
            </div>
        </div>
    </div>
</div>