<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const USER_VIEW = 'user_view';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::USER_VIEW
            && $subject instanceof User;
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
        return match ($attribute) {
            self::USER_VIEW => $this->canView($client, $subject),
            default => false,
        };

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
//    private function canUpdate(): bool
//    {
//    }

//    private function canDelete(): bool
//    {
//    }
}
