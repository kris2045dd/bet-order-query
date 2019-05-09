var totalSec = 60;
var countTime;
var isSending = false;

$(document).ready(function () {

    if(isLogin == 1){
        getQueryPattern();
        queryLists();
    }

    $('.send_btn').click(function () {
        event.preventDefault();

        formData = {};
        formData['name'] = $('#checkLogin input[name^="member_id"]').val();
        formData['overage'] = $('#checkLogin input[name^="account_money"]').val();
        formData['_token'] = $('#checkLogin input[name^="_token"]').val();

        if(formData['name'] === ''){
            alert('请填写会员账号!');
            $('#checkLogin input[name^="member_id"]').focus();
            return false;
        }

        if(formData['overage'] === ''){
            alert('请填写账户余额!');
            $('#checkLogin input[name^="account_money"]').focus();
            return false;
        }

        if(isNaN(formData['overage'])){
            alert('账户余额格式不正确!');
            $('#checkLogin input[name^="account_money"]').focus();
            return false;
        }


        $('.waiting').fadeIn();
        $.post('/login', formData, function (res) {
            if (res.jQuery === undefined) {
                result = $.parseJSON(res);
                switch (result.return) {
                    case 'true':
                        loginSuccess();
                        getQueryPattern();
                        queryLists(1);
                        break;
                    case 'false':
                        alert(result.msg);
                        $('.waiting').fadeOut();
                        break;
                }
            }
        });
    });



    $('.logout_btn').click(function () {
        event.preventDefault();

        $.getJSON('/logout', function (res) {
            switch (res.return) {
                case 'true':
                    logoutSuccess();
                    $('#datashow').find('div').remove();
                    $('#memberName').val('');
                    $('#memberOverage').val('');
                    $('#paging').html('');
                    $('.totalBetNum').hide();
                    $('#querynullshow').hide();
                    alert('登出成功');

                    clearInterval(countTime);
                    totalSec = 60;

                    $('#getquery').text('获取注单数据');
                    $('#getquery').removeClass('notwork');
                    $('#getquery').on('click', getqueryClick);
                    
                    break;
                case 'false':
                    alert(res.msg);
                    break;
            }
        })
    });


    $('#getquery').on('click', { force:1, showWait:1}, getqueryClick);
    $('.check_btn').on('click', { force:0 }, getqueryClick);

});


function getqueryClick(event){
    event.preventDefault();

    if(!isSending){
        if(isLogin == 0){
            alert('请先登入才可查询');
            $('.login_pop').fadeIn(); 
        } else {
            if(event.data.showWait == 1){
                $('.waiting').fadeIn();
            }
            queryLists(event.data.force);
        }
    }

}



function getQueryPattern() {
    if ($.trim($('#memberName').val()) !== '' && $.trim($('#memberOverage').val()) !== '') {
        $('#game_palt option').each(function () {
            if ($(this).val() !== 'all') {
                $(this).remove();
            }
        });
        $.getJSON('/getquerypatterns', function (res) {
            switch (res.return) {
                case true:
                    for (n = 0; n < res.platforms.length; n++) {
                        $('#game_palt').append(
                            '<option value="' + res.platforms[n] + '">' + res.platforms[n] + '</option>'
                        )
                    }
                    break;
                case false:
                    break;
            }
        });
    }
}

