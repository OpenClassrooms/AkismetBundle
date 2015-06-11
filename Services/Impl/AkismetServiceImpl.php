<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Services\Impl;

use OpenClassrooms\Akismet\Client\Client;
use OpenClassrooms\Akismet\Models\Impl\Comment;
use OpenClassrooms\Akismet\Services\AkismetService;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class AkismetServiceImpl implements AkismetService
{

    /**
     * @var Client
     */
    private $client;

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

        $params = array(
            'user_ip'              => $request->getClientIp(),
            'user_agent'           => $request->headers->get('User-Agent'),
            'referrer'             => $request->headers->get('referrer'),
            'permalink'            => $comment->permalink,
            'comment_author'       => $comment->authorName,
            'comment_author_email' => $comment->authorEmail,
            'comment_content'      => $comment->content
        );

        return $this->client->post(self::RESOURCE, $params);
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
