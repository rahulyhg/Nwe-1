AccountKit_OnInteractive = function() {
  AccountKit.init({
    appId: '163515537689481',
    state: document.getElementById('_token').value,
    version: 'v1.0'
  });
};

function loginCallback(response) {
  console.log(response);

  if (response.status === "PARTIALLY_AUTHENTICATED") {
    document.getElementById('code').value = response.code;
    document.getElementById('_token').value = response.state;
    document.getElementById('form').submit();
  }

  else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
      alert('You are not Authenticated');
  }
  else if (response.status === "BAD_PARAMS") {
    // handle bad parameters
    alert('wrong inputs');
  }
}

// phone form submission handler
function smsLogin(role) {
  var countryCode = '+84';
  console.log(role);
  // var phoneNumber = document.getElementById('phone').value;
  document.getElementById('role').value = role;
  AccountKit.login(
    'PHONE',
    {countryCode: countryCode, phoneNumber: ''},
    loginCallback
  );
}
// email form submission handler
function emailLogin(role) {
  var emailAddress = document.getElementById("email-fb").value;
  document.getElementById('role').value = role;
  AccountKit.login('EMAIL', {emailAddress: emailAddress}, loginCallback);
}
