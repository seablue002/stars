<?php /*a:0:{}*/ ?>
<li class="wow zoomIn"> <?php
    $url = getUrl('', ['parent_dir_path'=> $vo['parent_dir_path'], 'column_dir_path'=> $vo['column_dir_path']], $vo['id']);
 ?> <div class="eya_tjpro_img">
    <a href="<?php echo htmlentities((string) $url); ?>" target="_blank" class="pro_img">
      <img src="/storage/<?php echo htmlentities((string) $vo['cover_url']); ?>" alt="<?php echo htmlentities((string) $vo['title']); ?>" />
      <i></i>
    </a>
  </div>
  <div class="eya_tjpro_text">
    <a href="<?php echo htmlentities((string) $url); ?>" target="_blank" title="<?php echo htmlentities((string) $vo['title']); ?>" class="eya_tjpro_name"><?php echo htmlentities((string) $vo['title']); ?></a>
    <p>型号：ABC-6109</p>
    <em>
      <a href="<?php echo htmlentities((string) $url); ?>" target="_blank"></a>
    </em>
  </div>
</li>