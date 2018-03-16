<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"D:\phpStudy\WWW\web\Shopping\public/../application/admin\view\goods\goods_save_class.html";i:1521166218;}*/ ?>
<style>
    .box-body table tr td input{
        border-radius: 4px;
        width: 90%;
    }
    .box-body table tbody{

        width: 100%;
    }
    table td:nth-child(2){
        display: block;
    }
</style>
<section class="content">
    <div class="callout callout-info">
        <h4 style="float: left;">提示!</h4>
        <p style="max-width: 100%;color:#FFF;">
            <b style="color: #00a65a;">特别声明</b>：<br/>1.商品分类最多分为三级添加或者修改分类时, 应注意选择对应的上级<br/>
            2.最多成为第三级,如果设置为第二级, 只选择第一级即可<br/>
            3.根据排序进行由小到大排列显示。

        </p>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">商品分类-新增</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">

                        <div id="main" style="width: 100%; height: auto; ">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <form role="form" id="class_form" style="width: 100%; ">
                                        <input type="hidden" name="goods_class_id" value="<?php echo isset($details['goods_class_id'])?$details['goods_class_id']: ''; ?>">
                                        <div class="box-body" >
                                            <table class="form" style="float: left;">
                                                <tbody>
                                                <tr>
                                                    <th class="formTitle">分类名称<font face="宋体">*</font></th>
                                                    <td class="formValue">
                                                        <input id="class_name" name="class_name" type="text" value="<?php echo isset($details['class_name'])?$details['class_name']: ''; ?>" class="form-control" placeholder="分类名称" isvalid="yes" checkexpession="NotNull">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="formTitle">上级分类</th>
                                                    <td class="formValue">
                                                        <select class="name border-bottom" id="child_class_one" name="child_class_one" style="background-color: transparent;border:1px solid #ccc;height:34px;width:auto;border-radius:4px;font-size:14px;font:inherit; ">
                                                            <?php if(isset($details['child_class_name'])): ?>
                                                            <option value="<?php echo $details['child_class_id']; ?>"><?php echo $details['child_class_name']; ?></option>
                                                            <option value="0">顶级分类</option>
                                                            <?php else: ?>
                                                            <option value="0">顶级分类</option>
                                                            <?php endif; foreach($class_array as $k=>$v): ?>
                                                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <select class="name border-bottom" id="child_class_two" name="child_class_two" style="background-color: transparent;border:1px solid #ccc;height:34px;width:auto;border-radius:4px;font-size:14px;font:inherit; ">
                                                            <?php if(isset($details['subgrade_class_name'])): ?>
                                                            <option value="<?php echo $details['subgrade_class_id']; ?>"><?php echo $details['subgrade_class_name']; ?></option>
                                                            <option  value="0">请选择商品分类</option>
                                                            <?php else: ?>
                                                            <option  value="0">请选择商品分类</option>
                                                            <?php endif; ?>

                                                        </select>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th class="formTitle">导航显示</th>
                                                    <td class="formValue">
                                                        <input type="checkbox" id="is_display" name="is_display" class="make-switch" <?php if(isset($details['is_display']) && $details['is_display'] == 0): ?>checked <?php endif; ?> data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="formTitle">是否推荐</th>
                                                    <td class="formValue">
                                                        <input type="checkbox" id="is_recommend" name="is_recommend" class="make-switch" <?php if(isset($details['is_recommend']) && $details['is_recommend'] == 0): ?>checked <?php endif; ?> data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="formTitle">排序<font face="宋体">*</font></th>
                                                    <td class="formValue">
                                                        <input id="goods_sort" name="goods_sort" type="text" class="form-control" placeholder="数字" value="<?php echo isset($details['goods_sort'])?$details['goods_sort']: ''; ?>" isvalid="yes" checkexpession="NotNull" value="0" style="width: 13%;">
                                                    </td>
                                                </tr>
                                                </tbody></table>
                                        </div>
                                        <div class="box-footer" >
                                            <button type="button" id="goods_class_save" class="btn btn-block btn-info" style="width:100px;float: left;margin-left: 6rem;">保存</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script type="text/javascript" src="/static/js/modalmsg.js"></script>
<script type="text/javascript" src="/static/js/goods/goods_save_class.js"></script>
