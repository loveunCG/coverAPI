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
    $('#signOutBtn').click(function () {
        $.confirm({
            title: 'Information?',
            content: 'Are you sure sign out this account?',
            theme: 'material',
            columnClass: 'small',
            closeIcon: true,
            typeAnimated: true,
            draggable: true,
            icon: 'fa fa-sign-out',
            buttons: {
                formSubmit: {
                    text: '确定',
                    btnClass: 'btn-blue',
                    action: function () {
                        $('#logout-form').submit();
                    }
                },
                cancel: {
                    text: '取消',
                    btnClass: 'btn-green',
                    action: function () {}
                }

            }
        });

    })
</script>
<!-- End loading page level scripts-->
