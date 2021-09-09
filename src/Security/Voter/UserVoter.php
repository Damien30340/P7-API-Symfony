<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const USER_UPDATE = 'user_edit';
    const USER_VIEW = 'user_view';
    const USER_DELETE = 'user_delete';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::USER_UPDATE, self::USER_VIEW, self::USER_DELETE])
            && $subject instanceof \App\Entity\User;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $client = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$client instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::USER_UPDATE:
                return $this->canUpdate($client, $subject);
                break;
            case self::USER_VIEW:
                return $this->canView($client, $subject);
                break;
            case self::USER_DELETE:
                return $this->canDelete($client, $subject);
                break;
        }

        return false;
    }

    /**
     * @param UserInterface|Client $client
     * @param User $user
     * @return bool
     */
    private function canUpdate(UserInterface|Client $client, User $user): bool
    {
        if($user->getClient()->getId() === $client->getId()) return true;
        return false;
    }

    /**
     * @param UserInterface|Client $client
     * @param User $user
     * @return bool
     */
    private function canView(UserInterface|Client $client, User $user): bool
    {
        if($user->getClient()->getId() === $client->getId()) return true;
        return false;
    }

    /**
     * @param UserInterface|Client $client
     * @param User $user
     * @return bool
     */
    private function canDelete(UserInterface|Client $client, User $user): bool
    {
        if($user->getClient()->getId() === $client->getId()) return true;
        return false;
    }
}
