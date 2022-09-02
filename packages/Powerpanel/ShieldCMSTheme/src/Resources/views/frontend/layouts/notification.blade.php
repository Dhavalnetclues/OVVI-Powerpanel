
<input type="hidden" id="notificationmsg" value="">
<input type="hidden" id="notificationerr" value="">

<script type="text/javascript" src="{{ $CDN_PATH.'assets/js/firebase.js'}}"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-messaging.js"></script>
<script>

var config = {
    'messagingSenderId': '<?php echo Config::get('Constant.messagingSenderId') ?>',
    'apiKey': '<?php echo Config::get('Constant.apiKey') ?>',
    'projectId': '<?php echo Config::get('Constant.projectId') ?>',
    'appId': '<?php echo Config::get('Constant.appId') ?>',
};
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.requestPermission()
        .then(function () {
            $("#notificationmsg").val("Notification permission granted.");
            console.log("Notification permission granted.");
        })
        .catch(function (err) {
            $("#notificationmsg").val(err);
            console.log("Unable to get permission to notify.", err);
        });

        messaging.requestPermission().then(function () {
            $("#notificationmsg").val("Notification permission granted.");
            console.log("Notification permission granted.");

            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function (token) {
            // print the token on the HTML page
            var NotificationToken = window.site_url + "/NotificationToken";
            var message = $("#notificationmsg").val();
            var error = $("#notificationerr").val();
            $.ajax({
                type: 'POST',
                url: NotificationToken,
                data: {
                    token: token,
                    message: message,
                    error: error
                }
            });
        })
        .catch(function (err) {
            $("#notificationerr").val(err);
            console.log("Unable to get permission to notify.", err);
        });

</script>