<?php
/**
 * @file
 *   This file provides advanced forum preprocess functions for the private message module.
 */

/**
 * Implementation of hook_preprocess_forum_user().
 */
function privatemsg_preprocess_forum_user(&$variables) {
  // We don't want to do this for anonymous users.
  if ($variables['account']->uid == 0) {
    return;
  }
  
  global $user;
  $account = $variables['account'];

  // Send private message
  if (($account->uid != $user->uid) && user_access('write privatemsg')) {
    $variables['privatemsg_icon'] = theme('image', advanced_forum_path_to_images() . '/privatemsg.png', 'Private Message', 'Private Message', NULL, TRUE);
    $variables['privatemsg_text'] = t('Send PM');
    $variables['privatemsg_link'] = 'messages/new/'. $account->uid;
    $variables['privatemsg'] = '<div class="privatemsg">' . 
      l($variables['privatemsg_icon'] . ' '  . 
      $variables['privatemsg_text'], $variables['privatemsg_link'], array('absolute' => TRUE, 'html' => TRUE)) . '</div>';
  }
}