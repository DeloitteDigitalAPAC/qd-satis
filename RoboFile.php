<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  function satisDirExists() {
    exec('ls -l vendor/bin/satis 2> /dev/null', $output, $satis_exit_code);
    return ($satis_exit_code === 0) ? TRUE: FALSE;
  }

  function getBuildRepo() {
    $collection = $this->collectionBuilder();
    $collection->taskDeleteDir('build')
    ->taskGitStack()
      ->stopOnFail()
      ->cloneShallow('git@github.com:DeloitteDigitalAPAC/qd-satis.git', 'build', 'gh-pages');
    return $collection->run();
  }

  function buildSatis() {
    return $this->taskExec('php vendor/bin/satis build satis.json build')
      ->run();
  }

  function publishChanges() {
    $user_name = exec('git config user.name');
    $current_commit = exec('git rev-parse --verify HEAD');

    return $this->taskGitStack()
      ->stopOnFail()
      ->dir('build')
      ->add('-A')
      ->commit("Robo build - $current_commit - $user_name")
      ->push('origin', 'gh-pages')
      ->run();
  }

  function build($opts = ['publish' => false]) {
    $this->stopOnFail(TRUE);

    $this->getBuildRepo();
    $this->buildSatis();

    $this->taskGitStack()
      ->dir('build')
      ->exec('status')
      ->run();

    if ($opts['publish']) {
      $this->publishChanges();
    }
  }

  function serve() {
    $this->_exec('php -S localhost:8000 -t build');
  }

}
