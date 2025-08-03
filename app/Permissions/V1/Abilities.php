<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities {

  public const CreatePost = 'post:create';
  public const UpdatePost = 'post:update';
  public const ReplacePost = 'post:replace';
  public const DeletePost = 'post:delete';
  public const UpdateOwnPost = 'post:own:update';
  public const DeleteOwnPost = 'post:own:delete';

  public const CreateUser = 'post:create';
  public const UpdateUser = 'post:update';
  public const ReplaceUser = 'post:replace';
  public const DeleteUser = 'post:delete';

  public static function getAbilities(User $user)
  {
    if ($user->is_manager) {
      return [
        self::CreatePost,
        self::UpdatePost,
        self::DeletePost,
        self::ReplacePost,  
        self::CreateUser,
        self::UpdateUser,
        self::ReplaceUser,
        self::DeleteUser    
      ];
    } else {
      return [
        self::CreatePost,
        self::UpdateOwnPost,
        self::DeleteOwnPost,
      ];
    }
  }
}