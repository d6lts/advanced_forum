<?php
// $Id$

/**
 * advf-forum-post.tpl.php is the template file for both
 * the top post (the node) and the comments/replies
 * Changes here will affect an individual forum post.
 
 * The following standard variables are available to you:
    $top_post    - TRUE if we are formatting the main post (ie, not a comment)
    $title       - Title of this post/comment
    $content     - Content of this post/comment
    $reply_link  - Separated out link to reply to topic
    $jump_first_new - Shows number of new (to user) comments and links to the first one
    $links       - Formatted links (reply, edit, delete, etc)
    $links_array - Unformatted array of links
    $submitted   - Formatted date post/comment submitted

    $accountid   - ID of the poster
    $name        - User name of poster
 */
?>

<?php if ($top_post): ?>
  <?php $postclass = "top-post"; ?> 
  
  <div class="forum-post-header">
    <?php print $reply_link; ?>
    <?php print $jump_first_new; ?>
  </div>
  
<?php endif; ?>

<div class="<?php print $postclass ? $postclass . ' ' : ''; ?>forum-comment<?php print $row_class ? ' forum-comment-' . $row_class : ''; print $comment->new ? ' comment-new forum-comment-new' : ''; ?> clearfix">

  <div class="post-info clearfix">
    <?php if (!$top_post): ?>
      <?php if ($title): ?>
        <div class="posttitle">
          <?php print $title ?>
        </div>
      <?php endif; ?>    
      
      <span class="post-num"><?php print $comment_link . ' ' . $page_link; ?></span>
    <?php endif; ?>
  </div>

  <div class="forum-post-wrapper">
    <div class="forum-comment-left">
      <div class="user-info">
        <?php print $user_info_pane; ?>   
     </div>
    </div>

    <div class="forum-comment-right clearfix">
      <div class="posted-on"><?php print t("Posted: ") . $date ?>
        <?php if ($comment->new) : ?>
          <a id="new"></a>
          <span class="new">- <?php print $new ?></span>
        <?php endif ?>
      </div>
      
      <div class="content">
        <?php print $content ?>
      </div>  

    </div>
    
    <?php if ($signature): ?>
      <div class="user-signature clear-block">
        <?php print $signature ?>
      </div>
    <?php endif; ?> 
    
    <?php if ($links): ?>
      <div class="links">
        <?php print $links ?>
      </div>
    <?php endif; ?>
 
  </div>
</div>