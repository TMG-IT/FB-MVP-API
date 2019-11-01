<?php namespace App\Tests;

use App\Entity\Answer;
use App\Entity\Log\AnswerLog;
use Codeception\Module\Cli;
use Codeception\Module\Symfony;
use App\Repository\AnswerRepository;
use App\Repository\AnswerLogRepository;
use App\Repository\AnswerPlaceholderRepository;
use Codeception\Test\Unit;

class HappyPathTest extends Unit
{
    private const VALID_SESSION_CODE = '200000';
    private static $screenshotCnt = 1;

    /**
     * @var \App\Tests\FunctionalTester
     */
    protected $tester;

    /**
     * @var Symfony
     */
    private $symfony;

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    protected function _before()
    {
        /** @var Symfony $symfony */
        $this->symfony = $this->getModule('Symfony');

        /** @var Cli $cli */
        $cli = $this->getModule('Cli');

        $cli->runShellCommand('rm -rf tests/_output/*');
        $cli->runShellCommand('rm -rf tests/_output/debug/*');
    }

    protected function _after()
    {
    }

    /**
     * @throws \Exception
     */
    public function testFunctionalAppFlow(): void
    {
        $ratingAnswers = [
            0 => 'Excellent',
            1 => 'Very Good',
            2 => 'Good',
            3 => 'Acceptable',
            4 => 'Poor'
        ];

        $choiceAnswers = [
            0 => 'Strongly Agree',
            1 => 'Agree',
            2 => 'Neutral',
            3 => 'Disagree',
            4 => 'Strongly Disagree'
        ];

        $choiceAnswersChosenKeys = [];

        $this->tester->amOnUrl($_ENV['TEST_URL']);

        /**
         * Wait for session code input
         */
        $this->tester->waitForElement('.b-sessionCode', 30);
        $this->takeScreenshot();

        /**
         * Fill session code input with valid code
         */
        $this->tester->fillField('.b-sessionCode__input', self::VALID_SESSION_CODE);
        $this->takeScreenshot();

        /**
         * Click Enter Code button
         */
        $this->tester->click('button');
        $this->takeScreenshot();

        /**
         * Wait for welcome message page
         */
        $this->tester->waitForElement('.b-welcome', 30);
        $this->takeScreenshot();

        /**
         * Click Give feedback button
         */
        $this->tester->click('button');
        $this->takeScreenshot();

        /**
         * Wait for intro message
         */
        $this->tester->waitForElement('.b-chatBot', 30);
        $this->takeScreenshot();

        /**
         * Fill nickname input
         */
        $this->tester->fillField('textarea', 'Bob');
        $this->takeScreenshot();

        /**
         * Click nickname input button
         */

        $this->tester->click('button');

        $this->takeScreenshot();

        /**
         * Click I'm Ready
         */

        $this->tester->waitForElement('button', 30);

        $this->tester->click('button');

        $this->takeScreenshot();

        /**
         * Wait for the first question,
         * Click the first rating question by random choice
         */

        $this->tester->waitForElement('button', 30);

        $this->takeScreenshot();

        $ratingAnswerChosenKey = random_int(0, 4);
        $this->tester->click($ratingAnswers[$ratingAnswerChosenKey]);

        /**
         * Wait for the prompt message and click the "Lets go " button
         */

        $this->tester->waitForElement('button', 30);

        $this->tester->click('button');
        $this->takeScreenshot();

        /**
         * Run through all choice questions and click a button via random choice
         */
        for ($i=0; $i < 4; $i++) {
            $this->tester->waitForElement('button', 30);

            $choiceAnswersChosenKeys[$i] = random_int(0, 4);
            $this->tester->click($choiceAnswers[$choiceAnswersChosenKeys[$i]]);
            $this->takeScreenshot();
        }

        /**
         * Random string which will be used as an unique answer text
         */
        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

        /**
         * Wait for the last answer input field
         */
        $this->tester->waitForElement('.b-chatBot__input');
        $this->takeScreenshot();

        /**
         * Fill input with generated string
         */
        $this->tester->fillField('textarea', $randomString);
        $this->takeScreenshot();

        /**
         * Click arrow button
         */
        $this->tester->click('button');
        $this->takeScreenshot();

        /**
         * Wait for confirmation screen
         */
        $this->tester->waitForElement('.b-confirmation', 30);
        $this->takeScreenshot();

        /**
         * Click I'm done link
         */
        $this->tester->click('.btn--secondary');
        $this->takeScreenshot();

        /**
         * Check if the answers are stored to the database
         * For this, we will be using generated random string, in order to find answer with that value
         */

        /** @var AnswerRepository $answerRepository */
        $answerRepository = $this->symfony->grabService(AnswerRepository::class);
        /** @var AnswerLogRepository $answerLogRepository */
        $answerLogRepository = $this->symfony->grabService(AnswerLogRepository::class);
        /** @var AnswerPlaceholderRepository $answerRepository */
        $answerPlaceholderRepository = $this->symfony->grabService(AnswerPlaceholderRepository::class);

        /** @var Answer $answer */
        $answer = $answerRepository->findOneByText($randomString);
        /** @var AnswerLog $answerLog */
        $answerLog = $answerLogRepository->findOneByAnswer($answer);
        /** @var array $answerLogs */
        $answerLogs = $answerLogRepository->findBy(['uuid' => $answerLog->getUuid()], ['answeredAt' => 'ASC']);

        $session = $answer->getQuestion()->getSession();
        $nonPromptQuestions = [];

        /**
        * Count all the nonPrompt questions (the questions the user actually gives an answer for)
        */

        foreach ($session->getQuestions() as $question) {
            if (! $question->getIsPrompt()) {
                $nonPromptQuestions[] = $question;
            }
        }

        /**
        * Assert that we logged the answers for each counted question
        */
        $this->assertCount(count($nonPromptQuestions), $answerLogs);

        $placeholdersOrderedByAnsweredAt = [];

        /**
        * Finally, get the placeholders IDs for each AnswerLog through Answer relation
        */
        foreach ($answerLogs as $answerLog) {
            $this->assertNotNull($answerLog->getAnswer());
            $placeholder = $answerLog->getAnswer()->getAnswerPlaceholder();
            if ($placeholder)
            {
                $placeholdersOrderedByAnsweredAt[] = $answerPlaceholderRepository->findOneById($placeholder->getId());
            }
        }
        /**
        * Check that the randomized answers generated in the test fit the placeholders Text
        */
        for($i = 0; $i < (count($choiceAnswersChosenKeys) + 1); $i++) {
            if ($i === 0) {
                $this->assertEquals($placeholdersOrderedByAnsweredAt[$i]->getText(), $ratingAnswers[$ratingAnswerChosenKey]);
            }
            else{
                $this->assertEquals($placeholdersOrderedByAnsweredAt[$i]->getText(), $choiceAnswers[$choiceAnswersChosenKeys[$i-1]]);
            }
        }
    }

    /**
     * Take screenshot
     */
    private function takeScreenshot(): void
    {
        $this->tester->makeScreenshot('screenshot_' . self::$screenshotCnt);
        self::$screenshotCnt++;
    }
}
