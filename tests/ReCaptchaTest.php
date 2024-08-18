<?php

use ArtMksh\ReCaptchaV2\ReCaptchaV2Verification;

use PHPUnit\Framework\TestCase;

class ReCaptchaTest extends TestCase
{
    /**
     * @var ReCaptchaV2Verification
     */
    private ReCaptchaV2Verification $captcha;

    public function setUp()
    {
        parent::setUp();
        $this->captcha = new ReCaptchaV2Verification('{secret-key}', (array)'{options}', '{retry-count');
    }

    public function testRequestShouldWorks()
    {
        $response = $this->captcha->verifyResponse('should_false');
    }
}
