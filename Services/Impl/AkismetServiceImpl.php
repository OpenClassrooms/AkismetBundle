<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Services\Impl;

use OpenClassrooms\Akismet\Models\Comment;
use OpenClassrooms\Akismet\Services\AkismetService;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class AkismetServiceImpl implements AkismetService
{

    /**
     * @var AkismetService
     */
    private $akismet;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * {@inheritdoc}
     */
    public function commentCheck(Comment $comment)
    {
        $this->overLoadComment($comment);

        return $this->akismet->commentCheck($comment);
    }

    private function overLoadComment(Comment $comment)
    {
        $request = $this->requestStack->getCurrentRequest();

        $comment->setUserIp($request->getClientIp());
        $comment->setUserAgent($request->headers->get('User-Agent'));
        $comment->setReferrer($request->headers->get('referrer'));
    }

    /**
     * {@inheritdoc}
     */
    public function submitSpam(Comment $comment)
    {
        $this->overLoadComment($comment);

        return $this->akismet->submitSpam($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function submitHam(Comment $comment)
    {
        $this->overLoadComment($comment);

        return $this->akismet->submitHam($comment);
    }

    public function setAkismet(AkismetService $akismet)
    {
        $this->akismet = $akismet;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

}
