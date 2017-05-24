<style type="text/css">
    #opc24pay-pay {cursor: pointer}
    #opc24pay-error {color: #CC0000; font-weight: bold}
</style>

<div class="pull-right">
    <img src="<?php echo $opc24pay_button; ?>" id="opc24pay-pay"/>
</div>

<div id="opc24pay-error"></div>
<script type="text/javascript"><!--
    $('#opc24pay-pay').on('click', function() {
        $.ajax({
            type: 'get',
            url: '<?php echo $action; ?>',
            cache: false,
            dataType: 'json',
            beforeSend: function () {
                $('#opc24pay-error').empty();
                $('#opc24pay-pay').css('cursor', 'wait');
            },
            complete: function () {
                $('#opc24pay-pay').css('cursor', 'pointer');
            },
            success: function (ret) {
                console.log(ret);
                if (ret.status == 'SUCCESS') {
                    console.log("APPEND FORM AND SUBMIT");
                    $(ret.form).appendTo('body').submit();
                } else {
                    $('#opc24pay-error').empty().append(ret.message);
                }
            }
        });
    });
//--></script>