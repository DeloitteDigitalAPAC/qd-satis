<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  function getSatis() {
    $collection = $this->collectionBuilder();
    $collection->taskDeleteDir('satis')
    ->taskGitStack()
      ->stopOnFail()
      ->cloneShallow('git@github.com:composer/satis.git')
    ->taskComposerInstall()
      ->dir('satis');
    return $collection->run();
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
    return $this->taskExec('php bin/satis build ../satis.json ../build')
      ->dir('satis')
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

  function build() {
    $this->stopOnFail(true);
    $this->getSatis();
    $this->getBuildRepo();
    $this->buildSatis();
    $this->publishChanges();
  }

}
