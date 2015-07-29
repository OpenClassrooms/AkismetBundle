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
        return $this->akismet->commentCheck($this->completeComment($comment));
    }

    /**
     * @return Comment
     */
    private function completeComment(Comment $comment)
    {
        $request = $this->requestStack->getMasterRequest();

        $comment->setUserIp($request->getClientIp());
        $comment->setUserAgent($request->headers->get('User-Agent'));
        $comment->setReferrer($request->headers->get('referrer'));

        return $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function submitSpam(Comment $comment)
    {
        return $this->akismet->submitSpam($this->completeComment($comment));
    }

    /**
     * {@inheritdoc}
     */
    public function submitHam(Comment $comment)
    {
        return $this->akismet->submitHam($this->completeComment($comment));
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
