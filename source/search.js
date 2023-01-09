const searchEl = document.querySelector('#search');
const sortSelect = document.querySelector('#sort-select');
const container = document.querySelector('#table-container');

const updateTable = function (e) {
  const xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      container.innerHTML = this.responseText;
    }
  };

  xhr.open(
    'GET',
    'jadwal.php?keyword=' + searchEl.value + '&sort=' + sortSelect.value,
    true
  );
  xhr.send();
};

searchEl.addEventListener('keyup', updateTable);
sortSelect.addEventListener('change', updateTable);
