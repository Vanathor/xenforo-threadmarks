<?php

class Sidane_Threadmarks_ControllerHelper_Threadmarks extends XenForo_ControllerHelper_Abstract
{

  public function getThreadmarks($thread) {
  
    if (!empty($thread['threadmark_count'])) {
      $threadmarksModel = $this->_controller->getModelFromCache('Sidane_Threadmarks_Model_Threadmarks');
  
      if (!$threadmarksModel->canViewThreadmark($thread)) {
        return null;
      }
      $menuLimit = $threadmarksModel->getMenuLimit($thread);

      $threadmarksParams = array();

      $threadmarks = $threadmarksModel->getByThreadId($thread['thread_id']);
      $totalThreadmarks = count($threadmarks);

      if ($totalThreadmarks == 0) {
        return null;
      }

      $threadmarksParams['hide_menu_from_guests'] = XenForo_Application::get('options')->sidaneThreadmarksHideMenuFromGuests;

      // to allow for changing the template modification
      // based on whether user is logged in or not
      $threadmarksParams['logged_in'] = XenForo_Visitor::getUserId() != 0;

      if ($totalThreadmarks > $menuLimit) {
        $recentThreadmarks = array_slice($threadmarks, $totalThreadmarks - $menuLimit, null, true);
        $threadmarksParams['more_threadmarks'] = true;
      } else {
        $recentThreadmarks = $threadmarks;
      }

      $threadmarksParams['all'] = $threadmarks;
      $threadmarksParams['recent'] = $recentThreadmarks;
      $threadmarksParams['count'] = $totalThreadmarks;

      $threadmarksParams['threadmarks_post_ids'] = array_map(function($threadmark) {
        return $threadmark['post_id'];
      }, $threadmarks);

      return $threadmarksParams;
    }
  }

}
