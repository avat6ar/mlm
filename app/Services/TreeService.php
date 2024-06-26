<?php

namespace App\Services;

use App\Models\User;

class TreeService
{
  public function buildTree($user, &$tree, $level)
  {
    $children = $user->children()->get();

    if ($children->isEmpty())
    {
      return;
    }

    foreach ($children as $child)
    {
      $tree[] = $child;

      $this->buildTree($child, $tree, $level + 1);
    }
  }

  public function buildTree2($users)
  {
    $tree = [];
    $userMap = [];

    foreach ($users as $user)
    {
      $userMap[$user->id] = [
        'id' => $user->id,
        'position' => $user->position,
        'username' => $user->firstname . ' ' . $user->lastname,
        'image' => '/assets/images/default-member.png',
        'children' => []
      ];
    }

    foreach ($users as $key => $user)
    {
      if ($key != 0)
      {
        $userMap[$user->tree_id]['children'][] = &$userMap[$user->id];
      }
      else
      {
        $tree[] = &$userMap[$user->id];
      }
    }

    foreach ($userMap as &$userNode)
    {
      if (count($userNode['children']) == 1)
      {
        $userNode['children'][] = [
          'id' => $user->id,
          'position' => 1,
          'username' => "No User",
          'image' => '/assets/images/default.png',
          'children' => []
        ];
      }
    }

    $this->sortChildrenByPosition($tree);

    return $tree;
  }

  private function sortChildrenByPosition(&$tree)
  {
    foreach ($tree as &$node)
    {
      if (!empty($node['children']))
      {
        usort($node['children'], function ($a, $b)
        {
          return $a['position'] - $b['position'];
        });

        $this->sortChildrenByPosition($node['children']);
      }
    }
  }
}
