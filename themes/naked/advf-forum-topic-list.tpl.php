<?php
// $Id$

/**
 * @file advf-forum-topic-list.tpl.php
 * Theme implementation to display a list of forum topics.
 *
 * Available variables:
 * - $header: The table header. This is pre-generated with click-sorting
 *   information. If you need to change this, @see template_preprocess_forum_topic_list().
 * - $pager: The pager to display beneath the table.
 * - $topics: An array of topics to be displayed.
 * - $topic_id: Numeric id for the current forum topic.
 * - $forum_description: Description of the forum these topics are in.
 *
 * Each $topic in $topics contains:
 * - $topic->icon: The icon to display.
 * - $topic->moved: A flag to indicate whether the topic has been moved to
 *   another forum.
 * - $topic->title: The title of the topic. Safe to output.
 * - $topic->message: If the topic has been moved, this contains an
 *   explanation and a link.
 * - $topic->zebra: 'even' or 'odd' string used for row class.
 * - $topic->num_comments: The number of replies on this topic.
 * - $topic->new_replies: A flag to indicate whether there are unread comments.
 * - $topic->new_url: If there are unread replies, this is a link to them.
 * - $topic->new_text: Text containing the translated, properly pluralized count.
 * - $topic->created: An outputtable string represented when the topic was posted.
 * - $topic->last_reply: An outputtable string representing when the topic was
 *   last replied to.
 * - $topic->timestamp: The raw timestamp this topic was posted.
 *
 * @see template_preprocess_forum_topic_list()
 * @see theme_forum_topic_list()
 */
?>
<?php print $pager; ?>
<table id="forum-topic-<?php print $topic_id; ?>" class="forum-topics">
  <thead>
    <tr><?php print $header; ?></tr>
  </thead>
  <tbody>
  <?php foreach ($topics as $topic): ?>
    <?php 
    if ($topic->sticky) {
      $stickyclass = 'sticky-topic';
      $wassticky = TRUE;
      $topic->title = t('Sticky') . ': ' . $topic->title;
    } else {
      if ($wassticky) {
        $stickyclass = 'first-not-sticky not-sticky';
        $wassticky = FALSE;
      } else {
        $stickyclass = 'not-sticky';
      }
    }  
    ?>
    <tr class="<?php print $topic->zebra;?> <?php print $stickyclass;?>">
      <td class="icon"><div class="forum-icon"><?php print $topic->icon; ?></div></td>
      <td class="title">
      <?php 
      print $topic->title; 
      if (!empty($topic->pager)) {
        print '<div class="forum-topic-pager">' . $topic->pager . '</div>';
      }
      ?></td>
    <?php if ($topic->moved): ?>
      <td colspan="3">
      <?php print $topic->message; ?>
      </td>
    <?php else: ?>
      <td class="replies">
        <div class="num num-replies"><?php print $topic->num_comments; ?></div>
        <?php if ($topic->new_replies): ?>
          <div class="num num-new-replies"><a href="<?php print $topic->new_url; ?>"><?php print $topic->new_text; ?></a></div>
        <?php endif; ?>
      </td>
      <td class="num views"><?php print $topic->views;?></td>
      <td class="created"><?php print $topic->created; ?></td>
      <td class="last-reply"><?php print $topic->last_reply; ?></td>
    <?php endif; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php print $pager; ?>