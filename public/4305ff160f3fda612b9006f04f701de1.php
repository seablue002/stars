<?php /*a:0:{}*/ ?>
 <li class="wow fadeInUp"> <?php
    $url = getUrl('', ['parent_dir_path'=> $vo['parent_dir_path'], 'column_dir_path'=> $vo['column_dir_path']], $vo['id']);
  ?> <div class="eyxnew_text"> <a href="<?php echo htmlentities((string) $url); ?>" target="_blank" title="<?php echo htmlentities((string) $vo['title']); ?>" class="eyxnew_name"><?php echo htmlentities((string) $vo['title']); ?></a>
     <p><?php echo htmlentities((string) $vo['sub_title']); ?></p>
   </div>
   <div class="eyxnew_line"><i></i></div>
   <div class="eyxnew_timebtn">
     <div class="eyxnew_time"><span><?php echo date('Y', $vo['publish_time']); ?></span>
       <font><?php echo date('m-d', $vo['publish_time']); ?></font>
     </div>
     <a href="<?php echo htmlentities((string) $url); ?>" target="_blank" class="eyxnew_timebtn"></a>
   </div>
 </li>