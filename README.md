QuaDrupal Packages
==================

[![Build status](https://badge.buildkite.com/feba63b5965910cd831b137204f6e0500a1ef453e3a0336883.svg)](https://buildkite.com/deloitte-digital-au/quadrupal-satis)

Simple static Composer repository generator for QuaDrupal packages.

Prepare
-------

If you **do not** have `robo` installed globally, run

    composer install

Build
-----

If you have global `robo` installed, run

   robo build

Otherwise after installing composer dependencies, run

    composer build

If you want to build and publish in one command, run

    robo build --publish

Using composer, run

    composer build -- --publish
