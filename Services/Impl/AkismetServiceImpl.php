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
     * @return bool
     */
    public function commentCheck(Comment $comment)
    {
        $request = $this->requestStack->getCurrentRequest();

        $comment->setUserIp($request->getClientIp());
        $comment->setUserAgent($request->headers->get('User-Agent'));
        $comment->setReferrer($request->headers->get('referrer'));

        return $this->akismet->commentCheck($comment);
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
