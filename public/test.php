<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
            <script>
              // Initialize Firebase
              var config = {
                apiKey: "AIzaSyCvxE8AtcZ5r-szyIrCNCWTHu0fB8TTprs",
                authDomain: "tough-hull-204716.firebaseapp.com",
                databaseURL: "https://tough-hull-204716.firebaseio.com",
                projectId: "tough-hull-204716",
                storageBucket: "tough-hull-204716.appspot.com",
                messagingSenderId: "621544466997"
              };
              firebase.initializeApp(config);
            </script>

            <script>
              const messaging = firebase.messaging();
                messaging.requestPermission().then(function() {
                  console.log('Notification permission granted.');
                  // TODO(developer): Retrieve an Instance ID token for use with FCM.
                  // ...
                }).catch(function(err) {
                  console.log('Unable to get permission to notify.', err);
                });

            </script>
</body>
</html>