<?php

namespace Criterion\Test\Helper;

class RepoHelperTest extends \Criterion\Test\TestCase
{
    public function testShortRepoUrlGithub()
    {
        $repo = 'https://github.com/romhut/criterion';

        $short = \Criterion\Helper\Repo::short($repo);
        $this->assertEquals('romhut/criterion', $short);
    }

    public function testShortRepoUrlBitbucket()
    {
        $repo = 'https://bitbucket.org/romhut/criterion';

        $short = \Criterion\Helper\Repo::short($repo);
        $this->assertEquals('romhut/criterion', $short);
    }

    public function testShortRepoUrlFail()
    {
        $repo = 'https://nonegitsite.com/romhut/criterion';

        $short = \Criterion\Helper\Repo::short($repo);
        $this->assertEquals($repo, $short);
    }

    public function testProvider()
    {
        $repo = 'https://github.com/romhut/criterion';

        $provider = \Criterion\Helper\Repo::provider($repo);
        $this->assertEquals('github', $provider);
    }

    public function testUsername()
    {
        $repo = 'git@github.com:romhut/criterion';

        $username = \Criterion\Helper\Repo::username($repo);
        $this->assertEquals('git', $username);
    }

    public function testCloneCommand()
    {
        $project = new \Criterion\Model\Project(array(
            'repo' => 'git@github.com:romhut/criterion'
        ));

        $test = new \Criterion\Model\Test();
        $test->branch = 'master';

        $clone_command = \Criterion\Helper\Repo::cloneCommand($test, $project);
        $this->assertEquals('export GIT_SSH='.ROOT.'/bin/git; export PKEY='.ROOT.'/data/keys/'.$project->id.'; git clone -b master --depth=1 git@github.com:romhut/criterion '.$test->id, $clone_command);
    }

    public function testCloneTypeHttps()
    {
        $repo = 'https://github.com/romhut/criterion';
        $provider = \Criterion\Helper\Repo::cloneType($repo);
        $this->assertEquals('https', $provider);
    }

    public function testCloneTypeSSH()
    {
        $repo = 'git@github.com:romhut/criterion';
        $provider = \Criterion\Helper\Repo::cloneType($repo);
        $this->assertEquals('ssh', $provider);
    }

    public function testProviderFail()
    {
        $repo = 'https://nonegitsite.com/romhut/criterion';

        $provider = \Criterion\Helper\Repo::provider($repo);
        $this->assertEquals(false, $provider);
    }

}
