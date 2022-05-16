<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Services\Impl;

use OpenClassrooms\Akismet\Models\Comment;
use OpenClassrooms\Akismet\Services\AkismetService;
use Symfony\Component\HttpFoundation\RequestStack;

class AkismetServiceImpl implements AkismetService
{
    private AkismetService $akismet;

    private RequestStack $requestStack;

    public function commentCheck(Comment $comment): bool
    {
        return $this->akismet->commentCheck($this->completeComment($comment));
    }

    private function completeComment(Comment $comment): Comment
    {
        $request = $this->requestStack->getMasterRequest();

        if (null === $request) {
            return $comment;
        }

        $comment->setUserIp($request->getClientIp());
        $comment->setUserAgent($request->headers->get('User-Agent'));
        $comment->setReferrer($request->headers->get('referrer'));

        return $comment;
    }

    public function submitSpam(Comment $comment)
    {
        return $this->akismet->submitSpam($this->completeComment($comment));
    }

    public function submitHam(Comment $comment)
    {
        return $this->akismet->submitHam($this->completeComment($comment));
    }

    public function setAkismet(AkismetService $akismet): void
    {
        $this->akismet = $akismet;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