function queryLists(force,page,num) {
    force = (typeof force !== 'undefined') ? force : 0;
    page = (typeof page !== 'undefined') ? page : 0;
    num = (typeof num !== 'undefined') ? num : 10;

    if ($.trim($('#memberName').val()) !== '' && $.trim($('#memberOverage').val()) !== '') {
        $('#processing').show();
        $('#datashow').html('');
        $('#querynullshow').hide();
        $('#membershow').hide();
        formData = {};
        formData['platform'] = $.trim($('#game_palt option:selected').val()) === 'all' ? 'false' : $.trim($('#game_palt option:selected').val());
        formData['bet'] = $.trim($('#bet_dollar').val()) === '' ? 'false' : $.trim($('#bet_dollar').val());
        formData['no'] = $.trim($('#bet_tail').val()) === '' ? 'false' : $.trim($('#bet_tail').val());
        formData['page'] = page;
        formData['_token'] = $('#checkLogin input[name^="_token"]').val();
        formData['force'] = force;

        $.ajax({
            type: 'POST',
            url: '/querylists',
            data: formData,
            // async: false,
            cache: false,
            beforeSend: function(){
                isSending = true;
            },
            success: function (res) {
                $('#processing').hide();

                $('.waiting').fadeOut();
                if(force == 1){
                    clearInterval(countTime);
                    countTime = setInterval(countdown, 1000);
                }

                if (res.jQuery === undefined) {
                    result = $.parseJSON(res);
                    $.removeCookie('queryCheck', true);
                    switch (result.return) {
                        case true:
                            $('#datashow').find('div').remove();
                            if (result.data.length > 0) {
                                $('#membershow').hide();
                                for (n = 0; n < result.data.length; n++) {
                                    if (result.data[n].mark !== undefined) {
                                        $('#datashow').append(
                                            '<div class="d_list">' +
                                            '<div><input type="hidden" class="hideid" value="' + result.data[n].id + '">' + result.data[n].platform + '<span>(' + result.data[n].game+ ')</span></div>' +
                                            '<div>' + result.data[n].game + '</div>' +
                                            '<div>' + result.data[n].order_no + '</div>' +
                                            '<div>' + result.data[n].bet_datetime + '</div>' +
                                            '<div>' + result.data[n].bet + '</div>' +
                                            '<div>' + result.data[n].bet_reward + '</div>' +
                                            '<div class="regs">己完成申请</div>'
                                        );
                                    } else {
                                        $('#datashow').append(
                                            '<div class="d_list">' +
                                            '<div><input type="hidden" class="hideid" value="' + result.data[n].id + '">' + result.data[n].platform + '<span>(' + result.data[n].game+ ')</span></div>' +
                                            '<div>' + result.data[n].game + '</div>' +
                                            '<div>' + result.data[n].order_no + '</div>' +
                                            '<div>' + result.data[n].bet_datetime + '</div>' +
                                            '<div>' + result.data[n].bet + '</div>' +
                                            '<div>' + result.data[n].bet_reward + '</div>' +
                                            '<div class="regs"><a href="javascript:void(0)" data-id="' + result.data[n].id + '" onclick=regsOrder($(this))>一键办理</a></div>'
                                        );
                                    }
                                }
                                $('.totalBetNum').show();
                            } else {
                                $('#membershow').hide();
                                $('#querynullshow').show();
                            }

                            $('#searchcount').html(result.searchcount);
                            $('#resultall').html(result.count);
                            paging(page, num, result.searchcount);
                            break;
                        case false:

                            break;
                    }
                }
            },
            complete: function(){
                isSending = false;
            }
        });
    } else {
        $('#guestshow').show();
        $('#membershow').hide();
        $('#querynullshow').hide();
    }
}

function regsOrder(item) {
    orderId = $(item).parent('div').parent('div.d_list').find('input').val();
    $.getJSON('/regorder/' + orderId, function (res) {
        switch (res.return) {
            case true:
                $(item).parent('div').parent('div.d_list').find('div.regs').html('己完成申请');
                break;
            case false:
                break;
        }
    });
}

function paging(page,num,count) {
    page = (typeof page !== 'undefined') ? page : 0;
    num = (typeof num !== 'undefined') ? num : 10;
    count = (typeof count !== 'undefined') ? count : 0;

    if (count > 0) {

        count % num > 0 ? pages = Math.floor(count / num) + 1 : pages = Math.floor(count / num);

        page >= 0 ?
            pre = '<div class="pageNum"><a href="javascript:void(0);" onclick="queryLists(0,' + (page - 1) + ',' + num + ')">上一页</a>' :
            pre = '<div class="pageNum"><a href="javascript:void(0);" class="disabled">上一页</a>';

        page < pages ?
            next = '<a href="javascript:void(0);" onclick="queryLists(0,' + (page + 1) + ',' + num + ')">下一页</a></div>' :
            next = '<a href="javascript:void(0);" class="disabled">下一页</a></div>';

        buttons = '';

        for (n = page - 2; n <= page + 4; n++) {
            if (n <= pages && n > 0) {
                if (n === (page + 1)) {
                    buttons += '<a href="javascript:void(0);" class="active" onclick="queryLists(0,' + (n - 1) + ',' + num + ')">' + n + '</a>';
                } else {
                    buttons += '<a href="javascript:void(0);"  onclick="queryLists(0,' + (n - 1) + ',' + num + ')">' + n + '</a>';
                }
            }
        }

        $('#paging').html(pre + buttons + next);

    } else {

        $('#paging').html('');

    }
}


function countdown(){
    $('#getquery').text(totalSec+'秒后...获取注单数据');
    $('#getquery').addClass('notwork');
    $('#getquery').off('click', getqueryClick);
    if(totalSec > 0){
        totalSec --;
    }else if(totalSec == 0){
        clearInterval(countTime);
        totalSec = 60;
        $('#getquery').text('获取注单数据');
        $('#getquery').removeClass('notwork');
        $('#getquery').on('click', { force:1, showWait:1}, getqueryClick);
    }
}


