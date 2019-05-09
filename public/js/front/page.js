var isMobile = 0;
    // isLogin = 0; //0:未登入 ; 1:已登入


if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    isMobile = 1;
} else {
    isMobile = 0;
}


$(function () {
    $('.login_btn').on('click', loginClick); //登录
    // $('.logout_btn').on('click', logoutClick); //登出
    // $('.send_btn').on('click', login_send); //弹窗登录送出

    $('.popup_close').on('click', closeClick); //关闭popup


    // $('.nav_icon').on('click', function (event) {
    //     event.preventDefault();
    //     $('.nav_icon').toggleClass('close');
    //     $('.nav_area').slideToggle();
    // });


    new toggleColor('#ch', ['#fff', '#fae733'], 1400);
    new toggleColor('#ch1', ['#fff', '#fc36d8'], 600);
    new toggleColor('#ch2', ['#fff', '#27c246'], 1000);
    new toggleColor('#ch3', ['#fff', '#fc36d8'], 800);
    new toggleColor('#ch4', ['#fff', '#fc3658'], 600);

    $('.open_sreach_btn').on('click',function(event){
        event.preventDefault();
        $('.search_bet').slideToggle();
    });

});


function loginClick(event) {
    event.preventDefault();
    //登录
    $('.login_pop').fadeIn();
}

function logoutClick(event) {
    event.preventDefault();
    //登出
}


//弹窗登录
function login_send(event) {
    event.preventDefault();

    //登入成功后执行
    loginSuccess();
}


function loginSuccess() {
    $('.popup').fadeOut();
    // $('.waiting').fadeOut();

    isLogin = 1;
    $('.login_btn').css({display: 'none'});
    $('.logout_btn').css({display: 'inline'});

    //带入input的值
    $('.menber_btns span').html('欢迎，' + $('#checkLogin input[name^="member_id"]').val());
    $('.menber_btns span').css({display: 'inline'});
    $('#memberName').val($.trim($('#checkLogin input[name^="member_id"]').val()));
    $('#memberOverage').val($.trim($('#checkLogin input[name^="account_money"]').val()));
    $('#checkLogin input[name^="member_id"]').val('');
    $('#checkLogin input[name^="account_money"]').val('');
    $('#guestshow').hide();
    $('#membershow').fadeIn();
}

function logoutSuccess() {
    isLogin = 0;
    $('.login_btn').css({display: 'inline'});
    $('.logout_btn').css({display: 'none'});
    $('.menber_btns span').html('');
    $('.menber_btns span').css({display: 'none'});
    $('#guestshow').fadeIn();
    $('#membershow').hide();
    $('#memberName').val('');
    $('#memberOverage').val('');
}


//关闭popup
function closeClick(event) {
    event.preventDefault();
    $(event.currentTarget).parent().parent().fadeOut();
}



function toggleColor( id , arr , s ){
    var self = this;
    self._i = 0;
    self._timer = null;

    self.run = function(){
        if(arr[self._i]){
            $(id).css('color', arr[self._i]);
        }
        self._i == 0 ? self._i++ : self._i = 0;
        self._timer = setTimeout(function(){
            self.run( id , arr , s);
        }, s);
    }
    self.run();
}