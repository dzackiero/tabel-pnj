<nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link <?= $current_page-1 < 1 ? "disabled" : "" ?>" href="index.php?halaman=<?= $current_page-1 ?><?= $keyword != "" ? "&keyword=$keyword" : "" ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">Previous</a>
          </li>
          <?php for($i = 1; $i <= $total_page; $i++) : ?>
            <li class="page-item"><a class="page-link <?= $current_page == $i ? "active" : "" ?>" href="index.php?halaman=<?= $i ?><?= $keyword != "" ? "&keyword=$keyword" : "" ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>"><?= $i ?></a></li>
          <?php endfor ?>
          <li class="page-item <?= $current_page+1 > $total_page ? "disabled" : "" ?>"><a class="page-link" href="index.php?halaman=<?= $current_page+1 ?><?= $keyword != "" ? "&keyword=$keyword" : "" ?><?= $sort != "" ? "&sort=$sort" : "" ?>&<?= $sortBy != "" ? "&sortby=$sortBy" : "" ?>">Next</a></li>
        </ul>
      </nav>