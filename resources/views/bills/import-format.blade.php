<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> 文件格式说明 </h4>
            支持 xls 和 xlsx 格式的文件。文件内容必须在第一个工作表中。<br>
            第一行必须是标题行，其中， A-F 列分别必须是：房间号/位置，姓名，费用类型，金额，费用说明，缴费日期。其他列不做要求<br>
            要导入的数据必须从第二行开始，缴费日期格式为：2021-1-4，可不填。<br>
            文件中的金额是负数时，就相当于是退费。保存时，“缴费时间”将使用表格中的缴费日期或当前时间。<br>
            一次性导入超过2000条数据可能会发生错误，超过2000条时，请分批导入。
        </div>
    </div>
</div>