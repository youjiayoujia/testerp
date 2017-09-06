
<div class="row">
    <div class="col-xs-12">
        <h3 class="header small lighter blue">速卖通刊登-产品复制</h3>

        <div>
            <form action="">
                <div class="form-group clearfix">
                    <ul class="list-inline">
                        <li>
                            <label for="checkAll">
                                <input id="checkAll" type="checkbox"/>全选/全不选
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="form-group clearfix">
                    <ul class="list-inline account_list">
                        <?php foreach($account_list as $account):?>
                            <li class="col-sm-4">
                                <label>
                                    <input type="checkbox" value="<?php echo $account['id'];?>" />
                                    <?php echo $account['account'];?>
                                </label>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        //全选
        $('#checkAll').click(function(){
            this.checked ? $('.account_list').find(':checkbox').prop('checked', true) : $('.account_list').find(':checkbox').prop('checked', false);
        });
    })
</script>
