<h1>フォルダーの一覧</h1>
<?php foreach ($folders as $folder): ?>
<section>
  <a href="/folders/folder_pages/<?php echo $folder['name']; ?>"><?php echo $folder['name']; ?></a>
</section>
<?php endforeach ?>