#!/bin/sh

set -eux

git clone --depth=1 --branch=main git@github.com:marcusirgens/magento2-script-runner.git ./script_runner
rm -rf ./script_runner/.git
