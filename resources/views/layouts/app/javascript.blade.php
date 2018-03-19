<script type="text/javascript" src="{{asset('public/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/iziModal.min.js')}}"></script>

<script type="text/javascript" src="{{asset('public/js/jquery-confirm.min.js')}}"></script>
<!-- Load jQuery -->
<script type="text/javascript" src="{{asset('public/js/jqueryui-1.10.3.min.js')}}"></script>
<!-- Load jQueryUI -->
<script type="text/javascript" src="{{asset('public/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/jquery.validate.min.js')}}"></script>
<!-- Load Bootstrap -->
<script type="text/javascript" src="{{asset('public/js/enquire.min.js')}}"></script>
<!-- Load Enquire -->
<script type="text/javascript" src="{{asset('public/plugins/velocityjs/velocity.min.js')}}"></script>
<!-- Load Velocity for Animated Content -->
<script type="text/javascript" src="{{asset('public/plugins/velocityjs/velocity.ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/plugins/wijets/wijets.js')}}"></script>
<!-- Wijet -->
<script type="text/javascript" src="{{asset('public/plugins/codeprettifier/prettify.js')}}"></script>
<!-- Code Prettifier  -->
<script type="text/javascript" src="{{asset('public/plugins/bootstrap-switch/bootstrap-switch.js')}}"></script>
<!-- Swith/Toggle Button -->
<script type="text/javascript" src="{{asset('public/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js')}}"></script>
<!-- Bootstrap Tabdrop -->
<script type="text/javascript" src="{{asset('public/plugins/iCheck/icheck.min.js')}}"></script>
<!-- iCheck -->
<script type="text/javascript" src="{{asset('public/plugins/nanoScroller/js/jquery.nanoscroller.min.js')}}"></script>
<!-- nano scroller -->
<script type="text/javascript" src="{{asset('public/js/application.js')}}"></script>
<!-- Load page level scripts-->
<script type="text/javascript" src="{{asset('public/plugins/datatables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('public/plugins/datatables/dataTables.bootstrap.js')}}"></script>
<script>
    let reset_password_modal, account_profile;
    $('#signOutBtn').click(function () {
        $.confirm({
            title: 'Information?',
            content: 'Are you sure sign out this account?',
            theme: 'bootstrap',
            type: 'red',
            columnClass: 'small',
            animationBounce: 1.5,
            closeIcon: true,
            typeAnimated: true,
            draggable: true,
            icon: 'fa fa-sign-out',
            buttons: {
                formSubmit: {
                    text: 'OK',
                    btnClass: 'btn-blue',
                    action: function () {
                        $('#logout-form').submit();
                    }
                },
                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-green',
                    action: function () {}
                }

            }
        });
    });

    $(function(){
        reset_password_modal = $("#Reset_Password").iziModal();
        account_profile = $('#account_profile').iziModal();
        $('#open_reset_password').click(function(){
            reset_password_modal.iziModal('open',{ transition: 'fadeInDown'});
        });

        $('#reset_password_btn').click(function(){
          var submit_data = $('#reset_password_form').serialize();
          var setting = {
            "async": false,
            "url": '{{url('adminMg/changePassword')}}',
            "method": "POST",
            "dataType": "json",
            "processData": false,
            "data":submit_data
          };
          $.ajax(setting).done(function (response) {
            console.log(response);
            var message_title = "warning";
            if(response.response_code){
              message_title = "Ok!"
            }
            $.alert({
                title: message_title,
                content: response.message,
                columnClass: 'small',
                buttons: {
                    ok: function () {
                        if(response.response_code){
                          reset_password_modal.iziModal('close');
                        }
                        return true;
                    }
                }
            });
          });


        });

        $('#account_profile_open').click(function(){
            var setting = {
      				"async": false,
      				"url": '{{url('adminMg/getauthuser')}}',
      				"method": "GET",
      				"dataType": "json",
      				"processData": false
      			};
      			$.ajax(setting).done(function (response) {
              console.log(response);
              $('#admin_profile_name').html(response.data.name);
              $('#admin_profile_email').html(response.data.email);
              account_profile.iziModal('open');
            });
        });
        $('.panel-profile').css('opacity', '80');

    });
</script>
<!-- End loading page level scripts-->
