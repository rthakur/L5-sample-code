<?php

namespace App\Observers;

use App\User;
Use App\Helpers\UsersSubscriptionHelper;


class UsersObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
     
         public function saving(User $user)
         {
             if ($user->isDirty() && !$user::$DISABLED_OBSERVER) {
                 $updating = $user->getDirty();

                 if (isset($updating['package_id']) || isset($updating['add_property_plan']))
                 {
                     $user::$DISABLED_OBSERVER = true;
                     UsersSubscriptionHelper::sendEmail($user);
                     $user::$DISABLED_OBSERVER = false;
                 }
             }
             
             return;
         }

         public function saved(User $user)
         {
             if ($user->isDirty() && !$user::$DISABLED_OBSERVER) {
                 $updating = $user->getDirty();

                 if (isset($updating['syncronize_plan_request']) && !isset($updating['package_id']) && !isset($updating['add_property_plan']))
                 {
                     $user::$DISABLED_OBSERVER = true;
                     UsersSubscriptionHelper::sendSyncronizeMessageEmail($user);
                     $user::$DISABLED_OBSERVER = false;
                 }
             }
         }

}