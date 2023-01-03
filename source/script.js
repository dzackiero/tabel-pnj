const loginForm = document.querySelector('#form');

loginForm.addEventListener('submit', function (e) {
  e.preventDefault();
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const captcha = document.getElementById('captcha').value;
  const creds =
    'username=' + username + '&password=' + password + '&captcha=' + captcha;
  const xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (this.responseText != '') {
        const pastAlert = document.querySelector('.alert');
        if (pastAlert) {
          pastAlert.remove();
        }

        let captchaImage = document.querySelector('.captcha-image');
        const captchaContainer = document.querySelector('.captcha-container');

        if (captchaImage) {
          captchaImage.remove();
          captchaImage = document.createElement('img');
          captchaImage.classList.add('captcha-image');
          captchaImage.setAttribute('src', 'captcha.php');
          captchaContainer.append(captchaImage);
        }

        const alert = document.createElement('div');
        alert.classList.add(
          'alert',
          'alert-danger',
          'alert-dismissible',
          'fade',
          'show'
        );
        alert.setAttribute('role', 'alert');

        const alertButton = document.createElement('button');
        alertButton.classList.add('btn-close');
        alertButton.setAttribute('type', 'button');
        alertButton.setAttribute('data-bs-dismiss', 'alert');

        alert.append(this.responseText);
        alert.append(alertButton);
        loginForm.prepend(alert);
      } else {
        window.location = 'index.php';
      }
    }
  };

  xhr.open('POST', 'auth.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send(creds);
});
