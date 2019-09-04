<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> 文件格式说明 </h4>
            支持 xls 和 xlsx 格式的文件。文件内容必须在第一个工作表中。<br>
            第一行必须是标题行，其中， A-E 列分别必须是：房间号/位置，姓名，费用类型，金额，费用说明。其他列不做要求<br>
            要导入的数据必须从第二行开始。<br>
            文件中的金额是负数时，就相当于是退费。保存时，系统会自动将退费项目的“缴费时间”填写为当前时间。<br>
            一次性导入超过2000条数据可能会发生错误，超过2000条时，请分批导入。
        </div>
    </div>
</div>