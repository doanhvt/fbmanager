<div class="span6 resizable uniform modal-content" style="width:1000px">
    <form class="form-horizontal e_ajax_submit" action="<?php echo $ajax_link; ?>" enctype="multipart/form-data" method="POST">
        <div class="modal-header"> 
            <span type="button" class="close" data-dismiss="modal"><i class="icon16 i-close-2"></i></span>
            <h3><?php echo $title; ?></h3>
        </div>
        <div class="modal-body bgwhite">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>
                            STT
                        </th>
                        <th>
                            Tên
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Email
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($list_contact) {
                        $stt = 0;
                        foreach ($list_contact as $item) {
                            $stt++;
                            ?>
                            <tr>
                                <td><?php echo $stt; ?></td>
                                <td><?php echo $item->name; ?></td>
                                <td><?php echo $item->phone; ?></td>
                                <td><?php echo $item->email; ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center">Không có contact nào chưa được check bên CRM</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer"> 
            <button type="submit" class="b_add b_edit btn btn-primary">Kiểm tra bên CRM</button>
        </div>
    </form>
</div>