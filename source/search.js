const searchEl = document.querySelector('#search');
const container = document.querySelector('#table-container');

searchEl.addEventListener('keyup', function (e) {
  const xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      container.innerHTML = this.responseText;
    }
  };

  xhr.open('GET', 'jadwal.php?keyword=' + searchEl.value, true);
  xhr.send();
});
