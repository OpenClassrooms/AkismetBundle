<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Services\Impl;

use OpenClassrooms\Akismet\Models\Impl\CommentBuilderImpl;
use OpenClassrooms\Akismet\Models\Resource;
use OpenClassrooms\Akismet\Services\AkismetService;
use OpenClassrooms\Akismet\Services\Impl\AkismetServiceImpl as Akismet;
use OpenClassrooms\Akismet\Tests\Models\CommentStub;
use OpenClassrooms\Bundle\AkismetBundle\Services\Impl\AkismetServiceImpl;
use OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\Client\ClientMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AkismetServiceImplTest
 *
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class AkismetServiceImplTest extends \PHPUnit_Framework_TestCase
{
    const KEY = '123APIKey';

    const BLOG_URL = 'http://www.blogdomainname.com/';

    const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6';

    const REFERRER = 'http://www.google.com';

    /**
     * @var AkismetService
     */
    private $akismetService;

    /**
     * @test
     */
    public function commentCheck()
    {
        ClientMock::$postReturn = 'true';

        $commentBuilder = new CommentBuilderImpl();

        $response = $this->akismetService->commentCheck(
            $commentBuilder
                ->create()
                ->withPermalink(CommentStub::PERMALINK)
                ->withAuthorName(CommentStub::AUTHOR_NAME)
                ->withAuthorEmail(CommentStub::AUTHOR_EMAIL)
                ->withContent(CommentStub::CONTENT)
                ->build()
        );

        $this->assertTrue($response);
        $this->assertEquals(Resource::COMMENT_CHECK, ClientMock::$resource);
        $this->assertCommentCheckParams();
    }

    /**
     * @test
     */
    public function submitSpam()
    {
        $commentBuilder = new CommentBuilderImpl();

        $this->akismetService->submitSpam(
            $commentBuilder
                ->create()
                ->withPermalink(CommentStub::PERMALINK)
                ->withAuthorName(CommentStub::AUTHOR_NAME)
                ->withAuthorEmail(CommentStub::AUTHOR_EMAIL)
                ->withContent(CommentStub::CONTENT)
                ->build()
        );

        $this->assertEquals(Resource::SUBMIT_SPAM, ClientMock::$resource);
        $this->assertCommentCheckParams();
    }

    /**
     * @test
     */
    public function submitHam()
    {
        $commentBuilder = new CommentBuilderImpl();

        $this->akismetService->submitHam(
            $commentBuilder
                ->create()
                ->withPermalink(CommentStub::PERMALINK)
                ->withAuthorName(CommentStub::AUTHOR_NAME)
                ->withAuthorEmail(CommentStub::AUTHOR_EMAIL)
                ->withContent(CommentStub::CONTENT)
                ->build()
        );

        $this->assertEquals(Resource::SUBMIT_HAM, ClientMock::$resource);
        $this->assertCommentCheckParams();
    }

    private function assertCommentCheckParams()
    {
        $this->assertEquals(CommentStub::USER_IP, ClientMock::$params['user_ip']);
        $this->assertEquals(CommentStub::USER_AGENT, ClientMock::$params['user_agent']);
        $this->assertEquals(CommentStub::REFERRER, ClientMock::$params['referrer']);
        $this->assertEquals(CommentStub::PERMALINK, ClientMock::$params['permalink']);
        $this->assertEquals(CommentStub::AUTHOR_NAME, ClientMock::$params['comment_author']);
        $this->assertEquals(CommentStub::AUTHOR_EMAIL, ClientMock::$params['comment_author_email']);
        $this->assertEquals(CommentStub::CONTENT, ClientMock::$params['comment_content']);
    }

    protected function setUp()
    {
        $this->akismetService = new AkismetServiceImpl();
        $this->akismetService->setAkismet($this->buildAkismet());
        $this->akismetService->setRequestStack($this->buildRequestStack());
    }

    /**
     * @return Akismet
     */
    private function buildAkismet()
    {
        $akismet = new Akismet();
        $akismet->setClient(new ClientMock());

        return $akismet;
    }

    /**
     * @return RequestStack
     */
    protected function buildRequestStack()
    {
        $request = Request::create('http://localhost');
        $request->headers->set('User-Agent', self::USER_AGENT);
        $request->headers->set('referrer', self::REFERRER);
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
