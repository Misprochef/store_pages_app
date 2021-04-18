<div>
  <?php if ($err_msg): ?>
  <h1>対象のWebページが見つかりません。入力したURLに、間違いがないか確認してください。<br> エラーメッセージ : <?= $err_msg ?></h1>
  <?php endif ?>
  <h1><?= $folder_name ?></h1>

  <?php if ($pages): ?>
  <?php foreach ($pages as $page): ?>
  <p><?php echo $page['id']; ?></p>
  <a href=<?php echo $page['url']; ?> target="_blank" rel="noreferrer"><?php echo $page['title']; ?></a>
  <p><?php echo $page['url']; ?></p>
  <?php
  if ($page['img_path']) {
      echo Asset::img($page['img_path']);
  } elseif ($page['fav_path']) {
      echo Asset::img($page['fav_path']);
  } else {
      echo 'img or favicon not found';
  }
  ?>
  <?php echo date('Y/m/d', strtotime($page['updated_at'])); ?>
  <a href="/pages/edit_page/<?php echo $page['title']; ?>">Edit</a>
  <a href="/pages/delete_page/<?php echo $page['title']; ?>">Delete</a>
  <?php endforeach ?>
  <?php endif ?>

</div>