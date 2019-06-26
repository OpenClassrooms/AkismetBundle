<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Services\Impl;

use OpenClassrooms\Akismet\Doubles\Client\Impl\ApiClientMock;
use OpenClassrooms\Akismet\Doubles\Models\CommentStub;
use OpenClassrooms\Akismet\Models\Impl\CommentBuilderImpl;
use OpenClassrooms\Akismet\Services\AkismetService;
use OpenClassrooms\Akismet\Services\Impl\AkismetServiceImpl as Akismet;
use OpenClassrooms\Bundle\AkismetBundle\Services\Impl\AkismetServiceImpl;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AkismetServiceImplTest
 *
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class AkismetServiceImplTest extends TestCase
{
    const BLOG_URL = 'http://www.blogdomainname.com/';

    const KEY = '123APIKey';

    const REFERRER = 'http://www.google.com';

    const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6';

    /**
     * @var AkismetService
     */
    private $akismetService;

    /**
     * @test
     */
    public function commentCheck()
    {
        ApiClientMock::$postReturn = 'true';

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
        $this->assertEquals(Akismet::COMMENT_CHECK, ApiClientMock::$resource);
        $this->assertCommentCheckParams();
    }

    private function assertCommentCheckParams()
    {
        $this->assertEquals(CommentStub::USER_IP, ApiClientMock::$params['user_ip']);
        $this->assertEquals(CommentStub::USER_AGENT, ApiClientMock::$params['user_agent']);
        $this->assertEquals(CommentStub::REFERRER, ApiClientMock::$params['referrer']);
        $this->assertEquals(CommentStub::PERMALINK, ApiClientMock::$params['permalink']);
        $this->assertEquals(CommentStub::AUTHOR_NAME, ApiClientMock::$params['comment_author']);
        $this->assertEquals(CommentStub::AUTHOR_EMAIL, ApiClientMock::$params['comment_author_email']);
        $this->assertEquals(CommentStub::CONTENT, ApiClientMock::$params['comment_content']);
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

        $this->assertEquals(Akismet::SUBMIT_SPAM, ApiClientMock::$resource);
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

        $this->assertEquals(Akismet::SUBMIT_HAM, ApiClientMock::$resource);
        $this->assertCommentCheckParams();
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
        $akismet->setApiClient(new ApiClientMock());

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
